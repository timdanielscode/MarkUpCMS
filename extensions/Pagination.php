<?php

namespace extensions;

use core\Session;

class Pagination {

    private static $_pagenationAllArrayItems, $_maxNumberOfArrayItems, $_paginatedArrayItems, $_countPages, $_collectionNumberOfPages;

    /** 
     * To get truncated items to not have to scroll down a lot
     * 
     * @return array $request _GET page
     * @param array $paginationArray array to pagainate 
     * @param array $maxNumberArrayItems number of pages to paginate
     */ 
    public static function get($request, $paginationArray, $maxNumberArrayItems) {

        self::$_pagenationAllArrayItems = $paginationArray;
        self::$_maxNumberOfArrayItems = $maxNumberArrayItems;

        $numberPaginationArrayItems = count(self::$_pagenationAllArrayItems);
        self::$_countPages = ceil($numberPaginationArrayItems/self::$_maxNumberOfArrayItems);

        for($i = 1; $i <= self::$_maxNumberOfArrayItems; $i++) {

            if(array_key_exists($i, self::$_pagenationAllArrayItems) ) {
                
                self::$_paginatedArrayItems[] = self::$_pagenationAllArrayItems[$i];
            }
        }

        self::getNumberedArrayItems($request);
        return self::$_paginatedArrayItems;
    }

    /** 
     * To get truncated items but based on get request page value
     * 
     * @param array $request _GET page
     */ 
    public static function getNumberedArrayItems($request) {

        if(!empty($request['page']) ) {

            $pageValue = $request['page'];
            Session::set('page', $pageValue);
        } else {
            $pageValue = 1;
        }

        $maxValue = $pageValue * self::$_maxNumberOfArrayItems;
        $minValue = $maxValue - self::$_maxNumberOfArrayItems;
        
        $paginatedArrayItems = [];
                
        for($i = $minValue; $i < $maxValue;  $i++) {
                    
            if(array_key_exists($i, self::$_pagenationAllArrayItems) ) {

                array_push($paginatedArrayItems, self::$_pagenationAllArrayItems[$i]);
            }
        }
        self::$_paginatedArrayItems = $paginatedArrayItems;
    }

    /** 
     * To get the amount of truncated items 
     * 
     * @return array $paginated number of paginated numbers
     */     
    public static function getPageNumbers() {

        for($i = 1; $i <= self::$_countPages; $i++) {
            
            self::$_collectionNumberOfPages[] = $i;
        }

        return self::$_collectionNumberOfPages;
    }
}