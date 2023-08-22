<?php

namespace app\controllers;

use app\controllers\Controller;
use database\DB;

class SendFormMailController extends Controller {

    public function send($request) {

        foreach($request as $test) {

            echo $test;
        }
    }
}