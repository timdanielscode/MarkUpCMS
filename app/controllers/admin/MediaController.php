<?php

namespace app\controllers\admin;

use app\controllers\Controller;
use database\DB;
use app\models\Media;
use parts\Session;
use core\Csrf;
use parts\Pagination;
use validation\Rules;

class MediaController extends Controller {

    public function index() {
        
        $media = new Media();
        $allMedia = DB::try()->all($media->t)->order('date_created_at')->fetch();

        $count = count($allMedia);
        $search = get('search');

        if(!empty($search) ) {
            $allMedia = DB::try()->all($media->t)->where($media->media_title, 'LIKE', '%'.$search.'%')->or($media->media_description, 'LIKE', '%'.$search.'%')->or($media->date_created_at, 'LIKE', '%'.$search.'%')->or($media->time_created_at, 'LIKE', '%'.$search.'%')->or($media->date_updated_at, 'LIKE', '%'.$search.'%')->or($media->time_updated_at, 'LIKE', '%'.$search.'%')->fetch();
            if(empty($allMedia) ) {
                $allMedia = array(["id" => "?","title" => "not found", "author" => "not found", "date_created_at" => "-", "time_created_at" => "", "date_updated_at" => "-", "time_updated_at" => ""]);
            }
        }
        
        $allMedia = Pagination::set($allMedia, 20);
        $numberOfPages = Pagination::getPages();

        $data["allMedia"] = $allMedia;
        $data['numberOfPages'] = $numberOfPages;
        $data['count'] = $count;

        return $this->view('admin/media/index', $data);

    }

    public function fetchData() {

        header('Content-Type: application/json; charset=utf-8');
        $media = new Media();
        $allMedia = DB::try()->all($media->t)->order('date_created_at')->fetch();
        
        foreach($allMedia as $media) {
           
            echo "<tr>";
            if($media["media_title"] !== "not found") {
                echo '<td class="width-30">
                    <a href="/admin/media/$media["id"]/edit" class="font-weight-500">'; echo $media["media_title"]; echo '</a> |
                    <a href="/admin/media/'; echo $media["id"]; echo '/edit" class="font-weight-300">Edit</a> |
                    <a href="/admin/media/'; echo $media["id"]; echo '/preview" class="font-weight-300">Preview</a> |
                    <a href="/admin/media/'; echo $media["id"]; echo '/delete" class="font-weight-300 color-red">Remove</a>
                    
                </td>';
                 } else {
                echo '<td>
                        <span class="font-weight-500">'; echo $media["media_title"]; echo '</span>
                    </td>';
                }


                echo '<td class="width-10">';
                $type = $media['media_filetype']; 
                if($type == 'image/png' || $type  == 'image/webp' || $type  == 'image/gif' || $type  == 'image/jpeg' || $type  == 'image/svg+xml') { 
                    echo '<img src="/website/assets/img/'. $media['media_filename'] .'" id="imageSmall">'; 
                } else if ($type == 'application/pdf') {     
                    echo '<iframe src="/website/assets/application/'. $media['media_filename'] .'" id="pdfSmall"></iframe>';
                } else if ($type == 'video/mp4' || $type == 'video/quicktime') {
                    echo '<video src="/website/assets/video/'. $media['media_filename'] .'" id="imageSmall"></video>';
                }
                echo '</td>';
                echo "<td>";
                echo '<span class="font-weight-400">';

                    $type = $media['media_filetype']; 
                    if($type == 'image/png' || $type  == 'image/webp' || $type  == 'image/gif' || $type  == 'image/jpeg' || $type  == 'image/svg+xml') { 
                        echo '/website/assets/img/'; 
                    } else if($type == 'video/mp4' || $type == 'video/quicktime') {
                        echo '/website/assets/video/';
                    } else if($type == 'application/pdf') {
                        echo '/website/assets/application/';
                    }
                echo '</span>';

            
            echo "<form>";
            echo '<input class="mediaFilename" name="filename" id="filename-'.$media['id'].'" data-target="filename" type="text"'; echo ' value="'; echo $media["media_filename"]; echo '"/>';
            echo '<a data-role="update" id="update" data-id="'; echo $media['id']; echo '">update</a>';
            echo '<div id="successMessage-'.$media['id'].'"></div>';
            echo "</form>";
            echo "</td>";
            

            echo '<td class="width-10">
                <span class="font-weight-500">'; echo $media['media_filetype']; echo '</span>
            </td>';

            echo '<td class="width-10">
            <span class="font-weight-400">';
                $filesize = $media['media_filesize'] / 1000000;
                $filesize = number_format((float)$filesize, 2, '.', '');
                echo $filesize;
            echo '</span>
            <span class="font-weight-500"> 
                mb
            </span>
        </td>';


        echo '<td class="width-15">
        <span class="padding-b-2">Created:</span> <span class="font-weight-300">'; echo $media["date_created_at"] . " " . $media["time_created_at"]; echo '</span><br>
        <span>Updated:</span> <span class="font-weight-300">'; echo $media["date_updated_at"] . " " . $media["time_updated_at"]; echo '</span>
    </td>';


            echo "</tr>";
        }
        
    }

