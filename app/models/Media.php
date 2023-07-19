<?php

namespace app\models;

use database\DB;

class Media extends Model {

    public function __construct() {

        self::table('media');
    }

    public function allMediaButOrdered() {

        $media = DB::try()->all('media')->order('date_created_at')->fetch();
        return $media;
    }
    
    public function mediaFilesOnSearch($searchValue) {

        $media = DB::try()->all('media')->where('media_title', 'LIKE', '%'.$searchValue.'%')->or('media_filename', 'LIKE', '%'.$searchValue.'%')->or('date_created_at', 'LIKE', '%'.$searchValue.'%')->or('time_created_at', 'LIKE', '%'.$searchValue.'%')->or('date_updated_at', 'LIKE', '%'.$searchValue.'%')->or('time_updated_at', 'LIKE', '%'.$searchValue.'%')->fetch();
        return $media;
    }
}