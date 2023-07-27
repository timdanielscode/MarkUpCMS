<?php

namespace app\controllers\admin;

use app\controllers\Controller;
use database\DB;
                    
class DashboardController extends Controller {
                
    public function index() {    

        $data['pages'] = DB::try()->select('id')->from('pages')->fetch();
        $data['menus'] = DB::try()->select('id')->from('menus')->fetch();
        $data['categories'] = DB::try()->select('id')->from('categories')->fetch();
        $data['css'] = DB::try()->select('id')->from('css')->fetch();
        $data['js'] = DB::try()->select('id')->from('js')->fetch();
        $data['media'] = DB::try()->select('id')->from('media')->fetch();
        $data['users'] = DB::try()->select('id')->from('users')->fetch();

        $data['contentAppliedPages'] = DB::try()->select('id')->from('pages')->where('has_content', '=', 1)->fetch();
        $data['contentAppliedMenus'] = DB::try()->select('id')->from('menus')->where('has_content', '=', 1)->fetch();
        $data['contentAppliedCss'] = DB::try()->select('id')->from('css')->where('has_content', '=', 1)->fetch();
        $data['contentAppliedJs'] = DB::try()->select('id')->from('js')->where('has_content', '=', 1)->fetch();


        $data['percentageOfNormalUsers'] = $this->getPercentageOfNormalRoles();
        $data['percentageOfAdminUsers'] = $this->getPercentageOfAdminRoles();
        $data['numberOfAdminUsers'] = $this->getNumberOfAdminRoles();
        $data['numberOfNormalUsers'] = $this->getNumberOfNormalRoles();


        $data['chartNumberOfPages'] = $this->getNumberOfPages();
        $data['chartNumberOfAppliedMetaTitle'] = $this->getNumberOfNotAppliedMetaTitle();
        $data['chartNumberOfAppliedMetaDescription'] = $this->getNumberOfNotAppliedMetaDescription();
        $data['chartNumberOfAppliedMetaKeywords'] = $this->getNumberOfNotAppliedMetaKeywords();

        $data['chartNumberOfUnusedCss'] = $this->getNumberOfUnusedCss();
        $data['chartNumberOfUnusedJs'] = $this->getNumberOfUnusedJs();

        $data['chartNumberOfMediaFiletypePng'] = $this->getNumberOfMediaTypePng();
        $data['chartNumberOfMediaFiletypeJpg'] = $this->getNumberOfMediaTypeJpg();
        $data['chartNumberOfMediaFiletypeGif'] = $this->getNumberOfMediaTypeGif();
        $data['chartNumberOfMediaFiletypeWebp'] = $this->getNumberOfMediaTypeWebp();
        $data['chartNumberOfMediaFiletypeSvg'] = $this->getNumberOfMediaTypeSvg();
        $data['chartNumberOfMediaFiletypeMp4'] = $this->getNumberOfMediaTypeMp4();
        $data['chartNumberOfMediaFiletypePdf'] = $this->getNumberOfMediaTypePdf();

        return $this->view("admin/dashboard/index", $data);     
    }

    private function getPercentageOfAdminRoles() {

        $numberUsers = DB::try()->select('id')->from('users')->fetch();
        $numberAdminRoles = DB::try()->select('users.id')->from('users')->join('user_role')->on('user_role.user_id', '=', 'users.id')->join('roles')->on('user_role.role_id', '=', 'roles.id')->where('roles.name', '=', 'admin')->fetch();

        $countUsers = count($numberUsers);
        $countAdminRoles = count($numberAdminRoles);
        
        return $countAdminRoles / $countUsers * 100;
    }

    private function getNumberOfNormalRoles() {

        $numberNormalRoles = DB::try()->select('users.id')->from('users')->join('user_role')->on('user_role.user_id', '=', 'users.id')->join('roles')->on('user_role.role_id', '=', 'roles.id')->where('roles.name', '=', 'normal')->fetch();

        return count($numberNormalRoles);
    }

    private function getPercentageOfNormalRoles() {

        $numberUsers = DB::try()->select('id')->from('users')->fetch();
        $numberNormalRoles = DB::try()->select('users.id')->from('users')->join('user_role')->on('user_role.user_id', '=', 'users.id')->join('roles')->on('user_role.role_id', '=', 'roles.id')->where('roles.name', '=', 'normal')->fetch();

        $countUsers = count($numberUsers);
        $countNormalRoles = count($numberNormalRoles);
        
        return $countNormalRoles / $countUsers * 100;
    }

