<?php

namespace app\models;

use database\DB;

class Media extends Model {


    public function ifRowExists($id) {

        return DB::try()->select('id')->from('media')->where('id', '=', $id)->first();
    }

    public function allMediaButOrdered() {

        $media = DB::try()->all('media')->order('updated_at')->desc()->fetch();
        return $media;
    }
    
    public function mediaFilesOnSearch($searchValue) {

        $media = DB::try()->all('media')->where('updated_at', 'LIKE', '%'.$searchValue.'%')->or('media_filetype', 'LIKE', '%'.$searchValue.'%')->or('media_filename', 'LIKE', '%'.$searchValue.'%')->or('media_description', 'LIKE', '%'.$searchValue.'%')->or('media_filesize', 'LIKE', '%'.$searchValue.'%')->order('updated_at')->desc()->fetch();
        return $media;
    }
}