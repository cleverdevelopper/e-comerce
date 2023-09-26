<?php
    namespace App\Controller\Dashboard;
    use App\Controller\Dashboard\DashboardPageController;
    use App\Utils\ViewManager;
    use App\DatabaseManager\Pagination;
    use App\Model\Entity\Utilizador as EntityUtilizador;
    use App\Controller\Dashboard\ErrorController;
    use App\Utils\Funcoes;

    class UtilizadoresController extends DashboardPageController{
        private static function getUtilizadorItens($request, &$objPagination){
            $itens = '';
            $quantidadeTotal = EntityUtilizador::getUtilizadores(null, null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;

            $queryParams = $request->getQueryParams();
            $paginaActual = $queryParams['page'] ?? 1;

            $objPagination = new Pagination($quantidadeTotal, $paginaActual, 8);

            $results = EntityUtilizador::getUtilizadores(null, 'id_utilizador', $objPagination->getLimit());

            While ($objUtilizador = $results->fetchObject(EntityUtilizador::class)){
                $itens .=ViewManager::render('dashboard/modules/utilizadores/itens', [
                    'id'    => $objUtilizador->id_utilizador,
                    'user'  => $objUtilizador->utilizador,
                    'name'  => $objUtilizador->nome,
                    'grupo'=> $objUtilizador->descricao_grupo
                ]);
            }
            return $itens;
        }

        
        
        private static function getStatus($request){
            $queryParams = $request->getQueryParams();
            
            if(!isset($queryParams['status'])) return '';

            switch($queryParams['status']){
                case 'created':
                    return Alert::getSuccess('Utilizador cadastrado com sucesso.');
                    break;
                case 'updated':
                    return Alert::getSuccess('Utilizador actualizada com sucesso.');
                    break;
                case 'deleted':
                    return Alert::getSuccess('Utilizador excluido com sucesso.');
                    break;
            }
        } 
        
        public static function getUtilizadores($request){
            if(Funcoes::Permition(1)){
                $content = ViewManager::render('dashboard/modules/utilizadores/utilizadores',[
                    'navbar'        => parent::getNavbar(),
                    'itens'         => self::getUtilizadorItens($request, $objPagination),
                    'pagination'    => parent::getPagination($request, $objPagination),
                    'status'        => self::getStatus($request)
                ]);

                return parent::getPainel('Centro-medico - Utilizadores', $content);
            }else{
                return ErrorController::getError($request);
            }
        }

    }
?>