    private function getNumberOfAdminRoles() {

        $numberAdminRoles = DB::try()->select('users.id')->from('users')->join('user_role')->on('user_role.user_id', '=', 'users.id')->join('roles')->on('user_role.role_id', '=', 'roles.id')->where('roles.name', '=', 'admin')->fetch();

        return count($numberAdminRoles);
    }

    private function getNumberOfPages() {

        $numbers = DB::try()->select('id')->from('pages')->fetch();
        $data = [];

        foreach($numbers as $number) {

            array_push($data, $number);
        }
        return count($data);
    }

    private function getNumberOfNotAppliedMetaTitle() {

        $numbers = DB::try()->select('metaTitle')->from('pages')->where('metaTitle', 'IS NOT', NULL)->and('metaTitle', '!=', '')->fetch();
        $data = [];

        foreach($numbers as $number) {

            array_push($data, $number);
        }
        return count($data);
    }

    
    private function getNumberOfNotAppliedMetaDescription() {

        $numbers = DB::try()->select('metaDescription')->from('pages')->where('metaDescription', 'IS NOT', NULL)->and('metaDescription', '!=', '')->fetch();
        $data = [];

        foreach($numbers as $number) {

            array_push($data, $number);
        }
        return count($data);
    }

    private function getNumberOfNotAppliedMetaKeywords() {

        $numbers = DB::try()->select('metaKeywords')->from('pages')->where('metaKeywords', 'IS NOT', NULL)->and('metaKeywords', '!=', '')->fetch();
        $data = [];

        foreach($numbers as $number) {

            array_push($data, $number);
        }
        return count($data);
    }

    private function getNumberOfUnusedCss() {

        $numbers = DB::try()->select('id')->from('css')->joinLeft('css_page')->on("css_page.css_id", '=', 'css.id')->where('css_page.css_id', 'IS', NULL)->fetch();
        $data = [];

        foreach($numbers as $number) {

            array_push($data, $number);
        }
        return count($data);
    }

    private function getNumberOfUnusedJs() {

        $numbers = DB::try()->select('id')->from('js')->joinLeft('js_page')->on("js_page.js_id", '=', 'js.id')->where('js_page.js_id', 'IS', NULL)->fetch();
        $data = [];

        foreach($numbers as $number) {

            array_push($data, $number);
        }
        return count($data);
    }

    private function getNumberOfMediaTypePng() {

        $numbers = DB::try()->select('id')->from('media')->where('media_filetype', '=', 'image/png')->fetch();
        $data = [];

        foreach($numbers as $number) {

            array_push($data, $number);
        }
        return count($data);
    }

    private function getNumberOfMediaTypeJpg() {

        $numbers = DB::try()->select('id')->from('media')->where('media_filetype', '=', 'image/jpeg')->fetch();
        $data = [];

        foreach($numbers as $number) {

            array_push($data, $number);
        }
        return count($data);
    }

    private function getNumberOfMediaTypeGif() {

        $numbers = DB::try()->select('id')->from('media')->where('media_filetype', '=', 'image/gif')->fetch();
        $data = [];

        foreach($numbers as $number) {

            array_push($data, $number);
        }
        return count($data);
    }

    private function getNumberOfMediaTypeWebp() {

        $numbers = DB::try()->select('id')->from('media')->where('media_filetype', '=', 'image/webp')->fetch();
        $data = [];

        foreach($numbers as $number) {

            array_push($data, $number);
        }
        return count($data);
    }

    private function getNumberOfMediaTypeSvg() {

        $numbers = DB::try()->select('id')->from('media')->where('media_filetype', '=', 'image/svg+xml')->fetch();
        $data = [];

        foreach($numbers as $number) {

            array_push($data, $number);
        }
        return count($data);
    }

    private function getNumberOfMediaTypeMp4() {

        $numbers = DB::try()->select('id')->from('media')->where('media_filetype', '=', 'video/mp4')->fetch();
        $data = [];

        foreach($numbers as $number) {

            array_push($data, $number);
        }
        return count($data);
    }

    private function getNumberOfMediaTypePdf() {

        $numbers = DB::try()->select('id')->from('media')->where('media_filetype', '=', 'application/pdf')->fetch();
        $data = [];

        foreach($numbers as $number) {

            array_push($data, $number);
        }
        return count($data);
    }
}  