<?php

namespace app\models;

use database\DB;

class Post extends Model {

    public function __construct() {

        self::table("pages");
    }

    public function allPostsWithCategories($searchValue) {

        if(!empty($searchValue) && $searchValue !== null) {

            $posts = DB::try()->all('pages')->where('title', 'LIKE', '%'.$searchValue.'%')->or('author', 'LIKE', '%'.$searchValue.'%')->or('date_created_at', 'LIKE', '%'.$searchValue.'%')->or('time_created_at', 'LIKE', '%'.$searchValue.'%')->or('date_updated_at', 'LIKE', '%'.$searchValue.'%')->or('time_updated_at', 'LIKE', '%'.$searchValue.'%')->fetch();
            return $posts;
        }
    }
}