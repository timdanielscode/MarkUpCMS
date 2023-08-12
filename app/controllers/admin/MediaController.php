<?php

namespace app\controllers\admin;

use app\controllers\Controller;
use database\DB;
use app\models\Media;
use core\Session;
use core\Csrf;
use extensions\Pagination;
use validation\Rules;
use core\http\Response;

class MediaController extends Controller {

    private $_folderPath = '';

    public function __construct() {

        if(empty(get('folder')) ) {

            $this->_folderPath = 'website/assets';
        } else {
            $this->_folderPath = get('folder');
        }
    }

    private function ifExists($id) {

        $media = new Media();

        if(empty($media->ifRowExists($id)) ) {

            return Response::statusCode(404)->view("/404/404") . exit();
        }
    }

    public function index() {

        $media = new Media();
        $allMedia = $media->allMediaButOrdered();
        
        $search = get('search');

        if(!empty($search) ) {

            $allMedia = $media->mediaFilesOnSearch($search);
        }
        
        $count = count($allMedia);

        $allMedia = Pagination::get($allMedia, 8);
        $numberOfPages = Pagination::getPageNumbers();

        $data['count'] = $count;
        $data["allMedia"] = $allMedia;
        $data['numberOfPages'] = $numberOfPages;
        $data['search'] = $search;

        return $this->view('admin/media/index', $data);
    }

    public function TABLE() {

        $media = new Media();
        $allMedia = $media->allMediaButOrdered();

        $search = get('search');

        if(!empty($search) ) {

            $allMedia = $media->mediaFilesOnSearch($search);
        }

        $allMedia = Pagination::get($allMedia, 8);
        $numberOfPages = Pagination::getPageNumbers();

        $data['allMedia'] = $allMedia;
        $data['numberOfPages'] = $numberOfPages;
        $data['search'] = $search;

        return $this->view('admin/media/table', $data);
    }

    public function READ($request) {

        $this->ifExists($request['id']);

        $media = Media::where('id', '=', $request['id'])[0];

        switch ($media['media_filetype']) {

            case 'image/png':
            case 'image/webp':
            case 'image/gif':
            case 'image/jpeg':
            case 'image/svg+xml':

                $file = '<img id="mediaPreviewFile" class="display-none" src="/website/assets/img/' . $media['media_filename'] . '">';
            break;
            case 'video/mp4':
            case 'video/quicktime':

                $file = '<video id="mediaPreviewFile" class="display-none" src="/website/assets/video/' . $media['media_filename'] . '" controls></video>';
            break;  
            case 'application/pdf':

                $fileDestination = "website/assets/application/".$media['media_filename'];
                $file = '<iframe id="mediaPreviewFile" class="display-none" src="/website/assets/application/' . $media['media_filename'] . '"></iframe>';
            break;
            default:

                $fileDestination = '';
            break;
        }

        echo $file;
    }

    public function create() {

        $folders = glob($this->_folderPath . '/*', GLOB_ONLYDIR);
        $files = DB::try()->select('*')->from('media')->where('media_folder', '=', $this->_folderPath)->fetch();

        if(!empty(get('search'))) {

            $searchValue = get('search');
            $files = DB::try()->select('*')->from('media')->where('media_filename', 'LIKE', '%'.$searchValue.'%')->or('media_filetype', 'LIKE', '%'.$searchValue.'%')->or('date_created_at', 'LIKE', '%'.$searchValue.'%')->or('date_updated_at', 'LIKE', '%'.$searchValue.'%')->or('time_created_at', 'LIKE', '%'.$searchValue.'%')->or('time_updated_at', 'LIKE', '%'.$searchValue.'%')->fetch();    
        }
        
        /*else if(!empty($_GET['type']) ) {

            $filesQuery = "SELECT * FROM media";
            $count = 0;

            foreach($_GET['type'] as $value) {

                $count++;

                if($count === 1) {
                    $filesQuery .= " WHERE media_filetype LIKE " . "'%" . $value . "%'";
                } else {
                    $filesQuery .= " OR media_filetype LIKE " . "'%" . $value . "%'";
                }
            }
            
            $files = DB::try()->raw($filesQuery)->fetch();  
        }*/
        
        $data['folders'] = $folders;
        $data['files'] = $files;
        $data["rules"] = [];

        return $this->view('admin/media/create', $data);
    }

