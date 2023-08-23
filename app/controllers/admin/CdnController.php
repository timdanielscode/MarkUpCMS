<?php

namespace app\controllers\admin;

use app\controllers\Controller;
use app\models\Cdn;
use database\DB;
use extensions\Pagination;

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

        return $this->view('admin/cdn/create');
    }

    public function store($request) {

        Cdn::insert([

            'title' => $request['title'],
            'content' => $request['code'],
            'created_at' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']),
            'updated_at' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME'])
        ]);

        redirect('/admin/cdn');
    }

    public function edit($request) {

        $cdn = Cdn::get($request['id']);

        $data['cdn'] = $cdn;

        return $this->view('admin/cdn/edit', $data);
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

}