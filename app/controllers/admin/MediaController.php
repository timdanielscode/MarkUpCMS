<?php

namespace app\controllers\admin;

use app\models\Media;
use app\models\MediaFolder;
use core\Session;
use extensions\Pagination;
use validation\Rules;
use validation\Get;
use core\http\Response;

class MediaController extends \app\controllers\Controller {

    private $_data, $_search = '',$_type = false, $_folder = 'website/assets';

    /**
     * To show 404 page with 404 status code (on not existing media)
     * 
     * @param string $id _POST media id
     * @return object MediaController
     */ 
    private function ifExists($id) {

        if(empty(Media::ifRowExists($id)) ) {

            return Response::statusCode(404)->view("/404/404")->data() . exit();
        }
    }

    /**
     * To get the folder value
     * 
     * @param array $request _GET and _POST (values)
     * @return string folder name
     */
    private function getFolder($request) {

        if(!empty($request['folder']) ) {

            $this->_folder = Get::validate($request['folder']);
        }

        return $this->_folder;
    }

    /**
     * To get the search value
     * 
     * @param array $request _GET (values)
     * @return string search value
     */
    private function getSearch($request) {

        if(!empty($request['search'])) {

            $this->_search = Get::validate($request['search']);
        }

        return $this->_search;
    }

    /**
     * To check if type is set
     * 
     * @param array $request _GET and _POST (values)
     * @return bool true | false
     */
    private function getTypes($request) {

        if(!empty($request['type']) ) {

            $this->_type = true;
        }

        return $this->_type;
    }

    /**
     * To force failed validation message on update filename (filename is equal to folder name)
     * 
     * @param array $filename media filename
     */
    private function checkIfFilenameIsFolderName($filename) {

        foreach(MediaFolder::getAll(['folder_name']) as $folder) { 

            if($folder['folder_name'] === $filename) {

                exit();
            }
        }
    }

    /**
     * To show the media create view
     * 
     * @param array $request _GET search, folder, type, filter
     * @return object MediaController, Controller
     */
    public function index($request) {

        $folders = glob($this->getFolder($request) . '/*', GLOB_ONLYDIR);
        $files = Media::where(['media_folder' => $this->getFolder($request)]);

        if(!empty($request['search'] ) ) {

            $files = Media::mediaFilesOnSearch($this->getSearch($request));
        }

        if(!empty($request['type']) ) {

            $files = $this->getOnType($request['type']);
        }

        $this->_data['search'] = $this->getSearch($request);
        $this->_data['folder'] = $this->getFolder($request);
        $this->_data['folders'] = $folders;
        $this->_data['files'] = $files;
        $this->_data['types'] = $this->getTypes($request);
        $this->_data["rules"] = [];

        return $this->view('admin/media/index')->data($this->_data);
    }

    /**
     * To get media files on filter value
     * 
     * @param array $types filter values
     * @return array media data
     */
    private function getOnType($types) {

        $filesQuery = "SELECT * FROM media";
        $count = 0;

        foreach($types as $filter) {

            $count++;

            if($count === 1) {

                $filesQuery .= " WHERE media_filetype LIKE " . "'%" . Get::validate($filter) . "%'";
            } else {
                $filesQuery .= " OR media_filetype LIKE " . "'%" . Get::validate($filter) . "%'";
            }
        }

        return Media::getOnType($filesQuery);  
    }

