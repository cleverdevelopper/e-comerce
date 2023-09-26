<?php
    namespace App\Model\Entity;
    use App\DatabaseManager\Database;

    class TelegramConfig{
        public $codigo_bot;                
        public $bot_token;                        
        public $bot_username;  
     


        public  function cadastrar(){
            $this->$codigo_bot = (new Database('telegram_bot'))->insert([
                'bot_token'             =>  $this->bot_token,
                'bot_username'          =>  $this->bot_username
            ]);
            return true;
        }

        public static function getTelegramConfig($where = null, $order = null, $limit = null, $fields = "*"){
            return (new Database('telegram_bot'))->select($where, $order, $limit, $fields);
        }

        public static function getTelegramConfigById($id){
            return self::getTelegramConfig('codigo_bot = '.$id)->fetchObject(self::class);
        }

        public  function actualizar(){
            return (new Database('telegram_bot'))->update('codigo_bot = '.$this->codigo_bot, [
                'bot_token'             =>  $this->bot_token,
                'bot_username'          =>  $this->bot_username
            ]);
        }

    }
?>