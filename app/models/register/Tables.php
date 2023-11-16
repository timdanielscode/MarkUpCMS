<?php 

/**
 * Tables
 * 
 * Register here both model and table names inside the $table property
 * After applying these names, you can make use of the base model methods
 * 
 * @author Tim Daniëls
 */

namespace app\models\register;

class Tables {

    public $tables = [

        "Post"      =>      "pages",
        "Menu"  =>      "menus",
        "Category"  => "categories",
        "CdnPage" => "cdn_page",
        "PageWidget" => "page_widget",
        "Widget"    => "widgets",
        "CategoryPage" => "category_page",
        "Css"   => "css",
        "CssPage" => "css_page",
        "Js"    => "js",
        "JsPage"    => "js_page",
        "Cdn"   =>  "cdn",
        "CdnPage"   => "cdn_page"
    ];
}