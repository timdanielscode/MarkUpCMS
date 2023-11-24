<?php

namespace app\controllers\admin;

use app\controllers\Controller;
use app\models\Post;
use app\models\Menu;
use app\models\Widget;
use app\models\Category;
use app\models\Css;
use app\models\Js;
use app\models\Media;
use app\models\User;
                    
class DashboardController extends Controller {

    private $_data; 
    private $_pageIds = [], $_metaTitles = [], $_metaDescriptions = [], $_metaKeyWords = [], $_cssIds = [], $_jsIds = [];
    private $_pngIds = [], $_jpegIds = [], $_gifIds = [], $_webpIds = [], $_svgIds = [], $_videoIds = [], $_applicationIds = [];
                
    public function index() {    

        $this->_data['pages'] = Post::getAll(['id']);
        $this->_data['contentAppliedPages'] = Post::whereColumns(['id'], ['has_content' => 1]);
        $this->_data['removedPages'] = Post::whereColumns(['id'], ['removed' => 1]);
        $this->_data['numberOfPages'] = $this->getNumberOfPages();
        $this->_data['numberOfAppliedMetaTitle'] = $this->getNumberOfNotAppliedMetaTitle();
        $this->_data['numberOfAppliedMetaDescription'] = $this->getNumberOfNotAppliedMetaDescription();
        $this->_data['numberOfAppliedMetaKeywords'] = $this->getNumberOfNotAppliedMetaKeywords();
        $this->_data['categories'] = Category::getAll(['id']);
        
        $this->_data['menus'] = Menu::getAll(['id']);
        $this->_data['contentAppliedMenus'] = Menu::whereColumns(['id'], ['has_content' => 1]);
        $this->_data['removedMenus'] = Menu::whereColumns(['id'], ['removed' => 1]);
        $this->_data['positionAppliedMenus'] = Menu::getPositionNotUnset();
        $this->_data['orderingAppliedMenus'] = Menu::getOrderingIsNotNull();

        $this->_data['widgets'] = Widget::getAll(['id']);
        $this->_data['contentAppliedWidgets'] = Widget::whereColumns(['id'],  ['has_content' => 1]);
        $this->_data['removedWidgets'] = Widget::whereColumns(['id'], ['removed' => 1]);

        $this->_data['css'] = Css::getAll(['id']);
        $this->_data['removedCss'] = Css::whereColumns(['id'], ['removed' => 1]);
        $this->_data['contentAppliedCss'] = Css::whereColumns(['id'], ['has_content' => 1]);
        $this->_data['numberOfLinkedCss'] = $this->getNumberOfLinkedCss();

        $this->_data['js'] = Js::getAll(['id']);
        $this->_data['removedJs'] = Js::whereColumns(['id'], ['removed' => 1]);
        $this->_data['contentAppliedJs'] = Js::whereColumns(['id'], ['has_content' => 1]);
        $this->_data['numberOfIncludedJs'] = $this->getNumberOfIncludedJs();

        $this->_data['users'] = User::getAll(['id']);
        $this->_data['percentageOfNormalUsers'] = count(User::getIdNormalRoles()) / count(User::getAll(['id'])) * 100;
        $this->_data['percentageOfAdminUsers'] = count(User::getIdAdminRoles()) / count(User::getAll(['id'])) * 100;
        $this->_data['numberOfAdminUsers'] = count(User::getIdAdminRoles());
        $this->_data['numberOfNormalUsers'] = count(User::getIdNormalRoles());

        $this->_data['media'] = Media::getAll(['id']);
        $this->_data['numberOfMediaFiletypePng'] = $this->getNumberOfMediaTypePng();
        $this->_data['numberOfMediaFiletypeJpg'] = $this->getNumberOfMediaTypeJpg();
        $this->_data['numberOfMediaFiletypeGif'] = $this->getNumberOfMediaTypeGif();
        $this->_data['numberOfMediaFiletypeWebp'] = $this->getNumberOfMediaTypeWebp();
        $this->_data['numberOfMediaFiletypeSvg'] = $this->getNumberOfMediaTypeSvg();
        $this->_data['numberOfMediaFiletypeMp4'] = $this->getNumberOfMediaTypeMp4();
        $this->_data['numberOfMediaFiletypePdf'] = $this->getNumberOfMediaTypePdf();

        return $this->view("admin/dashboard/index")->data($this->_data);     
    }

    private function getNumberOfPages() {

        foreach(User::getAll(['id']) as $id) {

            array_push($this->_pageIds, $id);
        }

        return count($this->_pageIds);
    }

    private function getNumberOfNotAppliedMetaTitle() {

        foreach(Post::getMetaTitleNotNullEmpty() as $metaTitle) {

            array_push($this->_metaTitles, $metaTitle);
        }

        return count($this->_metaTitles);
    }

    private function getNumberOfNotAppliedMetaDescription() {
    
        foreach(Post::getMetaDescriptionNotNullEmpty() as $metaDescription) {

            array_push($this->_metaDescriptions, $metaDescription);
        }

        return count($this->_metaDescriptions);
    }

    private function getNumberOfNotAppliedMetaKeywords() {

        foreach(Post::getMetaKeyWordsNotNullEmpty() as $metaKeyWord) {

            array_push($this->_metaKeyWords, $metaKeyWord);
        }

        return count($this->_metaKeyWords);
    }

    private function getNumberOfLinkedCss() {

        foreach(Css::getIdLinkedNotNull() as $id) {

            array_push($this->_cssIds, $id);
        }

        return count($this->_cssIds);
    }

    private function getNumberOfIncludedJs() {

        foreach(Js::getIdIncludedNotNull() as $id) {

            array_push($this->_jsIds, $id);
        }

        return count($this->_jsIds);
    }

    private function getNumberOfMediaTypePng() {

        foreach(Media::whereColumns(['id'], ['media_filetype' => 'image/png']) as $id) {

            array_push($this->_pngIds, $id);
        }

        return count($this->_pngIds);
    }

    private function getNumberOfMediaTypeJpg() {

        foreach(Media::whereColumns(['id'], ['media_filetype' => 'image/jpeg']) as $id) {

            array_push($this->_jpegIds, $id);
        }

        return count($this->_jpegIds);
    }

    private function getNumberOfMediaTypeGif() {

        foreach(Media::whereColumns(['id'], ['media_filetype' => 'image/gif']) as $id) {

            array_push($this->_gifIds, $id);
        }

        return count($this->_gifIds);
    }

    private function getNumberOfMediaTypeWebp() {

        foreach(Media::whereColumns(['id'], ['media_filetype' => 'image/webp']) as $id) {

            array_push($this->_webpIds, $id);
        }

        return count($this->_webpIds);
    }

    private function getNumberOfMediaTypeSvg() {

        foreach(Media::whereColumns(['id'], ['media_filetype' => 'image/svg+xml']) as $id) {

            array_push($this->_svgIds, $id);
        }

        return count($this->_svgIds);
    }

    private function getNumberOfMediaTypeMp4() {

        foreach(Media::whereColumns(['id'], ['media_filetype' => 'video/mp4']) as $id) {

            array_push($this->_videoIds, $id);
        }

        return count($this->_videoIds);
    }

    private function getNumberOfMediaTypePdf() {

        foreach(Media::whereColumns(['id'], ['media_filetype' => 'application/pdf']) as $id) {

            array_push($this->_applicationIds, $id);
        }

        return count($this->_applicationIds);
    }
}  