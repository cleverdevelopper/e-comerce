<?php
    namespace App\Controller\Dashboard;
    use App\Controller\Dashboard\DashboardPageController;
    use App\Utils\ViewManager;
    use App\DatabaseManager\Pagination;
    use App\Model\Entity\Consulta as EntityConsulta;
    use App\Model\Entity\Paciente as EntityPaciente;
    use App\Model\Entity\Funcionario as EntityFuncionario;
    use App\Model\Entity\Diagnostico as EntityDiagnostico;
    use App\Controller\Dashboard\ErrorController;
    use App\Utils\Funcoes;

    class MyConsultasController extends DashboardPageController{
        private static function getPacienteName($id){
            $objPaciente = EntityPaciente::getPacienteById($id);
            
            return $objPaciente->nome_paciente;
        }

        private static function getFuncionarioName($id){
            $objFuncionario = EntityFuncionario::getFuncionarioById($id);
            
            return $objFuncionario->nome_funcionario;

        }

        private static function getDiagnosticoItens($request){
            $itens = '';
           
            $results = EntityDiagnostico::getDiagnostico(null, 'codigo_diagnostico', null);

            While ($objDiagnostico = $results->fetchObject(EntityDiagnostico::class)){
                $itens .=ViewManager::render('dashboard/modules/consultas/diagnostico', [
                    'id'                => $objDiagnostico->codigo_diagnostico,
                    'diagnostico'       => $objDiagnostico->diagnostico
                ]);
            }
            return $itens;
        }

        private static function getConsultasItens($request, &$objPagination){
            $itens = '';
            $quantidadeTotal = EntityConsulta::getConsultas(null, null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;

            $queryParams = $request->getQueryParams();
            $paginaActual = $queryParams['page'] ?? 1;

            $objPagination = new Pagination($quantidadeTotal, $paginaActual, 8);
            $condition = $_SESSION['admin']['utilizador']['id'];

            $results = EntityConsulta::getConsultas('codigo_funcionario =  '.$condition, 'codigo_consulta', $objPagination->getLimit());
            

            While ($objConsuta = $results->fetchObject(EntityPaciente::class)){
                $itens .=ViewManager::render('dashboard/modules/consultas/myitems', [
                    'id'                => $objConsuta->codigo_consulta,
                    'nome'              => self::getPacienteName($objConsuta->codigo_paciente),
                    'data_consulta'     => $objConsuta->data_consulta,
                    'horario_consulta'  => $objConsuta->horario_agendado
                ]);
            }
            return $itens;
        }


        private static function getHistoryItens($request, $id, &$objPagination){
            $itens = '';
            $quantidadeTotal = EntityConsulta::getConsultas(null, null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;

            $queryParams = $request->getQueryParams();
            $paginaActual = $queryParams['page'] ?? 1;

            $objPagination = new Pagination($quantidadeTotal, $paginaActual, 8);
            $condition = 'is not null';

            $results = EntityConsulta::getConsultas('diagnostico '.$condition .' and codigo_paciente = '.$id, 'codigo_consulta DESC', $objPagination->getLimit());
            

            While ($objConsuta = $results->fetchObject(EntityPaciente::class)){
                $Paciente = EntityPaciente::getPacienteById($objConsuta->codigo_paciente);
                $itens .=ViewManager::render('dashboard/modules/consultas/historico', [
                    'id'                => $objConsuta->codigo_consulta,
                    'nome'              => $Paciente->nome_paciente,
                    'idade'             => $Paciente->data_nascimento,
                    'genero'            => $Paciente->genero,
                    'medico'            => self::getFuncionarioName($objConsuta->codigo_funcionario)
                ]);
            }
            return $itens;
        }

        public static function getConsultar($request, $id){
            if(Funcoes::Permition(5)){
                $objConsuta = EntityConsulta::getConsultaById($id);
            
                if(!$objConsuta instanceof EntityConsulta){
                    $request->getRouter()->redirect('/consultas');
                }
                $Paciente = EntityPaciente::getPacienteById($objConsuta->codigo_paciente);
                $idade =  (int)date('Y-m-d') - (int)$Paciente->data_nascimento;
                $content = ViewManager::render('dashboard/modules/consultas/consultar',[
                    'navbar'                => parent::getNavbar(),
                    'nome_paciente'         => $Paciente->nome_paciente,
                    'idade'                 => $idade,
                    'genero'                => $Paciente->genero,
                    'sintomas'              => $objConsuta->sintomas,
                    'button'                => 'Confirmar',
                    'historico'             => self::getHistoryItens($request, $objConsuta->codigo_paciente, $objPagination),
                    'diagnostico__item'     => self::getDiagnosticoItens($request, $objPagination)
                    
                ]);
    
                return parent::getPainel('Centro-medico - Consultar', $content);
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


        public static function getMyConsultas($request){
            if(Funcoes::Permition(5)){

                $content = ViewManager::render('dashboard/modules/consultas/consultas',[
                    'navbar'        => parent::getNavbar(),
                    'itens'         => self::getConsultasItens($request, $objPagination),
                    'pagination'    => parent::getPagination($request, $objPagination),
                    'status'        => self::getStatus($request), 
                ]);
    
                return parent::getPainel('Centro-medico - Consultas', $content);
            }else{
                return ErrorController::getError($request);
            }  
        } 

    }
?>