<?php
    namespace App\Controller\Dashboard;
    use App\Controller\Dashboard\DashboardPageController;
    use App\Utils\ViewManager;
    use App\DatabaseManager\Pagination;
    use App\Model\Entity\Grupos as EntityGrupos;
    use App\Controller\Dashboard\ErrorController;
    use App\Utils\Funcoes;

    class GrupoController extends DashboardPageController{
        private static function getGruposItens($request, &$objPagination){
            $itens = '';
            $quantidadeTotal = EntityGrupos::getGrupos(null, null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;

            $queryParams = $request->getQueryParams();
            $paginaActual = $queryParams['page'] ?? 1;

            $objPagination = new Pagination($quantidadeTotal, $paginaActual, 8);

            $results = EntityGrupos::getGrupos(null, 'codigo_grupo', $objPagination->getLimit());

            While ($objGrupo = $results->fetchObject(EntityGrupos::class)){
                $itens .=ViewManager::render('dashboard/modules/grupos/itens', [
                    'id'            => $objGrupo->codigo_grupo,
                    'descricao'     => $objGrupo->descricao
                ]);
            }
            return $itens;
        }

        private static function getPermissoes($start, $limit){
            $itens = "";
            $permissoes = include(__DIR__.'/../../Model/Entity/Permissoes.php');
            for($i=$start; $i < $limit; $i++) {
                $itens .=ViewManager::render('dashboard/modules/grupos/itemPermissao', [
                    'id__permissao' => $i,
                    'permissao'     => $permissoes[$i]['permissao'],
                    'descricao'     => $permissoes[$i]['funcionalidade']
                ]);
            }
            return $itens;
        }

        private static function getPermissoesBD($start, $limit, $id){
            $itens = "";
            $objGrupo =  EntityGrupos::getGrupoById($id); 

            if(!$objGrupo instanceof EntityGrupos){
                $request->getRouter()->redirect('/grupos');
            }

            $permissao_temporaria = substr($objGrupo->permissoes, $start, 1);
            $checked = $permissao_temporaria == '1' ? 'checked' : '';
            $permissoes = include(__DIR__.'/../../Model/Entity/Permissoes.php');
            for($i=$start; $i < $limit; $i++) {
                $itens .=ViewManager::render('dashboard/modules/grupos/itemPermissao', [
                    'id__permissao' => $i,
                    'permissao'     => $permissoes[$i]['permissao'],
                    'descricao'     => $permissoes[$i]['funcionalidade'],
                    'check'         => $checked
                ]);
            }
            return $itens;
        }

     
        public static function getNewGrupo($request){
            if(Funcoes::Permition(0)){
                $content = ViewManager::render('dashboard/modules/grupos/cadastro',[
                    'navbar'        => parent::getNavbar(),
                    'indicate'              => 'Cadastrar grupo de utilizadores',
                    'description'           => "",
                    'itemAdmin'             => self::getPermissoes(0, 5),
                    'itemClinica'           => self::getPermissoes(5, 9),
                    'itemFarmacia'          => self::getPermissoes(9, 11),
                    'button'                => 'Cadastrar'
                ]);

                return parent::getPainel('Centro-medico - Grupos cadastrar', $content);
            }else{
                return ErrorController::getError($request);
            }
        }

        public static function setNewGrupo($request){
            if(Funcoes::Permition(0)){
                $permissoes = [];
                $total__permissoes = count(include(__DIR__.'/../../Model/Entity/Permissoes.php'));
                $postVars = $request->getPostVars();

                if(isset($postVars['check_permissao'])){
                    $permissoes = $postVars['check_permissao'];
                }
                
                $permissoes__finais = "";
                for( $i = 0; $i < 100; $i++){
                    if ($i < $total__permissoes){
                        if(in_array($i, $permissoes)){
                            $permissoes__finais .= '1';
                        }else{
                            $permissoes__finais .= '0';
                        }
                    }else{
                        $permissoes__finais .= '0';
                    }
                }

                $objGrupo = new EntityGrupos;
                $objGrupo->descricao        = $postVars['text_descricao'];
                $objGrupo->permissoes       = $permissoes__finais;
                
                $objGrupo->cadastrar();

                $request->getRouter()->redirect('/grupos?status=created');
            }else{
                return ErrorController::getError($request);
            }
        }


        public static function getEditGrupo($request, $id){
            if(Funcoes::Permition(0)){
                $objGrupo =  EntityGrupos::getGrupoById($id); 

                if(!$objGrupo instanceof EntityGrupos){
                    $request->getRouter()->redirect('/grupos');
                }

                $content = ViewManager::render('dashboard/modules/grupos/cadastro',[
                    'navbar'                => parent::getNavbar(),
                    'indicate'              => 'Editar grupo de utilizadores',
                    'description'           => $objGrupo->descricao,
                    'itemAdmin'             => self::getPermissoesBD(0, 5, $id),
                    'itemClinica'           => self::getPermissoesBD(5, 9, $id),
                    'itemFarmacia'          => self::getPermissoesBD(9, 11, $id),
                    'button'                => 'Actualizar'
                ]);

                return parent::getPainel('Centro-medico - Grupos editar', $content);
            }else{
                return ErrorController::getError($request);
            }
        }


        public static function setEditGrupo($request, $id){
            if(Funcoes::Permition(0)){
                $objGrupo =  EntityGrupos::getGrupoById($id); 

                if(!$objGrupo instanceof EntityGrupos){
                    $request->getRouter()->redirect('/grupos');
                }

                $permissoes = [];
                $total__permissoes = count(include(__DIR__.'/../../Model/Entity/Permissoes.php'));
                $postVars = $request->getPostVars();

                if(isset($postVars['check_permissao'])){
                    $permissoes = $postVars['check_permissao'];
                }
                
                $permissoes__finais = "";
                for( $i = 0; $i < 100; $i++){
                    if ($i < $total__permissoes){
                        if(in_array($i, $permissoes)){
                            $permissoes__finais .= '1';
                        }else{
                            $permissoes__finais .= '0';
                        }
                    }else{
                        $permissoes__finais .= '0';
                    }
                }

                $objGrupo->descricao        = $postVars['text_descricao'] ?? $objGrupo->descricao;
                $objGrupo->permissoes       = $permissoes__finais ?? $objGrupo->permissoes;

                $objGrupo->actualizar();

                $request->getRouter()->redirect('/grupos?status=updated');
            }else{
                return ErrorController::getError($request);
            }
        }

 
        private static function getStatus($request){
            $queryParams = $request->getQueryParams();
            
            if(!isset($queryParams['status'])) return '';

            switch($queryParams['status']){
                case 'created':
                    return Alert::getSuccess('Grupo cadastrado com sucesso.');
                    break;
                case 'updated':
                    return Alert::getSuccess('Grupo actualizada com sucesso.');
                    break;
                case 'deleted':
                    return Alert::getSuccess('Grupo excluido com sucesso.');
                    break;
            }
        } 
        
        public static function getGrupos($request){
            if(Funcoes::Permition(0)){
                $content = ViewManager::render('dashboard/modules/grupos/grupos',[
                    'navbar'        => parent::getNavbar(),
                    'itens'         => self::getGruposItens($request, $objPagination),
                    'pagination'    => parent::getPagination($request, $objPagination),
                    'status'        => self::getStatus($request)
                ]);

                return parent::getPainel('Centro-medico - Grupos', $content);
            }else{
                return ErrorController::getError($request);
            }
        } 

    }
?>