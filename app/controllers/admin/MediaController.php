<?php

namespace app\controllers\admin;

use app\controllers\Controller;
use database\DB;
use app\models\Media;
use app\models\MediaFolder;
use core\Session;
use core\Csrf;
use extensions\Pagination;
use validation\Rules;
use validation\Get;
use core\http\Response;

class MediaController extends Controller {

    private $_folderPath = '';

    public function __construct() {

        if(empty(Get::validate([get('folder')])) ) {

            $this->_folderPath = 'website/assets';
        } else {
            $this->_folderPath = Get::validate([get('folder')]);
        }
    }

    private function ifExists($id) {

        if(empty(Media::ifRowExists($id)) ) {

            return Response::statusCode(404)->view("/404/404") . exit();
        }
    }

    private function redirect($inputName, $path) {

        if(submitted($inputName) === false || Csrf::validate(Csrf::token('get'), post('token')) === false ) { 
            
            redirect($path) . exit(); 
        } 
    }

    public function index() {

        $allMedia = Media::allMediaButOrdered();
        $search = Get::validate([get('search')]);

        if(!empty($search) ) {

            $allMedia = Media::mediaFilesOnSearch($search);
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

    public function create() {

        $folders = glob($this->_folderPath . '/*', GLOB_ONLYDIR);
        $files = Media::where(['media_folder' => $this->_folderPath]);

        if(!empty(Get::validate([get('search')]))) {

            $searchValue = Get::validate([get('search')]);
            $files = Media::mediaFilesOnSearch($searchValue);

        } else if(!empty($_GET['type']) ) {

            $filesQuery = "SELECT * FROM media";
            $count = 0;

            foreach($_GET['type'] as $value) {

                $count++;

                if($count === 1) {
                    $filesQuery .= " WHERE media_filetype LIKE " . "'%" . Get::validate([$value]) . "%'";
                } else {
                    $filesQuery .= " OR media_filetype LIKE " . "'%" . Get::validate([$value]) . "%'";
                }
            }
            
            $files = DB::try()->raw($filesQuery)->fetch();  
        }
        
        $data['folders'] = $folders;
        $data['files'] = $files;
        $data["rules"] = [];

        return $this->view('admin/media/create', $data);
    }

    public function store($request) {

        $this->redirect("submit", '/admin/media/create?folder=' . Get::validate([get('folder')]));

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
                    'created_at' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']),
                    'updated_at' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME'])
                ]);
            }
               
