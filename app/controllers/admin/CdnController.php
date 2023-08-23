<?php

namespace app\controllers\admin;

use app\controllers\Controller;
use app\models\Cdn;
use database\DB;
use extensions\Pagination;
use app\models\CdnPage;
use core\Csrf;
use validation\Rules;
use core\Session;

class CdnController extends Controller {

    public function index() {

        $cdn = new Cdn();
        $cdns = $cdn->orderedCdns();

        $search = get('search');

        if(!empty($search) ) {

            $cdns = $cdn->orderedCdns($search);
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

            if(!empty($request['content']) && $request['content'] !== null) { $hasContent = 1; } else { $hasContent = 0; }

            if($rules->create_cdn($unique)->validated() ) {

                Cdn::insert([

                    'title' => $request['title'],
                    'content' => $request['content'],
                    'has_content' => $hasContent,
                    'removed'   => 0,
                    'author' => Session::get('username'),
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

    public function read($request) {

        $cdn = Cdn::get($request['id']);

        $data['cdn'] = $cdn;

        return $this->view('admin/cdn/read', $data);
    }

    public function edit($request) {

        $cdn = Cdn::get($request['id']);

        $importedPages = DB::try()->select('id, title')->from('pages')->join('cdn_page')->on('cdn_page.page_id', '=', 'pages.id')->fetch();
        $pages = $this->getPages($importedPages);

        $data['cdn'] = $cdn;
        $data['pages'] = $pages;
        $data['importedPages'] = $importedPages;
        $data['rules'] = [];

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

        if(submitted('submit') && Csrf::validate(Csrf::token('get'), post('token') ) === true) {

            $id = $request['id'];

            $unique = DB::try()->select('id')->from('cdn')->where('title', '=', $request['title'])->and('id', '!=', $request['id'])->fetch();

            $rules = new Rules();

            if(!empty($request['content']) && $request['content'] !== null) { $hasContent = 1; } else { $hasContent = 0; }

            if($rules->edit_cdn($unique)->validated() ) {

                Cdn::update(['id' => $id], [

                    'title'     => $request['title'],
                    'content' => $request['content'],
                    'has_content'   => $hasContent,
                    'updated_at' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME'])
                ]);

                redirect("/admin/cdn/$id/edit");

            } else {

                $data['cdn'] = Cdn::get($request['id']);
                $data['rules'] = $rules->errors;
                return $this->view('admin/cdn/edit', $data);
            }
        }
    }

    public function importPage($request) {

        if(submitted("submit") === true && Csrf::validate(Csrf::token('get'), post('token')) === true ) {

            $cdnId = $request['id'];

            foreach($request['pages'] as $pageId) {

                CdnPage::insert([

                    'page_id' => $pageId,
                    'cdn_id' => $request['id']
                ]);
            }

            redirect("/admin/cdn/$cdnId/edit");
        }
    }

    public function exportPage($request) {

        if(submitted("submit") === true && Csrf::validate(Csrf::token('get'), post('token')) === true ) {

            $cdnId = $request['id'];

            foreach($request['pages'] as $pageId) {

                CdnPage::delete('page_id', $pageId);
            }

            redirect("/admin/cdn/$cdnId/edit");
        }
    }

    public function recover($request) {

        if(!empty($request['recoverIds']) && $request['recoverIds'] !== null) {

            $recoverIds = explode(',', $request['recoverIds']);
            
            foreach($recoverIds as $request['id'] ) {

                $cdn = DB::try()->select('removed')->from('cdn')->where('id', '=', $request['id'])->first();

                Cdn::update(['id' => $request['id']], [

                    'removed'  => 0
                ]);
            }
        }

        redirect("/admin/cdn");
    }

    public function delete($request) {

        $deleteIds = explode(',', $request['deleteIds']);

        if(!empty($deleteIds) && !empty($deleteIds[0])) {

            foreach($deleteIds as $request['id']) {

                $cdn = DB::try()->select('removed')->from('cdn')->where('id', '=', $request['id'])->first();

                if($cdn['removed'] !== 1) {

                    Cdn::update(['id' => $request['id']], [

                        'removed'  => 1
                    ]);

                } else if($cdn['removed'] === 1) {

                    Cdn::delete("id", $request['id']);
                }
            }
        }

        redirect("/admin/cdn");
    }
}