<?php

namespace app\controllers\admin;

use app\controllers\Controller;
use core\Csrf;
use validation\Rules;
use app\models\Post;
use app\models\CategoryPage;
use app\models\Category;
use core\Session;
use database\DB;
use core\Request;
use extensions\Pagination;
use core\Response;

class PostController extends Controller {

    public function index() {

        $post = new Post();
        $posts = $post->allPostsWithCategories();
        
        $search = get('search');

        if(!empty($search) ) {

            $posts = $post->allPostsWithCategories($search);
        }
        $count = count($posts);

        $posts = Pagination::get($posts, 3);
        $numberOfPages = Pagination::getPageNumbers();

        $data["posts"] = $posts;
        $data["count"] = $count;
        $data['numberOfPages'] = $numberOfPages;

        return $this->view('admin/posts/index', $data);
    }

    public function create() {
        
        $data['rules'] = [];
        return $this->view('admin/posts/create', $data);
    }

    public function store($request) {

        if(submitted("submit") === true && Csrf::validate(Csrf::token('get'), post('token')) === true ) {

            $rules = new Rules();
            $post = new Post();

            $uniqueTitle = DB::try()->select('id, title')->from('pages')->where('title', '=', $request['title'])->and('id', '!=', $request['id'])->fetch();
    
            if($rules->create_post($uniqueTitle)->validated()) {
                        
                $slug = "/".post('title');
                $slug = str_replace(" ", "-", $slug);
    
                Post::insert([
    
                    'title' => $request['title'],
                    'slug' => $slug,
                    'body' => $request['body'],
                    'author' => Session::get('username'),
                    'date_created_at' => date("d/m/Y"),
                    'time_created_at' => date("H:i"),
                    'date_updated_at' => date("d/m/Y"),
                    'time_updated_at' => date("H:i")
                ]);
    
                Session::set('create', 'You have successfully created a new post!');            
                redirect('/admin/posts');
            } else {

                $data['rules'] = $rules->errors;
                return $this->view('admin/posts/create', $data);
            }
        }
    }

    public function read($request) {

        $post = Post::get($request['id']);

        $cssFiles = DB::try()->select('file_name', 'extension')->from('css')->join('css_page')->on('css_page.css_id', '=', 'css.id')->where('css_page.page_id', '=', $request['id'])->fetch();
        $jsFiles = DB::try()->select('file_name', 'extension')->from('js')->join('js_page')->on('js_page.js_id', '=', 'js.id')->where('js_page.page_id', '=', $request['id'])->fetch();

        $menusTop = DB::try()->all('menus')->where('position', '=', 'top')->order('ordering')->fetch();
        $menusBottom = DB::try()->all('menus')->where('position', '=', 'bottom')->order('ordering')->fetch();

        $data['menusTop'] = $menusTop;
        $data['menusBottom'] = $menusBottom;
        $data['post'] = $post;
        $data['cssFiles'] = $cssFiles;
        $data['jsFiles'] = $jsFiles;

        return $this->view('/admin/posts/read', $data);
    }

    public function edit($request) {

        $post = Post::get($request['id']);

        $postSlug = explode('/', $post['slug']);
        $postSlug = "/" . $postSlug[array_key_last($postSlug)];

        $linkedCssFiles = DB::try()->select('css.id, css.file_name, css.extension')->from('css')->join('css_page')->on('css_page.css_id', '=', 'css.id')->where('css_page.page_id', '=', $request['id'])->fetch();
        $notLinkedCssFiles = $this->notLinkedCssFiles($linkedCssFiles);
        $linkedJsFiles = DB::try()->select('js.id, js.file_name, js.extension')->from('js')->join('js_page')->on('js_page.js_id', '=', 'js.id')->where('js_page.page_id', '=', $request['id'])->fetch();
        $notLinkedJsFiles = $this->notLinkedJsFiles($linkedJsFiles);
    
        $data['data'] = $post;

        $ifAlreadyAssingedToCategory = DB::try()->select('page_id')->from('category_page')->where('page_id', '=', $request['id'])->fetch();

        if(empty($ifAlreadyAssingedToCategory)) {

            $categories = DB::try()->select('id, title')->from("categories")->fetch();
            $data['data']['categories'] = $categories;
        } else {

            $category = DB::try()->select('categories.title, categories.slug')->from('categories')->join('category_page')->on('category_page.category_id', '=', 'categories.id')->where('category_page.page_id', '=', $request['id'])->first();
            $data['data']['category'] = $category;
        }

        $data['data']['postSlug'] = $postSlug;
        $data['data']['linkedCssFiles'] = $linkedCssFiles;
        $data['data']['notLinkedCssFiles'] = $notLinkedCssFiles;
        $data['data']['linkedJsFiles'] = $linkedJsFiles;
        $data['data']['notLinkedJsFiles'] = $notLinkedJsFiles;
        $data['rules'] = [];

        return $this->view('admin/posts/edit', $data);
    }

