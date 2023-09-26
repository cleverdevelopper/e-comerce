<?php
    namespace App\Controller\Dashboard;
    use App\Controller\Dashboard\DashboardPageController;
    use App\Utils\ViewManager;
    use App\DatabaseManager\Pagination;
    use App\Model\Entity\Funcionario as EntityFuncionario;
    use App\Model\Entity\Especialidade as EntityEspecialidade;
    use App\Model\Entity\Grupos as EntityGrupos;
    use App\Controller\Dashboard\ErrorController;
    use App\Utils\Funcoes;

    class FuncionarioController extends DashboardPageController{

        private static function getFuncionariosItens($request, &$objPagination){
            $itens = '';
            $quantidadeTotal = EntityFuncionario::getFuncionarios(null, null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;

            $queryParams = $request->getQueryParams();
            $paginaActual = $queryParams['page'] ?? 1;

            $objPagination = new Pagination($quantidadeTotal, $paginaActual, 8);

            $results = EntityFuncionario::getFuncionarios(null, 'codigo_funcionario', $objPagination->getLimit());

            While ($objFuncionario = $results->fetchObject(EntityFuncionario::class)){
                $objEspecialidade = EntityEspecialidade::getEspecialidadeById($objFuncionario->especialidade);
                $itens .=ViewManager::render('dashboard/modules/funcionario/itens', [
                    'id'            => $objFuncionario->codigo_funcionario,
                    'nome'          => $objFuncionario->nome_funcionario,
                    'sexo'          => $objFuncionario->sexo,
                    'especialidade' => $objEspecialidade->nome_especialidade
                ]);
            }
            return $itens;
        }

        private static function getEspecialidadeItens($request){
            $itens = '';
           
            $results = EntityEspecialidade::getEspecialidades(null, 'codigo_especialidade', null);

            While ($objEspecialidade = $results->fetchObject(EntityEspecialidade::class)){
                $itens .=ViewManager::render('dashboard/modules/funcionario/especialidade', [
                    'id'             => $objEspecialidade->codigo_especialidade,
                    'especialidade'  => $objEspecialidade->nome_especialidade
                ]);
            }
            return $itens;
        }

        private static function getGruposItens($request){
            $itens = '';
            $results = EntityGrupos::getGrupos(null, 'codigo_grupo', null);

            While ($objGrupo = $results->fetchObject(EntityGrupos::class)){
                $itens .=ViewManager::render('dashboard/modules/funcionario/grupo', [
                    'id'            => $objGrupo->codigo_grupo,
                    'descricao'     => $objGrupo->descricao
                ]);
            }
            return $itens;
        }


        public static function getNewFuncionario($request){
            if(Funcoes::Permition(2)){
                $content = ViewManager::render('dashboard/modules/funcionario/cadastro', [
                    'navbar'        => parent::getNavbar(),
                    'indicate'              => 'Cadastrar Funcionario',
                    'nome_funcionario'      => '',
                    'casa_funcionario'      => '',
                    'formacao_funcionario'  => '',
                    'genero_funcionario'    => '',
                    'provincia_funcionario' => '',
                    'distrito_funcionario'  => '',
                    'bairro'                => '',
                    'telefone_funcionario'  => '',
                    'email_funcionario'     => '',
                    'utilizador_funcionario'=> '',
                    'senha_funcionario'     => '',
                    'especialidade_editar'  => '',
                    'grupos_editar'         => '',
                    'especialidade__item'   => self::getEspecialidadeItens($request),
                    'grupo_funcionario'     => self::getGruposItens($request),
                    'button'                => 'Cadastrar'
                ]);
    
                return parent::getPainel('Centro-medico - Funcionario cadastrar', $content);
            }else{
                return ErrorController::getError($request);
            }
        }

        public static function setNewFuncionario($request){
            if(Funcoes::Permition(2)){
                $postVars = $request->getPostVars();

                $objFuncionario = new EntityFuncionario;
                $objFuncionario->nome_funcionario       = $postVars['nome_funcionario'];
                $objFuncionario->sexo                   = $postVars['genero_funcionario'];
                $objFuncionario->especialidade          = $postVars['especialidade_funcionario'];
                $objFuncionario->formacao               = $postVars['formacao_funcionario'];
                $objFuncionario->distrito               = $postVars['distrito_funcionario'];
                $objFuncionario->bairro                 = $postVars['bairro_funcionario'];
                $objFuncionario->rua                    = $postVars['casa_funcionario'];
                $objFuncionario->provincia              = $postVars['provincia_funcionario'];
                $objFuncionario->telefone               = $postVars['telefone_funcionario'];
                $objFuncionario->email                  = $postVars['email_funcionario'];
                $objFuncionario->utilizador             = $postVars['utilizador_funcionario'];
                $objFuncionario->palavra_passe          = md5($postVars['senha_funcionario']);
                $objFuncionario->criado_em              = date('Y-m-d H:i:s');
                $objFuncionario->atualizado_em          = date('Y-m-d H:i:s');
                $objFuncionario->grupos                 = $postVars['grupo_funcionario'];

                $objFuncionario->cadastrar();

                $request->getRouter()->redirect('/funcionario?status=created');
            }else{
                return ErrorController::getError($request);
            }
        }

        public static function getEditFuncionario($request, $id){
            if(Funcoes::Permition(2)){
                $objFuncionario = EntityFuncionario::getFuncionarioById($id);
            
                if(!$objFuncionario instanceof EntityFuncionario){
                    $request->getRouter()->redirect('/funcionario');
                }

                $objEspecialidade = EntityEspecialidade::getEspecialidadeById($objFuncionario->especialidade);
                $objGrupo = EntityGrupos::getGrupoById($objFuncionario->grupos);
                $content = ViewManager::render('dashboard/modules/funcionario/cadastro', [
                    'navbar'        => parent::getNavbar(),
                    'indicate'              => 'Actualizar Funcionario',
                    'nome_funcionario'      => $objFuncionario->nome_funcionario,
                    'casa_funcionario'      => $objFuncionario->rua,
                    'formacao_funcionario'  => $objFuncionario->formacao,
                    'genero_funcionario'    => $objFuncionario->sexo,
                    'provincia_funcionario' => $objFuncionario->provincia,
                    'distrito_funcionario'  => $objFuncionario->distrito,
                    'bairro'                => $objFuncionario->bairro,
                    'telefone_funcionario'  => $objFuncionario->telefone,
                    'email_funcionario'     => $objFuncionario->email,
                    'utilizador_funcionario'=> $objFuncionario->utilizador,
                    'senha_funcionario'     => $objFuncionario->palavra_passe,
                    'especialidade_id'      => $objEspecialidade->codigo_especialidade,
                    'especialidade_editar'  => $objEspecialidade->nome_especialidade,
                    'grupos_id'             => $objGrupo->codigo_grupo,
                    'grupos_editar'         => $objGrupo->descricao,
                    'especialidade__item'   => self::getEspecialidadeItens($request),
                    'grupo_funcionario'     => self::getGruposItens($request),
                    'button'                => 'Actualizar'
                ]);
    
                return parent::getPainel('Centro-medico - Funcionario editar', $content);
            }else{
                return ErrorController::getError($request);
            }
        }


        public static function setEditFuncionario($request, $id){
            if(Funcoes::Permition(2)){
                $objFuncionario = EntityFuncionario::getFuncionarioById($id);
            
                if(!$objFuncionario instanceof EntityFuncionario){
                    $request->getRouter()->redirect('/funcionario');
                }

                $postVars = $request->getPostVars();

                $objFuncionario->nome_funcionario       = $postVars['nome_funcionario'];
                $objFuncionario->sexo                   = $postVars['genero_funcionario'];
                $objFuncionario->especialidade          = $postVars['especialidade_funcionario'];
                $objFuncionario->formacao               = $postVars['formacao_funcionario'];
                $objFuncionario->distrito               = $postVars['distrito_funcionario'];
                $objFuncionario->bairro                 = $postVars['bairro_funcionario'];
                $objFuncionario->rua                    = $postVars['casa_funcionario'];
                $objFuncionario->provincia              = $postVars['provincia_funcionario'];
                $objFuncionario->telefone               = $postVars['telefone_funcionario'];
                $objFuncionario->email                  = $postVars['email_funcionario'];
                $objFuncionario->utilizador             = $postVars['utilizador_funcionario'];
                $objFuncionario->palavra_passe          = md5($postVars['senha_funcionario']);
                $objFuncionario->atualizado_em          = date('Y-m-d H:i:s');
                $objFuncionario->grupos                 = $postVars['grupo_funcionario'];

                $objFuncionario->actualizar();

                $request->getRouter()->redirect('/funcionario?status=updated');
            }else{
                return ErrorController::getError($request);
            }
        }

        private static function getStatus($request){
            $queryParams = $request->getQueryParams();
            
            if(!isset($queryParams['status'])) return '';

            switch($queryParams['status']){
                case 'created':
                    return Alert::getSuccess('Funcionario cadastrado com sucesso.');
                    break;
                case 'updated':
                    return Alert::getSuccess('Funcionario actualizada com sucesso.');
                    break;
                case 'deleted':
                    return Alert::getSuccess('Funcionario excluido com sucesso.');
                    break;
            }
        } 


        public static function getFuncionarios($request){
            if(Funcoes::Permition(2)){
                $content = ViewManager::render('dashboard/modules/funcionario/funcionarios',[
                    'navbar'        => parent::getNavbar(),
                    'itens'         => self::getFuncionariosItens($request, $objPagination),
                    'pagination'    => parent::getPagination($request, $objPagination),
                    'status'        => self::getStatus($request)
                ]);
    
                return parent::getPainel('Centro-medico - Funcionarios', $content);
            }else{
                return ErrorController::getError($request);
            }
        } 

    }
?>