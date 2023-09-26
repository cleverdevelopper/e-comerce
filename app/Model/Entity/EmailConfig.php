<?php
    namespace App\Model\Entity;
    use App\DatabaseManager\Database;

    class EmailConfig{
        public $codigo_config;                
        public $email;                        
        public $palavra_passe;  
        public $host_mail;      
        public $porta;       
        public $p_ssl;     
        public $p_tls;

        public  function cadastrar(){
            $this->$codigo_config = (new Database('mail_config'))->insert([
                'email'             =>  $this->email,
                'palavra_passe'     =>  $this->palavra_passe,
                'host_mail'         =>  $this->host_mail,
                'porta'             =>  $this->porta,
                'p_ssl'             =>  $this->p_ssl,
                'p_tls'             =>  $this->p_tls
            ]);
            return true;
        }

        public static function getEmailConfig($where = null, $order = null, $limit = null, $fields = "*"){
            return (new Database('mail_config'))->select($where, $order, $limit, $fields);
        }

        public static function getEmailConfigById($id){
            return self::getEmailConfig('codigo_config = '.$id)->fetchObject(self::class);
        }

        public  function actualizar(){
            return (new Database('mail_config'))->update('codigo_config = '.$this->codigo_config, [
                'email'             =>  $this->email,
                'palavra_passe'     =>  $this->palavra_passe,
                'host_mail'         =>  $this->host_mail,
                'porta'             =>  $this->porta,
                'p_ssl'             =>  $this->p_ssl,
                'p_tls'             =>  $this->p_tls
            ]);
        }

    }
?>