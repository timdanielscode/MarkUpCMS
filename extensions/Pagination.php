<?php

namespace extensions;

use core\Session;

class Pagination {

    private static $_pagenationAllArrayItems, $_maxNumberOfArrayItems, $_paginatedArrayItems, $_countPages, $_collectionNumberOfPages;

    /** 
     * Determine number of array items per pagenated page
     * 
     * @param array $paginationArray array to pagainate 
     * @param array $maxNumberArrayItems number of pages to paginate
     * @return array $_page paginated data
     */ 
    public static function get($paginationArray, $maxNumberArrayItems) {

        self::$_pagenationAllArrayItems = $paginationArray;
        self::$_maxNumberOfArrayItems = $maxNumberArrayItems;

        $numberPaginationArrayItems = count(self::$_pagenationAllArrayItems);
        self::$_countPages = ceil($numberPaginationArrayItems/self::$_maxNumberOfArrayItems);

        for($i = 1; $i <= self::$_maxNumberOfArrayItems; $i++) {

            if(array_key_exists($i, self::$_pagenationAllArrayItems) ) {
                
                self::$_paginatedArrayItems[] = self::$_pagenationAllArrayItems[$i];
            }
        }

        self::getNumberedArrayItems();
        return self::$_paginatedArrayItems;
    }

    /** 
     * Determine array items based on request uri get method value of page
     * 
     * @return void
     */ 
    public static function getNumberedArrayItems() {

        if(get('page') !== null) {

            $pageValue = get('page');
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
     * @return array $paginated number of paginated numbers
     */     
    public static function getPageNumbers() {

        for($i = 1; $i <= self::$_countPages; $i++) {
            
            self::$_collectionNumberOfPages[] = $i;
        }

        return self::$_collectionNumberOfPages;
    }
}