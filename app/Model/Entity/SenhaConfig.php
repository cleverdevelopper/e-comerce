<?php
    namespace App\Model\Entity;
    use App\DatabaseManager\Database;

    class SenhaConfig{
        public $codigo_senha;                
        public $senha;                        
        public $criado_em;  
        public $atualizado_em;      


        public  function cadastrar(){
            $this->$codigo_senha = (new Database('senha_reset'))->insert([
                'senha'             =>  $this->senha,
                'criado_em'         =>  $this->criado_em,
                'atualizado_em'     =>  $this->atualizado_em
            ]);
            return true;
        }

        public static function getSenhaConfig($where = null, $order = null, $limit = null, $fields = "*"){
            return (new Database('senha_reset'))->select($where, $order, $limit, $fields);
        }

        public static function getSenhaConfigById($id){
            return self::getSenhaConfig('codigo_senha = '.$id)->fetchObject(self::class);
        }

        public  function actualizar(){
            return (new Database('senha_reset'))->update('codigo_senha = '.$this->codigo_senha, [
                'senha'             =>  $this->senha,
                'criado_em'         =>  $this->criado_em,
                'atualizado_em'     =>  $this->atualizado_em
            ]);
        }

    }
?>