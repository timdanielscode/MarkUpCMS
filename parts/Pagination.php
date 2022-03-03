<?php

namespace parts;

use parts\Session;
use core\Request;

class Pagination {

    private static $_page, $_countPages, $_collPages;

    /** 
     * @param array $arr
     * @param array $max number of pages to paginate
     * @return array $_page paginated data
     */ 
    public static function set($arr, $max) {

        $allNum = count($arr);
        self::$_countPages = ceil($allNum/$max);

        if($max < $allNum) {
            for($i = 1; $i <= $max; $i++) {
                self::$_page[] = $arr[$i];
            }
        }
        
        if(submitted('page')) {
            for($i = 1; $i <= self::$_countPages; $i++) {
                if(get('page') == $i) {
                    Session::set('page', $i);

                    $from = $i * $max - $max;
                    $to = $from + $max;
                    
                    self::$_page = array_slice($arr, $from, $to - $from);
                
                    if($from > $allNum) {
                        $number = $i * $max - $allNum;
                    }
                } 
            }
        }
 
        if(get('back')) {

           if(Session::exists('page')) {

                $req = new Request();
                $uri = strtok($req->getUri(), '?');
                $oneBack = Session::get('page') - 1;
                $back = $uri . "?page=". $oneBack;
                $first = $uri . "?page=1";
                
                if(Session::get('page') !== 1) {
                    redirect("$back");
                } else {
                    redirect("$first");
                }
            }
        } else if(get('next')) {
            
            if(Session::exists('page')) {
               
                $req = new Request();
                $uri = strtok($req->getUri(), '?');
                $oneForward = Session::get('page') + 1;
                $next = $uri . "?page=". $oneForward;
                $last = $uri . "?page=" . self::$_countPages;
                
                if(Session::get('page') == self::$_countPages) {
                    redirect("$last");
                } else {
                    redirect("$next");
                }
            }
        }

        return self::$_page;
    }

    /** 
     * @return array $paginated paginated numbers
     */     
    public static function getPages() {

        for($i = 1; $i <= self::$_countPages; $i++) {
            self::$_collPages[] = $i;
        }
        return self::$_collPages;
    }
}