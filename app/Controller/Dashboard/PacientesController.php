<?php
    namespace App\Controller\Dashboard;
    use App\Controller\Dashboard\DashboardPageController;
    use App\Utils\ViewManager;
    use App\DatabaseManager\Pagination;
    use App\Model\Entity\Paciente as EntityPaciente;
    use App\Model\Entity\TelegramConfig as EntityTelegram;
    use App\Controller\Dashboard\ErrorController;
    use App\Utils\Funcoes;

    class PacientesController extends DashboardPageController{
        //======================================================
        // Link with telegram
        // Aqui sera feita a verificacao do login via telegram
        //======================================================
        private static function getTelegramUserData(){
            if (isset($_GET['chat_id'])){
                $chat_id = $_GET['chat_id'];
                return $chat_id;
            }
            return false;
        }
        private static function getTelegram(){
            $dados_bot = EntityTelegram::getTelegramConfigById(1); 
            define('BOT_USERNAME', $dados_bot->bot_username);

            if(self::getTelegramUserData() !== false){
                $chat_id = self::getTelegramUserData();
                $html = '<p>Telegram Associado</p>';
            }else{
                $bot_username = BOT_USERNAME;
                $html = <<<HTML
                         <script async src="https://telegram.org/js/telegram-widget.js?2" data-telegram-login="{$bot_username}" data-size="large" data-radius="0" data-auth-url="{{URL}}/pacientes/cadastrar"></script>
                         HTML;
            }

            return $html;
        }



        private static function getPacientesItens($request, &$objPagination){
            $itens = '';
            $quantidadeTotal = EntityPaciente::getPacientes(null, null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;

            $queryParams = $request->getQueryParams();
            $paginaActual = $queryParams['page'] ?? 1;

            $objPagination = new Pagination($quantidadeTotal, $paginaActual, 8);

            $results = EntityPaciente::getPacientes(null, 'codigo_paciente', $objPagination->getLimit());

            While ($objPaciente = $results->fetchObject(EntityPaciente::class)){
                $itens .=ViewManager::render('dashboard/modules/pacientes/itens', [
                    'id'    => $objPaciente->codigo_paciente,
                    'nome'  => $objPaciente->nome_paciente,
                    'sexo'  => $objPaciente->genero,
                    'morada'=> $objPaciente->provincia
                ]);
            }
            return $itens;
        }

        public static function getNewPaciente($request){
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
                    'Telegram'              => self::getTelegram(),
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
        }

        private static function getStatus($request){
            $queryParams = $request->getQueryParams();
            
            if(!isset($queryParams['status'])) return '';

            switch($queryParams['status']){
                case 'created':
                    return Alert::getSuccess('Paciente cadastrado com sucesso.');
                    break;
                case 'updated':
                    return Alert::getSuccess('Paciente actualizado com sucesso.');
                    break;
                case 'deleted':
                    return Alert::getSuccess('Paciente excluido com sucesso.');
                    break;
            }
        } 


        public static function getPacientes($request){
            if(Funcoes::Permition(5)){
                $content = ViewManager::render('dashboard/modules/pacientes/pacientes',[
                    'navbar'        => parent::getNavbar(),
                    'itens'         => self::getPacientesItens($request, $objPagination),
                    'pagination'    => parent::getPagination($request, $objPagination),
                    'status'        => self::getStatus($request)
                ]);
    
                return parent::getPainel('Centro-medico - Pacientes', $content);
            }else{
                return ErrorController::getError($request);
            }  
        } 

    }
?>