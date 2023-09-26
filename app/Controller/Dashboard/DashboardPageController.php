<?php
    namespace App\Controller\Dashboard;
    use App\Utils\ViewManager;
    use App\Utils\Funcoes;

    class DashboardPageController{
        private static function init(){
            if(session_status() != PHP_SESSION_ACTIVE){
                session_start();
            }
        }

        private static function getPermition(){
            $links = '';
            if(Funcoes::Permition(0)){
                $links .= ViewManager::render('dashboard/menu/itemAdministracao', [
                    'page'   => $page['page'],
                    'link'   => $link,
                    'active' => $page['current'] ? 'active' : ''
                ]);
            }
        }
        
        public static function getDashboardPage($title, $content){
            return ViewManager::render('dashboard/dashboardPage', [
                'title'     => $title,
                'content'   => $content
            ]);
        } 

        public static function getNavbar(){
            return ViewManager::render('dashboard/menu/navbar', [
                'name' => $_SESSION['admin']['utilizador']['nome'],
            ]);
        }

        //===============================================
        // Permissoes
        //===============================================
        private static function getClinica(){
            $itens = '';
            $itens .= ViewManager::render('dashboard/menu/clinica', []);   
            return $itens;
        }

        private static function getFarmacia(){
            $itens = '';
            $itens .= ViewManager::render('dashboard/menu/farmacia', []);   
            return $itens;
        }

        private static function getAdmin(){
            $itens = '';
            $itens .= ViewManager::render('dashboard/menu/administracao', []);   
            return $itens;
        }


        private static function getMenu(){
            if(Funcoes::Permition(0)){
                return ViewManager::render('dashboard/menu/box', [
                    'administracao'     => self::getAdmin(),
                    'clinica'           => self::getClinica(),
                    'farmacia'          => self::getFarmacia(),
                ]);
            }elseif(Funcoes::Permition(5)){
                return ViewManager::render('dashboard/menu/box', [
                    'administracao'     => '',
                    'clinica'           => self::getClinica(),
                    'farmacia'          => '',
                ]);
            }elseif(Funcoes::Permition(9)){
                return ViewManager::render('dashboard/menu/box', [
                    'administracao'     => '',
                    'clinica'           => '',
                    'farmacia'          => self::getFarmacia(),
                ]);
            }
        }

         //===============================================
        // End Permissoes
        //===============================================

        public static function getPainel($title, $content){
            $contentPainel = ViewManager::render('dashboard/painel', [
                'menu'      => self::getMenu(),
                'content'   => $content
            ]);
            return self::getDashboardPage($title, $contentPainel);
        }

        public static function getPagination($request, $objPagination){
            $pages = $objPagination->getPages();

            if(count($pages) <= 1) return '';

            $links = '';
            $url = $request->getRouter()->getCurrentUrl();
            $queryParams = $request->getQueryParams();

            foreach($pages as $page){
                $queryParams['page'] = $page['page'];
                $link = $url.'?'.http_build_query($queryParams);
                $links .= ViewManager::render('dashboard/pagination/link', [
                    'page'   => $page['page'],
                    'link'   => $link,
                    'active' => $page['current'] ? 'active' : ''
                ]);
            }

            return ViewManager::render('dashboard/pagination/box', [
                'links' => $links
            ]);
        }
    }
?>