    public function notLinkedCssFiles($linkedCssFiles) {

        $linkedCssFileIds = [];

        if(!empty($linkedCssFiles) && $linkedCssFiles !== null) {

            foreach($linkedCssFiles as $linkedCssFile) {

                array_push($linkedCssFileIds, $linkedCssFile['id']);
            }

            $linkedCssFileIdStrings = implode(',', $linkedCssFileIds);

            $notLinkedCssFiles = DB::try()->select('id, file_name, extension')->from('css')->whereNotIn('id', $linkedCssFileIdStrings)->fetch();
        } else {

            $notLinkedCssFiles = DB::try()->select('id, file_name, extension')->from('css')->fetch();
        }

        return $notLinkedCssFiles;
    }

    public function notLinkedJsFiles($linkedJsFiles) {

        $linkedJsFileIds = [];

        if(!empty($linkedJsFiles) && $linkedJsFiles !== null) {

            foreach($linkedJsFiles as $linkedJsFile) {

                array_push($linkedJsFileIds, $linkedJsFile['id']);
            }

            $linkedJsFileIdStrings = implode(',', $linkedJsFileIds);

            $notLinkedJsFiles = DB::try()->select('id, file_name, extension')->from('js')->whereNotIn('id', $linkedJsFileIdStrings)->fetch();
        } else {

            $notLinkedJsFiles = DB::try()->select('id, file_name, extension')->from('js')->fetch();
        }

        return $notLinkedJsFiles;
    }

    public function update($request) {

        if(!empty($request['submitCategory']) && $request['submitCategory'] !== null) {

            return $this->updateCategory($request);
        } else if (!empty($request['removeCategory']) && $request['removeCategory'] !== null) {

            return $this->removeCategory($request);
        } else if(!empty($request['updateSlug']) && $request['updateSlug'] !== null) {

            return $this->updateSlug($request);
        } else if(!empty($request['updateMetaData']) && $request['updateMetaData'] !== null) {

            return $this->updateMetaData($request);
        } else if(!empty($request['removeCss']) && $request['removeCss'] !== null) {

            return $this->removeCss($request);
        } else if(!empty($request['linkCss']) && $request['linkCss'] !== null) {

            return $this->linkCss($request);
        } else if(!empty($request['includeJs']) && $request['includeJs'] !== null) {

            return $this->includeJs($request);
        } else if (!empty($request['removeJs']) && $request['removeJs'] !== null) {

            return $this->removeJs($request);
        } else if(!empty($request['submit']) && $request['submit'] !== null) {

            return $this->updatePost($request);
        } else {
            return;
        }
    }

    public function updatePost($request) {

        if(submitted("submit") === true && Csrf::validate(Csrf::token('get'), post('token')) === true ) {
                
            $post = new Post();
            $rules = new Rules();

            $uniqueTitle = DB::try()->select('id, title')->from('pages')->where('title', '=', $request['title'])->and('id', '!=', $request['id'])->fetch();

            if($rules->update_post($uniqueTitle)->validated()) {
                
                $id = $request['id'];

                    Post::update(['id' => $id], [

                        'title' => $request["title"],
                        'body' => $request["body"],
                        'date_updated_at' => date("d/m/Y"),
                        'time_updated_at' => date("H:i")
                    ]);

                    Session::set('updated', 'User updated successfully!');
                    redirect("/admin/posts/$id/edit");
                
            } else {

                $ifAlreadyAssingedToCategory = DB::try()->select('page_id')->from('category_page')->where('page_id', '=', $request['id'])->fetch();

                if(empty($ifAlreadyAssingedToCategory)) {
        
                    $categories = DB::try()->select('id, title')->from("categories")->fetch();
                    $data['data']['categories'] = $categories;
                } else {
        
                    $category = DB::try()->select('categories.title, categories.slug')->from('categories')->join('category_page')->on('category_page.category_id', '=', 'categories.id')->where('category_page.page_id', '=', $request['id'])->first();
                    $data['data']['category'] = $category;
                }

                $data['data']['linkedCssFiles'] = $linkedCssFiles = DB::try()->select('css.id, css.file_name, css.extension')->from('css')->join('css_page')->on('css_page.css_id', '=', 'css.id')->where('css_page.page_id', '=', $request['id'])->fetch();
                $data['data']['notLinkedCssFiles'] = $notLinkedCssFiles = $this->notLinkedCssFiles($linkedCssFiles);
                $data['data']['linkedJsFiles'] =  $linkedJsFiles = DB::try()->select('js.id, js.file_name, js.extension')->from('js')->join('js_page')->on('js_page.js_id', '=', 'js.id')->where('js_page.page_id', '=', $request['id'])->fetch();
                $data['data']['notLinkedJsFiles'] =  $notLinkedJsFiles = $this->notLinkedJsFiles($linkedJsFiles);
                $data['data']['id'] = DB::try()->select('id')->from('pages')->where('id', '=', $request['id'])->first()['id'];
                $data['data']['title'] = DB::try()->select('title')->from('pages')->where('id', '=', $request['id'])->first()['title'];
                $data['data']['body'] = DB::try()->select('body')->from('pages')->where('id', '=', $request['id'])->first()['body'];
                $data['data']['slug'] = DB::try()->select('slug')->from('pages')->where('id', '=', $request['id'])->first()['slug'];
                $data['data']['metaTitle'] = DB::try()->select('metaTitle')->from('pages')->where('id', '=', $request['id'])->first()['metaTitle'];
                $data['data']['metaDescription'] = DB::try()->select('metaDescription')->from('pages')->where('id', '=', $request['id'])->first()['metaDescription'];
                $data['data']['metaKeywords'] = DB::try()->select('metaKeywords')->from('pages')->where('id', '=', $request['id'])->first()['metaKeywords'];

                $postSlug = explode('/', $data['data']['slug']);
                $postSlug = "/" . $postSlug[array_key_last($postSlug)];
                $data['data']['postSlug'] = $postSlug;

                $data['rules'] = $rules->errors;

                return $this->view('admin/posts/edit', $data);
            }
        }
    }

