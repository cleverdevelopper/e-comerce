<?php
    namespace App\Controller\Dashboard;
    use App\Utils\ViewManager;

    class Alert{
        public static function getSuccess($message){
            return ViewManager::render('dashboard/alert/status', [
                'titulo'    => 'Sucesso',
                'tipo'      => 'success',
                'mensagem'  => $message
            ]);
        }

        public static function getError($message){
            return ViewManager::render('dashboard/alert/status', [
                'titulo'    => 'Erro',
                'tipo'      => 'error',
                'mensagem'  => $message
            ]);
        }
    }

?>