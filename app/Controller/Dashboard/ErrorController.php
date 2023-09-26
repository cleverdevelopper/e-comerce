<?php
    namespace App\Controller\Dashboard;
    use App\Utils\ViewManager;

    class ErrorController{
        public static function getError($request){
            return ViewManager::render('error/error', []);
        }
    }
?>