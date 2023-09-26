<?php
    namespace App\Controller\Dashboard;
    use App\Controller\Dashboard\DashboardPageController;
    use App\Utils\ViewManager;
    use App\DatabaseManager\Pagination;
    use App\Model\Entity\EmailConfig as EntityEmail;
    use App\Model\Entity\SenhaConfig as EntitySenha;
    use App\Model\Entity\TelegramConfig as EntityTelegram;
    use App\Controller\Dashboard\ErrorController;
    use App\Utils\Funcoes;
    

    class DefinicoesController extends DashboardPageController{
        public static function setNewConfig($request){
            if(Funcoes::Permition(4)){
                $postVars = $request->getPostVars();
                $objEmailConfig = EntityEmail::getEmailConfigById(1);
                $objSenhaConfig = EntitySenha::getSenhaConfigById(1);
                $objTelegramConfig = EntityTelegram::getTelegramConfigById(1);
            
                if($postVars['text_email'] != ''){
                    if($objEmailConfig->codigo_config == ''){
                        if($postVars['text_ssl'] && $postVars['text_tls'] == false){
                            $objEmailConfig = new EntityEmail;
                            $objEmailConfig->email             = $postVars['text_email'];
                            $objEmailConfig->palavra_passe     = $postVars['text_password'];
                            $objEmailConfig->host_mail         = $postVars['text_host'];
                            $objEmailConfig->porta             = $postVars['text_porta'];
                            $objEmailConfig->p_ssl             = 1;
                            $objEmailConfig->p_tls             = 0;
                            $objEmailConfig->cadastrar();
        
                            $request->getRouter()->redirect('/definicoes?status=created');
        
                        }elseif($postVars['text_tls'] && $postVars['text_ssl'] == false){
                            $objEmailConfig = new EntityEmail;
                            $objEmailConfig->email             = $postVars['text_email'];
                            $objEmailConfig->palavra_passe     = $postVars['text_password'];
                            $objEmailConfig->host_mail         = $postVars['text_host'];
                            $objEmailConfig->porta             = $postVars['text_porta'];
                            $objEmailConfig->p_ssl             = 0;
                            $objEmailConfig->p_tls             = 1;
        
                            $objEmailConfig->cadastrar(); 
        
                            $request->getRouter()->redirect('/definicoes?status=created');
                        } elseif($postVars['text_ssl'] && $postVars['text_tls']){
                            $request->getRouter()->redirect('/definicoes?status=error');
                        }
                
                    }elseif($objEmailConfig->codigo_config == 1){
                        if($postVars['text_ssl'] && $postVars['text_tls'] == false){
                            $objEmailConfig = new EntityEmail;
                            $objEmailConfig->email             = $postVars['text_email'];
                            $objEmailConfig->palavra_passe     = $postVars['text_password'];
                            $objEmailConfig->host_mail         = $postVars['text_host'];
                            $objEmailConfig->porta             = $postVars['text_porta'];
                            $objEmailConfig->p_ssl             = 1;
                            $objEmailConfig->p_tls             = 0;
                            $objEmailConfig->actualizar();
            
                            $request->getRouter()->redirect('/definicoes?status=updated');
            
                        }elseif($postVars['text_tls'] && $postVars['text_ssl'] == false){
                            $objEmailConfig = new EntityEmail;
                            $objEmailConfig->email             = $postVars['text_email'];
                            $objEmailConfig->palavra_passe     = $postVars['text_password'];
                            $objEmailConfig->host_mail         = $postVars['text_host'];
                            $objEmailConfig->porta             = $postVars['text_porta'];
                            $objEmailConfig->p_ssl             = 0;
                            $objEmailConfig->p_tls             = 1;
            
                            $objEmailConfig->actualizar(); 
            
                            $request->getRouter()->redirect('/definicoes?status=updated');
                        } elseif($postVars['text_ssl'] && $postVars['text_tls']){
                            $request->getRouter()->redirect('/definicoes?status=error');
                        }
                    }
                }elseif($postVars['text_senha'] != ''){
                    if($objSenhaConfig->codigo_senha == ''){
                        if($postVars['text_senha'] == $postVars['text_senha_repeat'] ){
                            $objSenhaConfig = new EntitySenha;
                            $objSenhaConfig->senha         = $postVars['text_senha'];
                            $objSenhaConfig->criado_em     = date('Y-m-d H:i:s');
                            $objSenhaConfig->atualizado_em = date('Y-m-d H:i:s');

                            $objSenhaConfig->cadastrar();
                            $request->getRouter()->redirect('/definicoes?status=createdS');
                        }else{
                            $request->getRouter()->redirect('/definicoes?status=errorS');
                        }
                    }elseif($objSenhaConfig->codigo_senha == 1){
                        if($postVars['text_senha'] == $postVars['text_senha_repeat'] ){
                            $objSenhaConfig = new EntitySenha;
                            $objSenhaConfig->senha         = $postVars['text_senha'];
                            $objSenhaConfig->atualizado_em = date('Y-m-d H:i:s');
        
                            $objSenhaConfig->actualizar();
                            $request->getRouter()->redirect('/definicoes?status=updatedS');
                        }else{
                            $request->getRouter()->redirect('/definicoes?status=errorS');
                        }
                    }
                }elseif($postVars['text_token'] != ''){
                    if($objTelegramConfig->codigo_bot == ''){
                        $objTelegramConfig = new EntityTelegram;
                        $objTelegramConfig->bot_token         = $postVars['text_token'];
                        $objTelegramConfig->bot_username      = $postVars['text_username'];

                        $objTelegramConfig->cadastrar();
                        $request->getRouter()->redirect('/definicoes?status=createdB');
                    }elseif($objTelegramConfig->codigo_bot == 1){
                        $objTelegramConfig = new EntityTelegram;
                        $objTelegramConfig->bot_token         = $postVars['text_token'];
                        $objTelegramConfig->bot_username      = $postVars['text_username'];
        
                        $objTelegramConfig->actualizar();
                        $request->getRouter()->redirect('/definicoes?status=updatedB');
                    }
                }
            }else{
                return ErrorController::getError($request);
            }
        }
        
        
        private static function getStatus($request){
            $queryParams = $request->getQueryParams();
            if(!isset($queryParams['status'])) return '';
            switch($queryParams['status']){
                case 'created':
                    return Alert::getSuccess('Email cadastrado com sucesso.');
                    break;
                case 'updated':
                    return Alert::getSuccess('Email actualizada com sucesso.');
                    break;
                case 'deleted':
                    return Alert::getSuccess('Email excluido com sucesso.');
                    break;
                case 'createdS':
                    return Alert::getSuccess('Senha cadastrado com sucesso.');
                    break;
                case 'updatedS':
                    return Alert::getSuccess('Senha actualizada com sucesso.');
                    break;
                case 'deletedS':
                    return Alert::getSuccess('Senha excluido com sucesso.');
                    break;
                case 'createdB':
                    return Alert::getSuccess('Bot cadastrado com sucesso.');
                    break;
                case 'updatedB':
                    return Alert::getSuccess('Bot actualizada com sucesso.');
                    break;
                case 'deletedB':
                    return Alert::getSuccess('Bot excluido com sucesso.');
                    break;
                case 'error':
                    return Alert::getError('Escolha apenas um protocolo.');
                    break;
                case 'errorS':
                    return Alert::getError('A senha e a repeticao sao diferentes.');
                    break;
            }
        }

        public static function getDefinicoes($request){
            if(Funcoes::Permition(4)){
                $objEmailConfig     = EntityEmail::getEmailConfigById(1);
                $objSenhaConfig     = EntitySenha::getSenhaConfigById(1);
                $objTelegramConfig  = EntityTelegram::getTelegramConfigById(1);

                $checked_ssl = '';
                $checked_tls = '';

                if(empty($objEmailConfig->p_ssl)){
                    $checked_ssl = '';
                }elseif($objEmailConfig->p_ssl == 1){
                    $checked_ssl = 'checked';
                }
                    

                if(empty($objEmailConfig->p_tls)){
                    $checked_tls = '';
                }elseif($objEmailConfig->p_tls == 1){
                    $checked_tls = 'checked';
                }
                
                $content = ViewManager::render('dashboard/modules/definicoes/configuracoes', [
                    'navbar'            => parent::getNavbar(),
                    'email'             => $objEmailConfig->email ?? '',
                    'password'          => $objEmailConfig->palavra_passe ?? '',
                    'host'              => $objEmailConfig->host_mail ?? '',
                    'porta'             => $objEmailConfig->porta ?? '',
                    'ssl'               => $checked_ssl,
                    'tls'               => $checked_tls,
                    'senha'             => $objSenhaConfig->senha ?? '',
                    'senha_reset'       => $objSenhaConfig->senha ?? '',
                    'token'             => $objTelegramConfig->bot_token ?? '',
                    'username'          => $objTelegramConfig->bot_username ?? '',
                    'status'            => self::getStatus($request)
                ]);

                return parent::getPainel('Centro-medico - Definicoes', $content);
            }else{
                return ErrorController::getError($request);
            }  
        } 

    }
?>