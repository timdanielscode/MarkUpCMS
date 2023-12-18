<?php 
/**
 * To register model and table names inside the $table property to make use of the 'base Model' model methods (model name as key and table name as value)
 */

namespace app\models\register;

class Tables {

    public $tables = [

        "Post"          =>      "pages",
        "Menu"          =>      "menus",
        "Category"      =>      "categories",
        "CategorySub"   =>      "category_sub",
        "PageMeta"       =>     "meta_page",
        "PageWidget"    =>      "page_widget",
        "Widget"        =>      "widgets",
        "CategoryPage"  =>      "category_page",
        "Css"           =>      "css",
        "CssPage"       =>      "css_page",
        "Js"            =>      "js",
        "JsPage"        =>      "js_page",
        "Meta"           =>     "metas",
        "Media"         =>      "media",
        "MediaFolder"   =>      "mediaFolders",
        "User"          =>      "users",
        "UserRole"      =>      "user_role",
        "WebsiteSlug"   =>      "websiteSlug",
        "Roles"         =>      "roles"
    ];
}