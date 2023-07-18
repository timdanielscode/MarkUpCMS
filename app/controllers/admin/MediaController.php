<?php

namespace app\controllers\admin;

use app\controllers\Controller;
use database\DB;
use app\models\Media;
use core\Session;
use core\Csrf;
use extensions\Pagination;
use validation\Rules;

class MediaController extends Controller {

    public function index() {
        
        $media = new Media();
        $allMedia = $media->allMediaButOrdered();

        $count = count($allMedia);
        $search = get('search');

        if(!empty($search) ) {

            $allMedia = $media->mediaFilesOnSearch($search);
        }
        
        $allMedia = Pagination::get($allMedia, 1);
        $numberOfPages = Pagination::getPageNumbers();

        $data["allMedia"] = $allMedia;
        $data['numberOfPages'] = $numberOfPages;
        $data['count'] = $count;

        return $this->view('admin/media/index', $data);
    }

    public function TABLE() {

        $media = new Media();
        $allMedia = $media->allMediaButOrdered();

        $allMedia = Pagination::get($allMedia, 1);
        $numberOfPages = Pagination::getPageNumbers();

        $data['allMedia'] = $allMedia;
        $data['numberOfPages'] = $numberOfPages;

        return $this->view('admin/media/table', $data);
    }

    public function EDIT($request) {

        $media = Media::where('id', '=', $request['id'])[0];

        $mediaTitle = $media['media_title'];
        $mediaDescription = $media['media_description'];

        $data['mediaTitle'] = $mediaTitle;
        $data['mediaDescription'] = $mediaDescription;
        $data['id'] = $request['id'];

        return $this->view('admin/media/modal', $data);
    }

    public function READ($request) {

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

                $file = '<video id="mediaPreviewFile" class="display-none" src="/website/assets/video/' . $media['media_filename'] . '"></video>';
            break;  
            case 'application/pdf':

                $fileDestination = "website/assets/application/".$media['media_filename'];
                $file = '<iframe id="mediaPreviewFile" class="display-none" src="/website/assets/application/' . $media['media_filename'] . '"></iframe>';
            break;
            default:

                $fileDestination = '';
            break;
        }

        echo '<a href="#" id="mediaPreviewClose">Close</a>';
        echo $file;
    }

    public function create() {

        $data["rules"] = [];

        return $this->view('admin/media/create', $data);
    }

    public function store($request) {

        if(submitted('submit') && Csrf::validate(Csrf::token('get'), post('token') ) === true) {

            $filenames = $_FILES['file']['name'];
            $tmp = $_FILES['file']['tmp_name'];
            $size = $_FILES['file']['size'];
            $type = $_FILES['file']['type'];

            $rules = new Rules();

            foreach($filenames as $key => $filename) {

                $uniqueFilename = Media::where('media_filename', '=', $filename);

                if($rules->media($uniqueFilename)->validated()) {

                    switch ($type[$key]) {
        
                        case 'image/png':
                        case 'image/webp':
                        case 'image/gif':
                        case 'image/jpeg':
                        case 'image/svg+xml':
        
                            $fileDestination = "website/assets/img/".$filename;
                        break;
                        case 'video/mp4':
                        case 'video/quicktime':
        
                            $fileDestination = "website/assets/video/".$filename;
                        break;  
                        case 'application/pdf':
        
                            $fileDestination = "website/assets/application/".$filename;
                        break;
                        default:
        
                            $fileDestination = '';
                        break;
                    }
                        
                    move_uploaded_file($tmp[$key], $fileDestination);
        
                    Media::insert([
        
                        'media_title'   => $request['media_title'],
                        'media_description' => $request['media_description'],
                        'media_filename'    => $filename,
                        'media_filetype'    => $type[$key],
                        'media_filesize'    => $size[$key],
                        'date_created_at'   => date("d/m/Y"),
                        'time_created_at'   => date("H:i"),
                        'date_updated_at'   => date("d/m/Y"),
                        'time_updated_at'   => date("H:i")
                    ]);
               
    
                    Session::set('create', 'You have successfully created a new post!');            
                    redirect('/admin/media');

                } else {

                    $data['rules'] = $rules->errors;
                    return $this->view('admin/media/create', $data);
                }
            } 
        }
    }

    public function UPDATE($request) { 

        if(!empty($request['filename']) && $request['filename'] !== null) {

            $data['id'] = $request['id'];
            $data['filename'] = $request['filename'];
    
            $rules = new Rules();
    
            if($rules->update_media_filename()->validated()) {

                $this->updateFilename($request);

                echo json_encode($data);
            }

        } else if (!empty($request['title']) && $request['title'] !== null) {
            
            $data['id'] = $request['id'];
            $data['title'] = $request['title'];
            $data['description'] = $request['description'];

            $this->updateTitleAndDescription($request);

            echo json_encode($data);
        }
    }

    private function updateFilename($request) {

        $currentFile = Media::where('id', '=', $request['id'])[0];
        $currentFileName = $currentFile['media_filename'];

        $type = $currentFile['media_filetype'];
         
        switch ($type) {

            case 'image/png':
            case 'image/webp':
            case 'image/gif':
            case 'image/jpeg':
            case 'image/svg+xml':

                $fileDestination = "website/assets/img/";
            break;
            case 'video/mp4':
            case 'video/quicktime':

                $fileDestination = "website/assets/video/";
            break;  
            case 'application/pdf':

                $fileDestination = "website/assets/application/";
            break;
            default:

                $fileDestination = '';
            break;
        }

        rename($fileDestination.$currentFileName, $fileDestination.$request['filename']);

        Media::update(['id' => $request['id']], [
                
            'media_filename'    => $request['filename']
        ]);
    }

    private function updateTitleAndDescription($request) {

        Media::update(['id' => $request['id']], [

            'media_title'   => $request['title'],
            'media_description' => $request['description']

        ]);
    }

    public function delete($request) {

        Media::delete('id', $request['id']);
        redirect("/admin/media");
    }
}