    public function removeJs($request) {

        $id = $request['id'];
        $linkedJsIds = $request['linkedJsFiles'];

        if(!empty($linkedJsIds) && $linkedJsIds !== null) {

            foreach($linkedJsIds as $linkedJsId) {

                DB::try()->delete('js_page')->where('page_id', '=', $id)->and('js_id', '=', $linkedJsId)->run();
            }
        }

        redirect("/admin/posts/$id/edit");
    }

    public function includeJs($request) {

        $id = $request['id'];
        $jsIds = $request['jsFiles'];

        if(!empty($jsIds) && $jsIds !== null) {

            foreach($jsIds as $jsId) {

                DB::try()->insert('js_page', [

                    'js_id' => $jsId,
                    'page_id' => $id

                ])->where('js_page', '=', $id)->and('js_id', '=', $jsId);
            }
        }
        
        redirect("/admin/posts/$id/edit");
    }

    public function linkCss($request) {

        $id = $request['id'];
        $cssIds = $request['cssFiles'];

        if(!empty($cssIds) && $cssIds !== null) {

            foreach($cssIds as $cssId) {

                DB::try()->insert('css_page', [

                    'css_id' => $cssId,
                    'page_id' => $id

                ])->where('css_page', '=', $id)->and('css_id', '=', $cssId);
            }
        }
        redirect("/admin/posts/$id/edit");
    }

    public function removeCss($request) {

        $id = $request['id'];
        $linkedCssIds = $request['linkedCssFiles'];

        if(!empty($linkedCssIds) && $linkedCssIds !== null) {

            foreach($linkedCssIds as $linkedCssId) {

                DB::try()->delete('css_page')->where('page_id', '=', $id)->and('css_id', '=', $linkedCssId)->run();
            }
        }

        redirect("/admin/posts/$id/edit");
    }

    public function updateSlug($request) {

        $id = $request['id'];

        $slug = explode('/', $request['slug']);
        $slug[array_key_last($slug)] = substr($request['postSlug'], 1);
        $slug = implode('/', array_filter($slug));
        
        Post::update(['id' => $id], [

            'slug' => "/" . $slug
        ]);

        redirect("/admin/posts/$id/edit");
    }

