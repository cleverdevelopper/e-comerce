<?php
    namespace App\Controller\Dashboard;
    use App\Controller\Dashboard\DashboardPageController;
    use App\Utils\ViewManager;
    use App\DatabaseManager\Pagination;
    use App\Model\Entity\Consulta as EntityConsulta;
    use App\Model\Entity\Paciente as EntityPaciente;
    use App\Controller\Dashboard\ErrorController;
    use App\Utils\Funcoes;

    class ConsultasController extends DashboardPageController{
        private static function getPacienteName($id){
            $objPaciente = EntityPaciente::getPacienteById($id);
            
            return $objPaciente->nome_paciente;

        }

        private static function getConsultasItens($request, &$objPagination){
            $itens = '';
            $quantidadeTotal = EntityConsulta::getConsultas(null, null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;

            $queryParams = $request->getQueryParams();
            $paginaActual = $queryParams['page'] ?? 1;

            $objPagination = new Pagination($quantidadeTotal, $paginaActual, 8);
            $condition = 'is null';

            $results = EntityConsulta::getConsultas('codigo_funcionario  '.$condition, 'codigo_consulta', $objPagination->getLimit());

            While ($objConsuta = $results->fetchObject(EntityPaciente::class)){
                $itens .=ViewManager::render('dashboard/modules/consultas/itens', [
                    'id'                => $objConsuta->codigo_consulta,
                    'nome'              => self::getPacienteName($objConsuta->codigo_paciente),
                    'data_consulta'     => $objConsuta->data_consulta,
                    'horario_consulta'  => $objConsuta->horario_agendado
                ]);
            }
            return $itens;
        }

        public static function getConfirmation($request, $id){
            if(Funcoes::Permition(5)){
                $objConsuta = EntityConsulta::getConsultaById($id);
            
                if(!$objConsuta instanceof EntityConsulta){
                    $request->getRouter()->redirect('/consultas');
                }

                $content = ViewManager::render('dashboard/modules/consultas/confirmar',[
                    'navbar'                => parent::getNavbar(),
                    'nome_paciente'         => self::getPacienteName($objConsuta->codigo_paciente),
                    'data'                  => $objConsuta->data_consulta,
                    'horario'               => $objConsuta->horario_agendado,
                    'sintomas'              => $objConsuta->sintomas,
                    'button'                => 'Confirmar'
                ]);
    
                return parent::getPainel('Centro-medico - Pacientes cadastrar', $content);
            }else{
                return ErrorController::getError($request);
            }
        }

        public static function setConfirmation($request, $id){
            if(Funcoes::Permition(5)){
                $objConsuta = EntityConsulta::getConsultaById($id);
            
                if(!$objConsuta instanceof EntityConsulta){
                    $request->getRouter()->redirect('/consultas');
                }
                $postVars = $request->getPostVars();

                $objConsuta->codigo_funcionario     = $_SESSION['admin']['utilizador']['id'];

                $objConsuta->actualizar();

                $request->getRouter()->redirect('/consultas?status=confirmed');
            }else{
                return ErrorController::getError($request);
            }
        }

        /*public static function getNewPaciente($request){
            if(Funcoes::Permition(5)){
                $content = ViewManager::render('dashboard/modules/pacientes/cadastro',[
                    'navbar'        => parent::getNavbar(),
                    'indicate'              => 'Cadastrar Paciente',
                    'nome_paciente'         => '',
                    'estado_paciente'       => '',
                    'email_paciente'        => '',
                    'telefone_paciente'     => '',
                    'genero_paciente'       => '',
                    'provincia_paciente'    => '',
                    'distrito_paciente'     => '',
                    'bairro'                => '',
                    'estado_paciente'       => '',
                    'date'                  => 'date',
                    'button'                => 'Cadastrar'
                ]);
    
                return parent::getPainel('Centro-medico - Pacientes cadastrar', $content);
            }else{
                return ErrorController::getError($request);
            }
        }

        public static function setNewPaciente($request){
            if(Funcoes::Permition(5)){
                $postVars = $request->getPostVars();

                $objPaciente = new EntityPaciente;
                $objPaciente->nome_paciente     = $postVars['nome_paciente'];
                $objPaciente->estado_civil      = $postVars['estado_paciente'];
                $objPaciente->genero            = $postVars['genero_paciente'];
                $objPaciente->provincia         = $postVars['provincia_paciente'];
                $objPaciente->distrito          = $postVars['distrito_paciente'];
                $objPaciente->bairro            = $postVars['bairro_paciente'];
                $objPaciente->telefone          = $postVars['telefone_paciente'];
                $objPaciente->email             = $postVars['email_paciente'];
                $objPaciente->data_nascimento   = $postVars['data_paciente'];
                $objPaciente->criado_em         = date('Y-m-d H:i:s');
                $objPaciente->atualizado_em     = date('Y-m-d H:i:s');

                $mensagem = $objPaciente->cadastrar();

                $request->getRouter()->redirect('/pacientes?status=created');
            }else{
                return ErrorController::getError($request);
            }
        }

        public static function getEditPaciente($request, $id){
            if(Funcoes::Permition(5)){
                $objPaciente = EntityPaciente::getPacienteById($id);
            
                if(!$objPaciente instanceof EntityPaciente){
                    $request->getRouter()->redirect('/pacientes');
                }

                $content = ViewManager::render('dashboard/modules/pacientes/cadastro', [
                    'navbar'                => parent::getNavbar(),
                    'indicate'              => 'Actualizar Paciente',
                    'nome_paciente'         => $objPaciente->nome_paciente,
                    'data_paciente'         => date('Y-m-d', strtotime($objPaciente->data_nascimento)),
                    'email_paciente'        => $objPaciente->email,
                    'telefone_paciente'     => $objPaciente->telefone,
                    'genero_paciente'       => $objPaciente->genero,
                    'provincia_paciente'    => $objPaciente->provincia,
                    'distrito_paciente'     => $objPaciente->distrito,
                    'bairro'                => $objPaciente->bairro,
                    'estado_paciente'       => $objPaciente->estado_civil,
                    'date'                  => 'text',
                    'button'                => 'Actualizar'
                ]);
    
                return parent::getPainel('Centro-medico - Pacientes editar', $content);
            }else{
                return ErrorController::getError($request);
            } 
        }


        public static function setEditPaciente($request, $id){
            if(Funcoes::Permition(5)){
                $objPaciente = EntityPaciente::getPacienteById($id);
            
                if(!$objPaciente instanceof EntityPaciente){
                    $request->getRouter()->redirect('/pacientes');
                }

                $postVars = $request->getPostVars();

                $objPaciente->nome_paciente     = $postVars['nome_paciente'] ??  $objPaciente->nome_paciente;
                $objPaciente->estado_civil      = $postVars['estado_paciente'] ?? $objPaciente->estado_civil;
                $objPaciente->genero            = $postVars['genero_paciente'] ?? $objPaciente->genero;
                $objPaciente->provincia         = $postVars['provincia_paciente'] ?? $objPaciente->provincia;
                $objPaciente->distrito          = $postVars['distrito_paciente'] ?? $objPaciente->distrito;
                $objPaciente->bairro            = $postVars['bairro_paciente'] ?? $objPaciente->bairro;
                $objPaciente->telefone          = $postVars['telefone_paciente'] ?? $objPaciente->telefone;
                $objPaciente->email             = $postVars['email_paciente'] ??  $objPaciente->email;
                $objPaciente->email             = $postVars['email_paciente'] ??  $objPaciente->email;
                $objPaciente->data_nascimento   = date('Y-m-d', strtotime($postVars['data_paciente'])) ?? $objPaciente->data_nascimento;
                $objPaciente->atualizado_em   = date('Y-m-d H:i:s');

                $objPaciente->actualizar();

                $request->getRouter()->redirect('/pacientes?status=updated');
            }else{
                return ErrorController::getError($request);
            }
        }*/

        private static function getStatus($request){
            $queryParams = $request->getQueryParams();
            
            if(!isset($queryParams['status'])) return '';

            switch($queryParams['status']){
                case 'created':
                    return Alert::getSuccess('Paciente cadastrado com sucesso.');
                    break;
                case 'confirmed':
                    return Alert::getSuccess('Consulta confirmada com sucesso.');
                    break;
                case 'updated':
                    return Alert::getSuccess('Paciente actualizado com sucesso.');
                    break;
                case 'deleted':
                    return Alert::getSuccess('Paciente excluido com sucesso.');
                    break;
            }
        } 


        public static function getConsultas($request){
            if(Funcoes::Permition(5)){
                $content = ViewManager::render('dashboard/modules/consultas/consultas',[
                    'navbar'        => parent::getNavbar(),
                    'itens'         => self::getConsultasItens($request, $objPagination),
                    'pagination'    => parent::getPagination($request, $objPagination),
                    'status'        => self::getStatus($request)
                ]);
    
                return parent::getPainel('Centro-medico - Gestao de consultas', $content);
            }else{
                return ErrorController::getError($request);
            }  
        } 

    }
?>