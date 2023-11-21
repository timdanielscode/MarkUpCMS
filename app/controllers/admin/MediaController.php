<?php

namespace app\controllers\admin;

use app\controllers\Controller;
use app\models\Media;
use app\models\MediaFolder;
use core\Session;
use core\Csrf;
use extensions\Pagination;
use validation\Rules;
use validation\Get;
use core\http\Response;

class MediaController extends Controller {

    private $_count, $_data;

    private function ifExists($id) {

        if(empty(Media::ifRowExists($id)) ) {

            return Response::statusCode(404)->view("/404/404") . exit();
        }
    }

    private function redirect($inputName, $path) {

    //    if(submitted($inputName) === false || Csrf::validate(Csrf::token('get'), post('token')) === false ) { 
            
    //        redirect($path) . exit(); 
    //    } 
    }

    private function getFolderPath($request) {

        if(empty($request['folder']) === true) {

            return 'website/assets';
        } 

        return Get::validate($request['folder']);
    }

    private function getSearch($request) {

        if(!empty($request['search']) === true) {

            return Get::validate($request['search']);
        }

        return '';
    }
 
    public function index($request) {

        $this->_data['search'] = $this->getSearch($request);
        $this->_data["allMedia"] = $this->getMedia($request);
        $this->_data['count'] = $this->_count;
        $this->_data['numberOfPages'] = Pagination::getPageNumbers();

        return $this->view('admin/media/index')->data($this->_data);
    }

    private function getMedia($request) {

        $media = Media::allMediaButOrdered();
    
        if(!empty($request['search'])) {
    
            $media = Media::mediaFilesOnSearch(Get::validate($request['search']));
        }
    
        $this->_count = count($media);
        return Pagination::get($media, 8);
    }

    private function getFolders($request) {

        if(!empty($request['folder'])) {

            glob($this->getFolderPath($request) . '/*', GLOB_ONLYDIR);
        }

        return glob($this->getFolderPath($request) . '/*', GLOB_ONLYDIR);
    }

    public function create($request) {

        $folders = $this->getFolders($request);
        $files = Media::where(['media_folder' => $this->getFolderPath($request)]);
        
        if(!empty($request['search'])) {

            $files = Media::mediaFilesOnSearch(Get::validate($request['search']));

        } else if(!empty($request['type']) ) {

            $files = $this->getOnType($request['type']);
        }

        $this->_data['folders'] = $folders;
        $this->_data['files'] = $files;
        $this->_data["rules"] = [];

        return $this->view('admin/media/create')->data($this->_data);
    }

    private function getOnType($types) {

        $filesQuery = "SELECT * FROM media";
        $count = 0;

        foreach($types as $type) {

            $count++;

            if($count === 1) {

                $filesQuery .= " WHERE media_filetype LIKE " . "'%" . Get::validate($type) . "%'";
            } else {
                $filesQuery .= " OR media_filetype LIKE " . "'%" . Get::validate($type) . "%'";
            }
        }
        
        return Media::getOnType($filesQuery);  
    }

    public function store($request) {

        $this->redirect("submit", '/admin/media');

        $filenames = $_FILES['file']['name'];
        $tmps = $_FILES['file']['tmp_name'];
        $sizes = $_FILES['file']['size'];
        $types = $_FILES['file']['type'];
        $errors = $_FILES['file']['error'];

        $rules = new Rules();

        if($this->validation($filenames, $types, $errors, $rules) !== false) {
                      
            foreach($filenames as $key => $filename) {
                   
                move_uploaded_file($tmps[$key], $this->getFolderPath($request) . '/' . $filename);

                Media::insert([
            
                    'media_filename'    => $filename,
                    'media_folder'      => $this->getFolderPath($request),
                    'media_filetype'    => $types[$key],
                    'media_filesize'    => $sizes[$key],
                    'media_description' => $request['media_description'],
                    'created_at' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']),
                    'updated_at' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME'])
                ]);
            }
               
            Session::set('success', 'You have successfully uploaded new file(s)!');            
            redirect('/admin/media/create?folder=' . $this->getFolderPath($request));
        } else {

            $this->_data['folders'] = glob($this->getFolderPath($request) . '/*', GLOB_ONLYDIR);
            $this->_data['files'] = Media::where(['media_folder' => $this->getFolderPath($request)]);
            $this->_data['rules'] = $rules->errors;

            return $this->view('admin/media/create')->data($this->_data);
        }
    }

    public function folder($request) {

        if(file_exists($this->getFolderPath($request) . '/' . $request['P_folder']) === true) {

            $this->deleteFolder($request);
        } else {
            $this->addFolder($request);
        }   
  
        redirect('/admin/media/create?folder=' . $this->getFolderPath($request));
    }

    private function deleteFolder($request) {

        $this->redirect("submitFolder", '/admin/media');
        Session::set('success', 'You have successfully removed the folder!');
        rmdir($this->getFolderPath($request) . '/' . $request['P_folder']);
    }

    private function addFolder($request) {

        $this->redirect("submitFolder", '/admin/media/create');

        $rules = new Rules();

        if($rules->insert_media_folder($request['P_folder'])->validated() ) {

            $this->insertFolder($request['P_folder']);
            Session::set('success', 'You have successfully added the folder!');
            mkdir($this->getFolderPath($request) . '/' . $request['P_folder'], 0777, true); 

        } else {

            $this->_data['folders'] = glob($this->getFolderPath($request) . '/*', GLOB_ONLYDIR);
            $this->_data['files'] = Media::where(['media_folder' => $this->getFolderPath($request)]);
            $this->_data['rules'] = $rules->errors;

            return $this->view('admin/media/create')->data($this->_data);
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

        if($rules->update_media_filename($request['filename'], Media::checkMediaFilenameOnId($request['filename'], $request['id']))->validated()) {
        
            rename($request['folder'] . '/' . Media::get($request['id'])['media_filename'], $request['folder'] . '/' . $request['filename']);
        
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

        if($rules->update_media_description($request['description'])->validated()) {

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

        $this->redirect("deleteIds", '/admin/media');
        $this->deleteFiles($request);

        redirect('/admin/media/create?folder=' . $this->getFolderPath($request));
    }

    private function deleteFiles($request) {

        $ids = explode(',', $request['deleteIds']);

        foreach($ids as $id) {

            $file = Media::get($id);
            unlink($this->getFolderPath($request) . '/' . $file['media_filename']);
            Media::delete('id', $id);
        }

        Session::set('success', 'You have successfully removed the file(s)!');
    }
}