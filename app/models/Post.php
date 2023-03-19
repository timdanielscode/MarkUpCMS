<?php

namespace app\models;

class Post extends Model {

    public function __construct() {

        self::table("pages");
    }

    public $t = "pages",

        $id = 'id', 
        $title = 'title', 
        $slug = 'slug',
        $body = 'body', 
        $author = 'author', 
        $metaTitle = 'metaTitle',
        $metaDescription = 'metaDescription',
        $date_created_at = 'date_created_at', 
        $time_created_at = 'time_created_at', 
        $date_updated_at = 'date_updated_at', 
        $time_updated_at = 'time_updated_at';
}