<?php

namespace app\controllers\admin;

use app\controllers\Controller;
use database\DB;
use app\models\WebsiteSlug;
use validation\Rules;
                    
class DashboardController extends Controller {

    private $_data;
                
    public function index() {    

        $this->_data['pages'] = DB::try()->select('id')->from('pages')->fetch();
        $this->_data['menus'] = DB::try()->select('id')->from('menus')->fetch();
        $this->_data['widgets'] = DB::try()->select('id')->from('widgets')->fetch();
        $this->_data['categories'] = DB::try()->select('id')->from('categories')->fetch();
        $this->_data['css'] = DB::try()->select('id')->from('css')->fetch();
        $this->_data['js'] = DB::try()->select('id')->from('js')->fetch();
        $this->_data['media'] = DB::try()->select('id')->from('media')->fetch();
        $this->_data['users'] = DB::try()->select('id')->from('users')->fetch();

        $this->_data['contentAppliedPages'] = DB::try()->select('id')->from('pages')->where('has_content', '=', 1)->fetch();
        $this->_data['removedPages'] = DB::try()->select('id')->from('pages')->where('removed', '=', 1)->fetch();

        $this->_data['contentAppliedMenus'] = DB::try()->select('id')->from('menus')->where('has_content', '=', 1)->fetch();
        $this->_data['contentAppliedCss'] = DB::try()->select('id')->from('css')->where('has_content', '=', 1)->fetch();
        $this->_data['contentAppliedJs'] = DB::try()->select('id')->from('js')->where('has_content', '=', 1)->fetch();

        $this->_data['positionAppliedMenus'] = DB::try()->select('id')->from('menus')->where('position', '!=', 'unset')->fetch();
        $this->_data['orderingAppliedMenus'] = DB::try()->select('id')->from('menus')->where('ordering', 'IS NOT', NULL)->fetch();
        $this->_data['removedMenus'] = DB::try()->select('id')->from('menus')->where('removed', '=', 1)->fetch();

        $this->_data['contentAppliedWidgets'] = DB::try()->select('id')->from('widgets')->where('has_content', '=', 1)->fetch();
        $this->_data['removedWidgets'] = DB::try()->select('id')->from('widgets')->where('removed', '=', 1)->fetch();

        $this->_data['percentageOfNormalUsers'] = $this->getPercentageOfNormalRoles();
        $this->_data['percentageOfAdminUsers'] = $this->getPercentageOfAdminRoles();
        $this->_data['numberOfAdminUsers'] = $this->getNumberOfAdminRoles();
        $this->_data['numberOfNormalUsers'] = $this->getNumberOfNormalRoles();

        $this->_data['numberOfPages'] = $this->getNumberOfPages();
        $this->_data['numberOfAppliedMetaTitle'] = $this->getNumberOfNotAppliedMetaTitle();
        $this->_data['numberOfAppliedMetaDescription'] = $this->getNumberOfNotAppliedMetaDescription();
        $this->_data['numberOfAppliedMetaKeywords'] = $this->getNumberOfNotAppliedMetaKeywords();

        $this->_data['numberOfLinkedCss'] = $this->getNumberOfLinkedCss();
        $this->_data['removedCss'] = DB::try()->select('id')->from('css')->where('removed', '=', 1)->fetch();

        $this->_data['numberOfIncludedJs'] = $this->getNumberOfIncludedJs();
        $this->_data['removedJs'] = DB::try()->select('id')->from('js')->where('removed', '=', 1)->fetch();

        $this->_data['numberOfMediaFiletypePng'] = $this->getNumberOfMediaTypePng();
        $this->_data['numberOfMediaFiletypeJpg'] = $this->getNumberOfMediaTypeJpg();
        $this->_data['numberOfMediaFiletypeGif'] = $this->getNumberOfMediaTypeGif();
        $this->_data['numberOfMediaFiletypeWebp'] = $this->getNumberOfMediaTypeWebp();
        $this->_data['numberOfMediaFiletypeSvg'] = $this->getNumberOfMediaTypeSvg();
        $this->_data['numberOfMediaFiletypeMp4'] = $this->getNumberOfMediaTypeMp4();
        $this->_data['numberOfMediaFiletypePdf'] = $this->getNumberOfMediaTypePdf();
        $this->_data['numberOfMediaTotalUploadedSize'] = $this->getNumberOfMediaTotalUploadedSize();
        $this->_data['numberOfServerFreeSpace'] = $this->getNumberOfServerFreeSpace();

        return $this->view("admin/dashboard/index")->data($this->_data);     
    }

    private function getNumberOfServerFreeSpace() {

        $freespace = disk_free_space("/") / 1000000000;
        return number_format((float)$freespace, 2, '.', '');
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

    private function getNumberOfLinkedCss() {

        $numbers = DB::try()->select('id')->from('css')->joinLeft('css_page')->on("css_page.css_id", '=', 'css.id')->where('css_page.css_id', 'IS NOT', NULL)->fetch();
        $data = [];

        foreach($numbers as $number) {

            array_push($data, $number);
        }
        return count($data);
    }

    private function getNumberOfIncludedJs() {

        $numbers = DB::try()->select('id')->from('js')->joinLeft('js_page')->on("js_page.js_id", '=', 'js.id')->where('js_page.js_id', 'IS NOT', NULL)->fetch();
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

    private function getNumberOfMediaTotalUploadedSize() {

        $numbers = DB::try()->select('media_filesize')->from('media')->fetch();
        $amount = 0;

        foreach($numbers as $number) {

            $amount = $amount + $number['media_filesize'];
        }

        $mbs = $amount / 1000000;
        return number_format((float)$mbs, 2, '.', '');
    }
}  