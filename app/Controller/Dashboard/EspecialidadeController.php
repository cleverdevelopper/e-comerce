<?php
    namespace App\Controller\Dashboard;
    use App\Controller\Dashboard\DashboardPageController;
    use App\Utils\ViewManager;
    use App\DatabaseManager\Pagination;
    use App\Model\Entity\Especialidade as EntityEspecialidade;
    use App\Controller\Dashboard\ErrorController;
    use App\Utils\Funcoes;

    class EspecialidadeController extends DashboardPageController{

        private static function getEspecialidadeItens($request, &$objPagination){
            $itens = '';
            $quantidadeTotal = EntityEspecialidade::getEspecialidades(null, null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;

            $queryParams = $request->getQueryParams();
            $paginaActual = $queryParams['page'] ?? 1;

            $objPagination = new Pagination($quantidadeTotal, $paginaActual, 8);

            $results = EntityEspecialidade::getEspecialidades(null, 'codigo_especialidade', $objPagination->getLimit());

            While ($objEspecialidade = $results->fetchObject(EntityEspecialidade::class)){
                $itens .=ViewManager::render('dashboard/modules/especialidades/itens', [
                    'id'             => $objEspecialidade->codigo_especialidade,
                    'especialidade'  => $objEspecialidade->nome_especialidade
                ]);
            }
            return $itens;
        }

        public static function getNewEspecialidade($request){
            if(Funcoes::Permition(3)){
                $content = ViewManager::render('dashboard/modules/especialidades/cadastro',[
                    'navbar'                => parent::getNavbar(),
                    'indicate'              => 'Cadastrar Esepcialidade',
                    'especialidade'         => '',
                    'button'                => 'Cadastrar'
                ]);
    
                return parent::getPainel('Centro-medico - Especialidade cadastrar', $content);
            }else{
                return ErrorController::getError($request);
            }
        }

        public static function setNewEspecialidade($request){
            if(Funcoes::Permition(3)){
                $postVars = $request->getPostVars();

                $objEspecialidade = new EntityEspecialidade;
                $objEspecialidade->nome_especialidade     = $postVars['text_especialidade'];

                $objEspecialidade->cadastrar();

                $request->getRouter()->redirect('/especialidade?status=created');
            }else{
                return ErrorController::getError($request);
            }
        }

        public static function getEditEspecialidade($request, $id){
            if(Funcoes::Permition(3)){
                $objEspecialidade = EntityEspecialidade::getEspecialidadeById($id);
            
                if(!$objEspecialidade instanceof EntityEspecialidade){
                    $request->getRouter()->redirect('/especialidade');
                }

                $content = ViewManager::render('dashboard/modules/especialidades/cadastro', [
                    'navbar'        => parent::getNavbar(),
                    'indicate'              => 'Actualizar Especialidade',
                    'especialidade'         => $objEspecialidade->nome_especialidade,
                    'button'                => 'Actualizar'
                ]);
    
                return parent::getPainel('Centro-medico - Especialidade editar', $content);
            }else{
                return ErrorController::getError($request);
            }
        }


        public static function setEditEspecialidade($request, $id){
            if(Funcoes::Permition(3)){
                $objEspecialidade = EntityEspecialidade::getEspecialidadeById($id);
            
                if(!$objEspecialidade instanceof EntityEspecialidade){
                    $request->getRouter()->redirect('/especialidade');
                }

                $postVars = $request->getPostVars();
                $objEspecialidade->nome_especialidade     = $postVars['text_especialidade'];

                $objEspecialidade->actualizar();

                $request->getRouter()->redirect('/especialidade?status=updated');
            }else{
                return ErrorController::getError($request);
            }
        }

        private static function getStatus($request){
            $queryParams = $request->getQueryParams();
            
            if(!isset($queryParams['status'])) return '';

            switch($queryParams['status']){
                case 'created':
                    return Alert::getSuccess('Especialidade cadastrado com sucesso.');
                    break;
                case 'updated':
                    return Alert::getSuccess('Especialidade actualizada com sucesso.');
                    break;
                case 'deleted':
                    return Alert::getSuccess('Especialidade excluido com sucesso.');
                    break;
            }
        } 


        public static function getEspecialidades($request){
            if(Funcoes::Permition(3)){
                $content = ViewManager::render('dashboard/modules/especialidades/especialidades',[
                    'navbar'        => parent::getNavbar(),
                    'itens'         => self::getEspecialidadeItens($request, $objPagination),
                    'pagination'    => parent::getPagination($request, $objPagination),
                    'status'        => self::getStatus($request)
                ]);
    
                return parent::getPainel('Centro-medico - Especialidade', $content);
            }else{
                return ErrorController::getError($request);
            }
            
        } 

    }
?>