    /**
     * To store new media data and upload new files (on successful validation)
     * 
     * @param array $request _POST media_description _GET folder, search, type, filter
     * @return object MediaController, Controller (on failed validation)
     */
    public function store($request) {
        
        $filenames = $_FILES['file']['name'];
        $tmps = $_FILES['file']['tmp_name'];
        $sizes = $_FILES['file']['size'];
        $types = $_FILES['file']['type'];
        $errors = $_FILES['file']['error'];

        $rules = new Rules();

        if($this->validation($filenames, $types, $errors, $rules) !== false) {
                      
            foreach($filenames as $key => $filename) {
                   
                move_uploaded_file($tmps[$key], $this->getFolder($request) . '/' . $filename);

                Media::insert([
            
                    'media_filename'    => $filename,
                    'media_folder'      => $this->getFolder($request),
                    'media_filetype'    => $types[$key],
                    'media_filesize'    => $sizes[$key],
                    'media_description' => $request['media_description'],
                    'created_at' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']),
                    'updated_at' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME'])
                ]);
            }
               
            Session::set('success', 'You have successfully uploaded new file(s)!');            
            redirect('/admin/media?folder=' . $this->getFolder($request));
        } else {

            $this->_data['types'] = $this->getTypes($request);
            $this->_data['folders'] = glob($this->getFolder($request) . '/*', GLOB_ONLYDIR);
            $this->_data['files'] = Media::where(['media_folder' => $this->getFolder($request)]);
            $this->_data['rules'] = $rules->errors;

            return $this->view('admin/media/index')->data($this->_data);
        }
    }

    /**
     * To store new folder data and create a new folder or remove a folder (checking create or remove)
     * 
     * @param array $request _POST P_folder, _GET folder
     */
    public function folder($request) {

        $rules = new Rules();

        if($rules->media_folder($request['P_folder'])->validated() ) {

            $this->deleteOrAdd($request);

        } else {

            $this->_data['folders'] = glob($this->getFolder($request) . '/*', GLOB_ONLYDIR);
            $this->_data['files'] = Media::where(['media_folder' => $this->getFolder($request)]);
            $this->_data['types'] = $this->getTypes($request);
            $this->_data['rules'] = $rules->errors;

            return $this->view('admin/media/index')->data($this->_data);
        }

        redirect('/admin/media?folder=' . $this->getFolder($request));
    }

    /**
     * To check for to delete a folder or remove a folder (on successful validation)
     * 
     * @param array $request _POST P_folder, _GET folder
     */
    private function deleteOrAdd($request) {

        if(file_exists($this->getFolder($request) . '/' . $request['P_folder']) === true) {

            $this->deleteFolder($request);
        } else {
            $this->addFolder($request);
        }  
    }

    /**
     * To remove a folder
     * 
     * @param array $request _POST P_folder, _GET folder
     */
    private function deleteFolder($request) {

        Session::set('success', 'You have successfully removed the folder!');
        rmdir($this->getFolder($request) . '/' . $request['P_folder']);
    }

    /**
     * To store new folder data and create a new folder (on successful validation)
     * 
     * @param array $request _POST P_folder, _GET folder
     * @return object MediaController, Controller (on failed validation)
     */
    private function addFolder($request) {

        $this->insertFolder($request['P_folder']);
        Session::set('success', 'You have successfully added the folder!');
        mkdir($this->getFolder($request) . '/' . $request['P_folder'], 0777, true); 
    }

    /**
     * To store new folder data
     * 
     * @param array $folder folder name
     */
    private function insertFolder($folder) {

        if(empty(MediaFolder::where(['folder_name' => $folder]) ) ) {

            MediaFolder::insert([

                'folder_name' => $folder
            ]);
        }
    }

    /**
     * To validate files
     * 
     * @param array $filenames filenames 
     * @param array $types file types
     * @param array $errors file errors 
     * @param object $rules Rules
     * @return bool true | false 
     */
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

    /**
     * To show failed validation error messages
     * 
     * @param string $file filename
     * @param object $rules
     */
    private function checkSelected($file, $rules) {

        if(empty($file) || $file === null) {

            $rules->errors[] = ['file' => "No file selected."];
        }
    }

    /**
     * To show failed validation error messages
     * 
     * @param array $errors file errors
     * @param string $error file key value
     * @param object $rules Rules
     */
    private function checkFilesize($errors, $error, $rules) {

        if($errors[$error] === 1) {

            $rules->errors[] = ["file" => "Filesize is to big."];
        }
    }