            Session::set('success', 'You have successfully uploaded new file(s)!');            
            redirect('/admin/media/create?folder=' . Get::validate([get('folder')]));
        } else {

            $folders = glob($this->_folderPath . '/*', GLOB_ONLYDIR);
            $files = Media::where(['media_folder' => $this->_folderPath]);

            $data['folders'] = $folders;
            $data['files'] = $files;
            $data['rules'] = $rules->errors;

            return $this->view('admin/media/create', $data);
        }
    }

    public function folder($request) {

        if(file_exists($this->_folderPath . '/' . $request['P_folder']) === true) {

            $this->deleteFolder($request);
        } else {
            $this->addFolder($request);
        }   
  
        redirect('/admin/media/create?folder=' . Get::validate([get('folder')]));
    }

    private function deleteFolder($request) {

        $this->redirect("submitFolder", '/admin/media/create?folder=' . Get::validate([get('folder')]));
        rmdir($this->_folderPath . '/' . $request['P_folder']);
    }

    private function addFolder($request) {

        $this->redirect("submitFolder", '/admin/media/create?folder=' . Get::validate([get('folder')]));

        $rules = new Rules();

        if($rules->insert_media_folder()->validated() ) {

            $this->insertFolder($request['P_folder']);
            Session::set('success', 'You have successfully added the folder!');
            mkdir($this->_folderPath . '/' . $request['P_folder'], 0777, true); 

        } else {

            $data['folders'] = glob($this->_folderPath . '/*', GLOB_ONLYDIR);
            $data['files'] = Media::where(['media_folder' => $this->_folderPath]);
            $data['rules'] = $rules->errors;

            return $this->view('admin/media/create', $data);
        }
    }

    private function insertFolder($folder) {

        if(empty(MediaFolder::where(['folder_name' => $folder]) ) ) {

            MediaFolder::insert([

                'folder_name' => $folder
            ]);
        }
    }

    private function validation($filenames, $types, $errors, $rules) {

        foreach($filenames as $key => $value) {

            $this->checkSelected($value, $rules);
            $this->checkFilesize($errors, $key, $rules);
            $this->checkExists($value, $rules);
            $this->checkType($types, $key, $rules);
            $this->checkSpecial($value, $rules);
            $this->checkMax($value, $rules);
        }

        return $this->checkErrors($rules);
    }

    private function checkSelected($file, $rules) {

        if(empty($file) || $file === null) {

            $rules->errors[] = ['file' => "No file selected."];
         }
    }

    private function checkFilesize($errors, $error, $rules) {

        if($errors[$error] === 1) {

            $rules->errors[] = ["file" => "Filesize is to big."];
        }
    }

    private function checkExists($file, $rules) {

        if(!empty(Media::where(['media_filename' => $file]) )) {
    
            $rules->errors[] = ["file" => "File already exists."];
        }
    }

    private function checkType($types, $file, $rules) {

        if(in_array($types[$file],['image/png', 'image/jpeg', 'image/gif', 'image/webp', 'image/svg+xml', 'application/pdf', 'video/mp4', 'video/quicktime']) === false) {
                    
            $rules->errors[] = ["file" => "File mime type is not valid."];
        }
    }

    private function checkSpecial($file, $rules) {

        if(preg_match('/[#$%^&*()+=\\[\]\';,\/{}|":<>?~\\\\]/', $file)) {
                    
            $rules->errors[] = ["file" => "Filename cannot contains any special characters."];
        } 
    }

    private function checkMax($file, $rules) {

        if(strlen($file) > 49) {
                    
            $rules->errors[] = ["file" => "Filename cannot contain more than 50 characters."];
        }
    }

    private function checkErrors($rules) {

        if(!empty($rules->errors)) {

            return false;
        }
    }

    public function UPDATEFILENAME($request) {

        $this->ifExists($request['id']);
        $this->checkIfFilenameIsFolderName($request['filename']);

        $rules = new Rules();

        if($rules->update_media_filename(Media::checkMediaFilenameOnId($request['filename'], $request['id']))->validated()) {
        
            $currentFile = Media::get($request['id']);
            $currentFileName = $currentFile['media_filename'];
        
            rename($request['folder'] . '/' . $currentFileName, $request['folder'] . '/' . $request['filename']);
        
            Media::update(['id' => $request['id']], [
                            
                'media_filename'    => $request['filename'],
                'updated_at' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME'])
            ]);
                    
            echo json_encode($request);
        }
    }

    private function checkIfFilenameIsFolderName($filename) {

        foreach(MediaFolder::getAll(['folder_name']) as $folder) { 

            if($folder['folder_name'] === $filename) {

                exit();
            }
        }
    }

    public function UPDATEDESCRIPTION($request) {

        $this->ifExists($request['id']);

        $rules = new Rules();

        if($rules->update_media_description()->validated()) {

            Media::update(['id' => $request['id']], [
                    
                'media_description' => $request['description'],
                'updated_at' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME'])
            ]);
            
            echo json_encode($request);
        }
    }

    public function delete($request) {

        $this->redirect("deleteIds", '/admin/media');
        $this->deleteFiles($request);

        redirect("/admin/media");
    }

    public function deleteCreate($request) {

        $this->redirect("deleteIds", '/admin/media/create?folder=' . Get::validate([get('folder')]));
        $this->deleteFiles($request);

        redirect('/admin/media/create?folder=' . Get::validate([get('folder')]));
    }

    private function deleteFiles($request) {

        $ids = explode(',', $request['deleteIds']);

        foreach($ids as $id) {

            $file = Media::get($id);
            unlink($this->_folderPath . '/' . $file['media_filename']);
            Media::delete('id', $id);
        }

        Session::set('success', 'You have successfully removed the file(s)!');
    }
}