    public function create() {

        $data["rules"] = [];

        return $this->view('admin/media/create', $data);
    }

    public function store() {

        if(submitted('submit')) {

            if(Csrf::validate(Csrf::token('get'), post('token') ) === true) {

                $rules = new Rules();
                $media = new Media();
                
                $filename = $_FILES['file']['name'];
                $tmp = $_FILES['file']['tmp_name'];
                $size = $_FILES['file']['size'];
                $type = $_FILES['file']['type'];

                if($rules->media()->validated()) {
                    
                    if($type == 'image/png' || $type  == 'image/webp' || $type  == 'image/gif' || $type  == 'image/jpeg' || $type  == 'image/svg+xml') {
                        $fileDestination = "website/assets/img/".$filename;
                    } else if($type == 'video/mp4' || $type == 'video/quicktime') {
                        $fileDestination = "website/assets/video/".$filename;
                    } else if($type == 'application/pdf') {
                        $fileDestination = "website/assets/application/".$filename;
                    } else {
                        $fileDestination = '';
                    }
                    
                    move_uploaded_file($tmp, $fileDestination);

                    DB::try()->insert($media->t, [

                        $media->media_title => post('media_title'),
                        $media->media_description => post('media_description'),
                        $media->media_filename => $filename,
                        $media->media_filetype => $type,
                        $media->media_filesize => $size,
                        $media->date_created_at => date("d/m/Y"),
                        $media->time_created_at => date("H:i"),
                        $media->date_updated_at => date("d/m/Y"),
                        $media->time_updated_at => date("H:i")
                    ]);

                    Session::set('create', 'You have successfully created a new post!');            
                    redirect('/admin/media');

                } else {
                    $data['rules'] = $rules->errors;
                    return $this->view('admin/media/create', $data);
                }
            } else {
                Session::set('csrf', 'Cross site request forgery!');
                redirect('/admin/media/create');
            }
        }
    }


    public function edit($request) {
        
        $media = new Media();
        $media = DB::try()->all($media->t)->where($media->id, '=', $request['id'])->first();

        $data['media'] = $media;
        $data['rules'] = [];
        return $this->view('/admin/media/edit', $data);
    }

    public function updateFilename($request) { 

        $data['id'] = $_POST['id'];
        $data['filename'] = $_POST['filename'];

        echo json_encode($data);

        $media = new Media();

        $currentFile = DB::try()->select($media->media_filename, $media->media_filetype)->from($media->t)->where($media->id, '=', $data['id'])->first();
        $currentFileName = $currentFile[0];
        $type = $currentFile[1];
     
        if($type == 'image/png' || $type  == 'image/webp' || $type  == 'image/gif' || $type  == 'image/jpeg' || $type  == 'image/svg+xml') {
            $fileDestination = "website/assets/img/";
        } else if($type == 'video/mp4' || $type == 'video/quicktime') {
            $fileDestination = "website/assets/video/";
        } else if($type == 'application/pdf') {
            $fileDestination = "website/assets/application/";
        } else {
            $fileDestination = '';
        }

        rename($fileDestination.$currentFileName, $fileDestination.$data['filename']);

        DB::try()->update($media->t)->set([
            $media->media_filename => $data['filename']
        ])->where($media->id, '=', $data['id'])->run();  
    
    }
    
}