    public function store($request) {

        if(submitted('submit') && Csrf::validate(Csrf::token('get'), post('token') ) === true) {

            $filenames = $_FILES['file']['name'];
            $tmps = $_FILES['file']['tmp_name'];
            $sizes = $_FILES['file']['size'];
            $types = $_FILES['file']['type'];
            $errors = $_FILES['file']['error'];

            $rules = new Rules();

            if($this->validation($filenames, $types, $errors, $rules) !== false) {
                      
                foreach($filenames as $key => $filename) {
                   
                    move_uploaded_file($tmps[$key], $this->_folderPath . '/' . $filename);

                    Media::insert([
            
                        'media_filename'    => $filename,
                        'media_folder'      => $this->_folderPath,
                        'media_filetype'    => $types[$key],
                        'media_filesize'    => $sizes[$key],
                        'media_description' => $request['media_description'],
                        'date_created_at'   => date("d/m/Y"),
                        'time_created_at'   => date("H:i"),
                        'date_updated_at'   => date("d/m/Y"),
                        'time_updated_at'   => date("H:i")
                    ]);
                }
               
                Session::set('create', 'You have successfully created a new post!');            
                redirect('/admin/media/create?folder=' . get('folder'));
            } else {

                $folders = glob($this->_folderPath . '/*', GLOB_ONLYDIR);
                $files = DB::try()->select('*')->from('media')->where('media_folder', '=', $this->_folderPath)->fetch();

                $data['folders'] = $folders;
                $data['files'] = $files;
                $data['rules'] = $rules->errors;

                return $this->view('admin/media/create', $data);
            }

        } else if(submitted('submitFolder')) {

            $this->folder($request);
        } else if(submitted('submitDelete')) {
            $this->deleteFiles($request);
        }
    }

    private function deleteFiles($request) {

        $fileIds = explode(',', $request['files']);

        foreach($fileIds as $fileId) {

            $filename = Media::where('id', '=', $fileId)[0]['media_filename'];
            Media::delete('id', $fileId);
            unlink($this->_folderPath . '/' . $filename);
        }

        redirect('/admin/media/create?folder=' . get('folder'));
    }

    private function folder($request) {

        if(file_exists($this->_folderPath . '/' . $request['P_folder']) === true) {

            rmdir($this->_folderPath . '/' . $request['P_folder']);
        } else {
            mkdir($this->_folderPath . '/' . $request['P_folder'], 0777, true); 
        }
        
        redirect('/admin/media/create?folder=' . get('folder'));
    }

    private function validation($filenames, $types, $errors, $rules) {

        if(!empty($filenames) && $filenames !== null) {

            foreach($filenames as $key => $value) {

                if(empty($value) || $value === null) {

                    $rules->errors[] = ['file' => "No file selected."];
                } else if($errors[$key] === 1) {
                    $rules->errors[] = ["file" => "Filesize is to big."];
                }

                $unique = DB::try()->select('id')->from('media')->where('media_filename', '=', $value)->fetch();

                if(!empty($unique)) {
    
                    $rules->errors[] = ["file" => "File already exists."];
                } else if(in_array($types[$key],['image/png', 'image/jpeg', 'image/gif', 'image/webp', 'image/svg+xml', 'application/pdf', 'video/mp4', 'video/quicktime']) === false) {
                    
                    $rules->errors[] = ["file" => "File mime type is not valid."];
                } else if(preg_match('/[#$%^&*()+=\\[\]\';,\/{}|":<>?~\\\\]/', $value)) {
                    
                    $rules->errors[] = ["file" => "Filename cannot contains any special characters."];
                } else if(strlen($value) > 49) {
                    
                    $rules->errors[] = ["file" => "Filename cannot contain more than 50 characters."];
                }
            }
    
            if(!empty($rules->errors)) {

                return false;
            }
        } 
    }

    public function UPDATE($request) { 
        
        $this->ifExists($request['id']);

        $data['id'] = $request['id'];
        $data['description'] = $request['description'];

        $rules = new Rules();

        if($rules->media_update_title_description()->validated() ) {

            Media::update(['id' => $request['id']], [

                'media_description' => $request['description']
            ]);

            echo json_encode($data);
        }
    }

    public function UPDATEFILENAME($request) {

        $this->ifExists($request['id']);

        $rules = new Rules();

        $uniqueFilename = DB::try()->select('media_filename')->from('media')->where('media_filename', '=', $request['filename'])->and('id', '!=', $request['id'])->fetch();

        if($rules->update_media_filename($uniqueFilename)->validated()) {

            $currentFile = Media::where('id', '=', $request['id'])[0];
            $currentFileName = $currentFile['media_filename'];

            rename($request['folder'] . '/' . $currentFileName, $request['folder'] . '/' . $request['filename']);

            Media::update(['id' => $request['id']], [
                    
                'media_filename'    => $request['filename']
            ]);
            
            echo json_encode($request);
        }
    }

    public function UPDATEDESCRIPTION($request) {

        $this->ifExists($request['id']);

        $rules = new Rules();

        if($rules->update_media_description()->validated()) {

            Media::update(['id' => $request['id']], [
                    
                'media_description'    => $request['description']
            ]);
            
            echo json_encode($request);
        }
    }

    public function delete($request) {

        if(!empty($request['deleteIds']) && $request['deleteIds'] !== null) {

            $ids = explode(',', $request['deleteIds']);

            foreach($ids as $id) {

                $this->ifExists($id);

                $file = Media::where('id', '=', $id)[0];
                unlink($this->_folderPath . '/' . $file['media_filename']);
                Media::delete('id', $id);
                
            }

            redirect("/admin/media");
        }
    }
}