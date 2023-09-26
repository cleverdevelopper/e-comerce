<?php
    namespace App\Controller\TelegramBot;
    use App\Model\Entity\TelegramConfig as EntityTelegram;

    class CheckAuthorization{
        $dados_bot = EntityTelegram::getTelegramConfigById(1); 
        define('BOT_TOKEN', $dados_bot->bot_token);  
        
        function checkTelegramAuthorization($auth_data) {
            $check_hash = $auth_data['hash'];
            unset($auth_data['hash']);
            $data_check_arr = [];
            foreach ($auth_data as $key => $value) {
                $data_check_arr[] = $key.'='.$value;
            }
            
            //ignorando o primeiro elemento do link
            $link_pagina = array_shift($data_check_arr);
            $link_pag2 = array_shift($data_check_arr);
            $link_pag3 = array_shift($data_check_arr);
            sort($data_check_arr);
            $data_check_string = implode("\n", $data_check_arr);
            $secret_key = hash('sha256', BOT_TOKEN, true);
            $hash = hash_hmac('sha256', $data_check_string, $secret_key);
            if (strcmp($hash, $check_hash) !== 0) {
                throw new Exception('Data is NOT from Telegram');
            }
            if ((time() - $auth_data['auth_date']) > 86400) {
                throw new Exception('Data is outdated');
            }
            return $auth_data;
        }
        
        function saveTelegramUserData($auth_data) {
          $auth_data_json = json_encode($auth_data);
          $auth_data_decode = json_decode($auth_data_json, true);
          return $auth_data_decode;
          //setcookie('tg_user', $auth_data_json);
        }
        
        try {
          $auth_data = checkTelegramAuthorization($_GET);
          $tg_user = saveTelegramUserData($auth_data);
        
          $chat_id = htmlspecialchars($tg_user['id']);
        
        } catch (Exception $e) {
          die ($e->getMessage());
        }
        
        header("Location: $set->url?a=telegram&date=$data&time=$time&chat_id=$chat_id");

    }
?>
    