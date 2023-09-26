<?php
    namespace App\Controller\Dashboard;
    use App\Controller\Dashboard\DashboardPageController;
    use App\Utils\ViewManager;
    use App\DatabaseManager\Pagination;
    use App\Model\Entity\Diagnostico as EntityDiagnostico;
    use App\Controller\Dashboard\ErrorController;
    use App\Utils\Funcoes;

    class DiagnosticoController extends DashboardPageController{

        private static function getDiagnosticoItens($request, &$objPagination){
            $itens = '';
            $quantidadeTotal = EntityDiagnostico::getDiagnostico(null, null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;

            $queryParams = $request->getQueryParams();
            $paginaActual = $queryParams['page'] ?? 1;

            $objPagination = new Pagination($quantidadeTotal, $paginaActual, 8);

            $results = EntityDiagnostico::getDiagnostico(null, 'codigo_diagnostico', $objPagination->getLimit());

            While ($objDiagnostico = $results->fetchObject(EntityDiagnostico::class)){
                $itens .=ViewManager::render('dashboard/modules/diagnostico/itens', [
                    'id'             => $objDiagnostico->codigo_diagnostico,
                    'diagnostico'  => $objDiagnostico->diagnostico
                ]);
            }
            return $itens;
        }

        public static function getNewDiagnostico($request){
            if(Funcoes::Permition(6)){
                $content = ViewManager::render('dashboard/modules/diagnostico/cadastro',[
                    'navbar'                => parent::getNavbar(),
                    'indicate'              => 'Cadastrar Diagnostico',
                    'diagnostico'           => '',
                    'button'                => 'Cadastrar'
                ]);
    
                return parent::getPainel('Centro-medico - Diagnostico cadastrar', $content);
            }else{
                return ErrorController::getError($request);
            }
        }

        public static function setNewDiagnostico($request){
            if(Funcoes::Permition(6)){
                $postVars = $request->getPostVars();

                $objDiagnostico = new EntityDiagnostico;
                $objDiagnostico->diagnostico     = $postVars['text_diagnostico'];

                $objDiagnostico->cadastrar();

                $request->getRouter()->redirect('/diagnostico?status=created');
            }else{
                return ErrorController::getError($request);
            }
        }

        public static function getEditDiagnostico($request, $id){
            if(Funcoes::Permition(6)){
                $objDiagnostico = EntityDiagnostico::getDiagnosticoById($id);
            
                if(!$objDiagnostico instanceof EntityDiagnostico){
                    $request->getRouter()->redirect('/diagnostico');
                }

                $content = ViewManager::render('dashboard/modules/diagnostico/cadastro', [
                    'navbar'                => parent::getNavbar(),
                    'indicate'              => 'Actualizar Diagnostico',
                    'diagnostico'           => $objDiagnostico->diagnostico,
                    'button'                => 'Actualizar'
                ]);
    
                return parent::getPainel('Centro-medico - Diagnostico editar', $content);
            }else{
                return ErrorController::getError($request);
            }
        }


        public static function setEditDiagnostico($request, $id){
            if(Funcoes::Permition(6)){
                $objDiagnostico = EntityDiagnostico::getDiagnosticoById($id);
            
                if(!$objDiagnostico instanceof EntityDiagnostico){
                    $request->getRouter()->redirect('/diagnostico');
                }

                $postVars = $request->getPostVars();
                $objDiagnostico->diagnostico     = $postVars['text_diagnostico'];

                $objDiagnostico->actualizar();

                $request->getRouter()->redirect('/diagnostico?status=updated');
            }else{
                return ErrorController::getError($request);
            }
        }

        private static function getStatus($request){
            $queryParams = $request->getQueryParams();
            
            if(!isset($queryParams['status'])) return '';

            switch($queryParams['status']){
                case 'created':
                    return Alert::getSuccess('Diagnostico cadastrado com sucesso.');
                    break;
                case 'updated':
                    return Alert::getSuccess('Diagnostico actualizada com sucesso.');
                    break;
                case 'deleted':
                    return Alert::getSuccess('Diagnostico excluido com sucesso.');
                    break;
            }
        } 


        public static function getDiagnostico($request){
            if(Funcoes::Permition(6)){
                $content = ViewManager::render('dashboard/modules/diagnostico/diagnostico',[
                    'navbar'        => parent::getNavbar(),
                    'itens'         => self::getDiagnosticoItens($request, $objPagination),
                    'pagination'    => parent::getPagination($request, $objPagination),
                    'status'        => self::getStatus($request)
                ]);
    
                return parent::getPainel('Centro-medico - Diagnostico', $content);
            }else{
                return ErrorController::getError($request);
            }
            
        } 

    }
?>