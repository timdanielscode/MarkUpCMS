<?php

namespace app\controllers\admin;

use app\controllers\Controller;
use database\DB;
use app\models\WebsiteSlug;
use validation\Rules;
                    
class SettingsController extends Controller {
                
    public function index() {    

        $data['currentLoginSlug'] = DB::try()->select('slug')->from('websiteSlug')->first();

        return $this->view('/admin/settings/index', $data);
    }

    public function updateSlug($request) {

        $currentWebsiteSlug = DB::try()->all('websiteSlug')->fetch();

        $rules = new Rules();
        $unique = DB::try()->select('slug')->from('pages')->where('slug', '=', "/" . $request['slug'])->fetch();

        if($rules->update_website_slug($unique)->validated()) {

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
        } else {

            $data['rules'] = $rules->errors;
            return $this->view('/admin/settings/index', $data);
        }

        redirect('/admin/settings');
    }
}  