    public function updateMetaData($request) {

        $id = $request['id'];

        $rules = new Rules();

        if($rules->update_metadata()->validated()) {

            Post::update(['id' => $id], [

                'metaTitle' => $request['metaTitle'],
                'metaDescription' => $request['metaDescription'],
                'metaKeywords' => $request['metaKeywords']
            ]);

        } else {

            $ifAlreadyAssingedToCategory = DB::try()->select('page_id')->from('category_page')->where('page_id', '=', $request['id'])->fetch();

            if(empty($ifAlreadyAssingedToCategory)) {
    
                $categories = DB::try()->select('id, title')->from("categories")->fetch();
                $data['data']['categories'] = $categories;
            } else {
    
                $category = DB::try()->select('categories.title, categories.slug')->from('categories')->join('category_page')->on('category_page.category_id', '=', 'categories.id')->where('category_page.page_id', '=', $request['id'])->first();
                $data['data']['category'] = $category;
            }

            $data['data']['linkedCssFiles'] = $linkedCssFiles = DB::try()->select('css.id, css.file_name, css.extension')->from('css')->join('css_page')->on('css_page.css_id', '=', 'css.id')->where('css_page.page_id', '=', $request['id'])->fetch();
            $data['data']['notLinkedCssFiles'] = $notLinkedCssFiles = $this->notLinkedCssFiles($linkedCssFiles);
            $data['data']['linkedJsFiles'] =  $linkedJsFiles = DB::try()->select('js.id, js.file_name, js.extension')->from('js')->join('js_page')->on('js_page.js_id', '=', 'js.id')->where('js_page.page_id', '=', $request['id'])->fetch();
            $data['data']['notLinkedJsFiles'] =  $notLinkedJsFiles = $this->notLinkedJsFiles($linkedJsFiles);
            $data['data']['id'] = DB::try()->select('id')->from('pages')->where('id', '=', $request['id'])->first()['id'];
            $data['data']['body'] = DB::try()->select('body')->from('pages')->where('id', '=', $request['id'])->first()['body'];
            $data['data']['title'] = DB::try()->select('title')->from('pages')->where('id', '=', $request['id'])->first()['title'];
            $data['data']['slug'] = DB::try()->select('slug')->from('pages')->where('id', '=', $request['id'])->first()['slug'];
            $data['data']['metaTitle'] = DB::try()->select('metaTitle')->from('pages')->where('id', '=', $request['id'])->first()['metaTitle'];
            $data['data']['metaDescription'] = DB::try()->select('metaDescription')->from('pages')->where('id', '=', $request['id'])->first()['metaDescription'];
            $data['data']['metaKeywords'] = DB::try()->select('metaKeywords')->from('pages')->where('id', '=', $request['id'])->first()['metaKeywords'];

            $postSlug = explode('/', $data['data']['slug']);
            $postSlug = "/" . $postSlug[array_key_last($postSlug)];
            $data['data']['postSlug'] = $postSlug;

            $data['rules'] = $rules->errors;

            return $this->view('admin/posts/edit', $data);
        }

        redirect("/admin/posts/$id/edit");
    }

    public function updateCategory($request) {

        $categoryId = $request['categories'];
        $pageId = $request['id'];

        CategoryPage::insert([

            'category_id' => $categoryId,
            'page_id'    => $pageId
        ]);

        $currentCategorySlug = DB::try()->select('categories.slug')->from('categories')->join('category_page')->on('category_page.category_id', '=', 'categories.id')->where('categories.id', '=', $categoryId)->first();
        $currentSlugs = DB::try()->select('slug')->from('pages')->where('id', '=', $pageId)->fetch();

        foreach($currentSlugs as $currentSlug) {

            $subCategories = DB::try()->select('categories.slug')->from('categories')->join('category_sub')->on('categories.id', '=', 'category_sub.sub_id')->where('category_sub.category_id', '=', $categoryId)->fetch();

            if(!empty($subCategories) ) {
    
                $subCategoriesArray = [];
    
                foreach($subCategories as $subCategory) {
    
                    array_push($subCategoriesArray, $subCategory['slug']);
                }
    
                $subCategoriesSlug = implode('', $subCategoriesArray);
    
                Post::update(['id' => $pageId], [
    
                    'slug'  => $currentCategorySlug['slug'] . $subCategoriesSlug . $currentSlug['slug']
                ]);
    
            } else {
    
                Post::update(['id' => $pageId], [
    
                    'slug'  => $currentCategorySlug['slug'] . $currentSlug['slug']
                ]);
            }
        }

        redirect('/admin/posts/'. $pageId . '/edit');
    }

    public function removeCategory($request) {

        $pageId = $request['id'];

        $postSlug = DB::try()->select('slug')->from('pages')->where('id', '=', $pageId)->first();
        
        $slugParts = explode('/', $postSlug['slug']);
        $lastPageSlugKey = array_key_last($slugParts);
        $lastPageSlugValue = "/" . $slugParts[$lastPageSlugKey];

        Post::update(['id' => $pageId], [

            'slug'  => $lastPageSlugValue
        ]);

        CategoryPage::delete('page_id', $pageId);

        redirect('/admin/posts/'. $request['id'] . '/edit');
    }

    public function delete($request) {

        Post::delete("id", $request['id']);
        CategoryPage::delete('page_id', $request['id']);

        redirect("/admin/posts");
    }
}