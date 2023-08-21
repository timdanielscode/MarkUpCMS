<?php

namespace app\controllers\admin;

use app\controllers\Controller;
use database\DB;
use app\models\WebsiteSlug;
                    
class DashboardController extends Controller {
                
    public function index() {    

        $data['pages'] = DB::try()->select('id')->from('pages')->fetch();
        $data['menus'] = DB::try()->select('id')->from('menus')->fetch();
        $data['widgets'] = DB::try()->select('id')->from('widgets')->fetch();
        $data['categories'] = DB::try()->select('id')->from('categories')->fetch();
        $data['css'] = DB::try()->select('id')->from('css')->fetch();
        $data['js'] = DB::try()->select('id')->from('js')->fetch();
        $data['media'] = DB::try()->select('id')->from('media')->fetch();
        $data['users'] = DB::try()->select('id')->from('users')->fetch();

        $data['contentAppliedPages'] = DB::try()->select('id')->from('pages')->where('has_content', '=', 1)->fetch();
        $data['titleOfLastCreatedPage'] = DB::try()->select('id, title')->from('pages')->where('removed', '!=', '1')->order('created_at')->desc()->first();
        $data['removedPages'] = DB::try()->select('id')->from('pages')->where('removed', '=', 1)->fetch();
        $data['idOfLastUpdatedPage'] = DB::try()->select('id, title')->from('pages')->where('removed', '!=', '1')->order('updated_at')->desc()->first();

        $data['contentAppliedMenus'] = DB::try()->select('id')->from('menus')->where('has_content', '=', 1)->fetch();
        $data['contentAppliedCss'] = DB::try()->select('id')->from('css')->where('has_content', '=', 1)->fetch();
        $data['contentAppliedJs'] = DB::try()->select('id')->from('js')->where('has_content', '=', 1)->fetch();

        $data['positionAppliedMenus'] = DB::try()->select('id')->from('menus')->where('position', '!=', 'unset')->fetch();
        $data['orderingAppliedMenus'] = DB::try()->select('id')->from('menus')->where('ordering', 'IS NOT', NULL)->fetch();
        $data['idOfLastCreatedMenu'] = DB::try()->select('id, title')->from('menus')->where('removed', '!=', '1')->order('created_at')->desc()->first();
        $data['idOfLastUpdatedMenu'] = DB::try()->select('id')->from('menus')->where('removed', '!=', '1')->order('updated_at')->desc()->first();
        $data['removedMenus'] = DB::try()->select('id')->from('menus')->where('removed', '=', 1)->fetch();

        $data['contentAppliedWidgets'] = DB::try()->select('id')->from('widgets')->where('has_content', '=', 1)->fetch();
        $data['removedWidgets'] = DB::try()->select('id')->from('widgets')->where('removed', '=', 1)->fetch();
        $data['idOfLastCreatedWidget'] = DB::try()->select('id')->from('widgets')->where('removed', '!=', 1)->order('created_at')->desc()->first();
        $data['idOfLastUpdatedWidget'] = DB::try()->select('id')->from('widgets')->where('removed', '!=', 1)->order('updated_at')->desc()->first();

        $data['percentageOfNormalUsers'] = $this->getPercentageOfNormalRoles();
        $data['percentageOfAdminUsers'] = $this->getPercentageOfAdminRoles();
        $data['numberOfAdminUsers'] = $this->getNumberOfAdminRoles();
        $data['numberOfNormalUsers'] = $this->getNumberOfNormalRoles();

        $data['numberOfPages'] = $this->getNumberOfPages();
        $data['numberOfAppliedMetaTitle'] = $this->getNumberOfNotAppliedMetaTitle();
        $data['numberOfAppliedMetaDescription'] = $this->getNumberOfNotAppliedMetaDescription();
        $data['numberOfAppliedMetaKeywords'] = $this->getNumberOfNotAppliedMetaKeywords();

        $data['numberOfLinkedCss'] = $this->getNumberOfLinkedCss();
        $data['idOfLastCreatedCss'] = DB::try()->select('id')->from('css')->where('removed', '!=', '1')->order('created_at')->desc()->first();
        $data['idOfLastUpdatedCss'] = DB::try()->select('id')->from('css')->where('removed', '!=', '1')->order('updated_at')->desc()->first();
        $data['removedCss'] = DB::try()->select('id')->from('css')->where('removed', '=', 1)->fetch();

        $data['numberOfIncludedJs'] = $this->getNumberOfIncludedJs();
        $data['idOfLastCreatedJs'] = DB::try()->select('id')->from('js')->where('removed', '!=', '1')->order('created_at')->desc()->first();
        $data['idOfLastUpdatedJs'] = DB::try()->select('id')->from('js')->where('removed', '!=', '1')->order('updated_at')->desc()->first();
        $data['removedJs'] = DB::try()->select('id')->from('js')->where('removed', '=', 1)->fetch();


        $data['numberOfMediaFiletypePng'] = $this->getNumberOfMediaTypePng();
        $data['numberOfMediaFiletypeJpg'] = $this->getNumberOfMediaTypeJpg();
        $data['numberOfMediaFiletypeGif'] = $this->getNumberOfMediaTypeGif();
        $data['numberOfMediaFiletypeWebp'] = $this->getNumberOfMediaTypeWebp();
        $data['numberOfMediaFiletypeSvg'] = $this->getNumberOfMediaTypeSvg();
        $data['numberOfMediaFiletypeMp4'] = $this->getNumberOfMediaTypeMp4();
        $data['numberOfMediaFiletypePdf'] = $this->getNumberOfMediaTypePdf();
        $data['numberOfMediaTotalUploadedSize'] = $this->getNumberOfMediaTotalUploadedSize();
        $data['numberOfServerFreeSpace'] = $this->getNumberOfServerFreeSpace();

        return $this->view("admin/dashboard/index", $data);     
    }

    public function updateLoginSlug($request) {

        $currentWebsiteSlug = DB::try()->all('websiteSlug')->fetch();

        if(!empty($currentWebsiteSlug) && $currentWebsiteSlug !== null) {

            $currentWebsiteSlugId = DB::try()->select('id')->from('websiteSlug')->first();

            WebsiteSlug::update(['id' => $currentWebsiteSlugId[0]], [

                'slug'     => "/" . $request['slug'],
                'updated_at' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME'])
            ]);

        } else {

            WebsiteSlug::insert([

                'slug' => "/" . $request['slug'],
                'created_at' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']),
                'updated_at' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME'])
            ]);
        }

        redirect('/admin/dashboard');
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