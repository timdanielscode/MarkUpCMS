<?php

namespace app\controllers\admin;

use app\controllers\Controller;
use app\models\Cdn;
use database\DB;
use extensions\Pagination;
use app\models\CdnPage;
use core\Csrf;
use validation\Rules;

class CdnController extends Controller {

    public function index() {

        $cdns = DB::try()->all('cdn')->fetch();

        $search = get('search');

        if(!empty($search) ) {

            $cdns = $cdns($search);
        }

        $count = count($cdns);

        $cdns = Pagination::get($cdns, 10);
        $numberOfPages = Pagination::getPageNumbers();

        $data['count'] = $count;
        $data['cdns'] = $cdns;
        $data['numberOfPages'] = $numberOfPages;

        return $this->view('admin/cdn/index', $data);
    }

    public function create() {

        $data['rules'] = [];

        return $this->view('admin/cdn/create', $data);
    }

    public function store($request) {

        if(submitted('submit') && Csrf::validate(Csrf::token('get'), post('token') ) === true) {

            $rules = new Rules();

            $unique = DB::try()->select('id')->from('cdn')->where('title', '=', $request['title'])->fetch();

            if($rules->create_cdn($unique)->validated() ) {

                Cdn::insert([

                    'title' => $request['title'],
                    'content' => $request['code'],
                    'created_at' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']),
                    'updated_at' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME'])
                ]);

                redirect('/admin/cdn');
            } else {

                $data['rules'] = $rules->errors;
                return $this->view('admin/cdn/create', $data);
            }
        }
    }

    public function edit($request) {

        $cdn = Cdn::get($request['id']);

        $importedPages = DB::try()->select('id, title')->from('pages')->join('cdn_page')->on('cdn_page.page_id', '=', 'pages.id')->fetch();
        $pages = $this->getPages($importedPages);

        $data['cdn'] = $cdn;
        $data['pages'] = $pages;
        $data['importedPages'] = $importedPages;

        return $this->view('admin/cdn/edit', $data);
    }

    private function getPages($pages) {

        if(!empty($pages) && $pages !== null) {

            $importedIds = [];

            foreach($pages as $page) {
    
                array_push($importedIds, $page['id']);
            }
    
            $importedIds = implode(',', $importedIds);
    
            $otherPages = DB::try()->select('id, title')->from('pages')->whereNotIn('id', $importedIds)->and('pages.removed', '!=', 1)->fetch();
        } else {
            $otherPages = DB::try()->select('id, title')->from('pages')->where('removed', '!=', 1)->fetch();
        }

        return $otherPages;
    }

    public function update($request) {

        $id = $request['id'];

        Cdn::update(['id' => $id], [

            'title'     => $request['title'],
            'content' => $request['content'],
            'updated_at' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME'])
        ]);
        redirect("/admin/cdn/$id/edit");
    }

    public function importPage($request) {

        $cdnId = $request['id'];

        foreach($request['pages'] as $pageId) {

            CdnPage::insert([

                'page_id' => $pageId,
                'cdn_id' => $request['id']
            ]);
        }

        redirect("/admin/cdn/$cdnId/edit");
    }

    public function exportPage($request) {

        $cdnId = $request['id'];

        foreach($request['pages'] as $pageId) {

            CdnPage::delete('page_id', $pageId);
        }

        redirect("/admin/cdn/$cdnId/edit");
    }
}