    /**
     * To show failed validation error messages
     * 
     * @param array $file filename
     * @param object $rules Rules
     */
    private function checkExists($file, $rules) {

        if(!empty(Media::where(['media_filename' => $file]) ) || !empty(MediaFolder::where(['folder_name' => $file]))) {
    
            $rules->errors[] = ["file" => "File already exists."];
        }
    }

    /**
     * To show failed validation error messages
     * 
     * @param array $types file mime type
     * @param string $file file key value
     * @param object $rules Rules
     */
    private function checkType($types, $file, $rules) {

        if(in_array($types[$file], ['image/png', 'image/jpeg', 'image/gif', 'image/webp', 'image/svg+xml', 'application/pdf', 'video/mp4', 'video/quicktime']) === false) {
                    
            $rules->errors[] = ["file" => "File mime type is not valid."];
        }
    }

    /**
     * To show failed validation error messages
     * 
     * @param array $file filename
     * @param object $rules Rules
     */
    private function checkSpecial($file, $rules) {

        if(preg_match('/[#$%^&*()+=\\[\]\';,\/{}|":<>?~\\\\]/', $file)) {
                    
            $rules->errors[] = ["file" => "Filename cannot contains any special characters."];
        } 
    }

    /**
     * To show failed validation error messages
     * 
     * @param array $file filename
     * @param object $rules Rules
     */
    private function checkMax($file, $rules) {

        if(strlen($file) > 49) {
                    
            $rules->errors[] = ["file" => "Filename cannot contain more than 50 characters."];
        }
    }

    /**
     * To check if validation errors exist
     * 
     * @param object $rules Rules
     * @return bool false
     */
    private function checkErrors($rules) {

        if(!empty($rules->errors)) {

            return false;
        }
    }

    /**
     * To update media data (filename) (on successful validation)
     * 
     * @param array $request _POST id (media id), filename
     */
    public function updateFilename($request) {

        $this->ifExists($request['id']);
        $this->checkIfFilenameIsFolderName($request['filename']);

        $rules = new Rules();

        if($rules->media_filename($request, Media::checkMediaFilenameOnId($request['filename'], $request['id']))->validated()) {
        
            rename(Media::getColumns(['media_folder'], $request['id'])['media_folder'] . '/' . Media::getColumns(['media_filename'], $request['id'])['media_filename'], Media::getColumns(['media_folder'], $request['id'])['media_folder'] . '/' . $request['filename']);
        
            Media::update(['id' => $request['id']], [
                            
                'media_filename'    => $request['filename'],
                'updated_at' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME'])
            ]);
        }

        Session::set('success', 'You have successfully updated the filename!');
        redirect("/admin/media?folder=" . Media::getColumns(['media_folder'], $request['id'])['media_folder']);
    }

    /**
     * To update media data (description) (on successful validation)
     * 
     * @param array $request id (media id), description
     */
    public function updateDescription($request) {

        $this->ifExists($request['id']);

        $rules = new Rules();

        if($rules->media_description($request['description'])->validated()) {

            Media::update(['id' => $request['id']], [
                    
                'media_description' => $request['description'],
                'updated_at' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME'])
            ]);
            
            Session::set('success', 'You have successfully updated the description!');
            redirect("/admin/media?folder=" . Media::getColumns(['media_folder'], $request['id'])['media_folder']);
        }
    }

    /**
     * To remove media files
     * 
     * @param array $request _POST deleteIds (media deleteIds), _GET folder
     */
    public function delete($request) {

        $ids = explode(',', $request['deleteIds']);

        foreach(array_filter($ids) as $id) {

            $file = Media::whereColumns(['media_folder', 'media_filename'], ['id' => $id]);
            unlink($file[0]['media_folder'] . '/' . $file[0]['media_filename']);
            Media::delete('id', $id);
        }

        Session::set('success', 'You have successfully removed the file(s)!');
        redirect('/admin/media?folder=' . $file[0]['media_folder']);
    }
}