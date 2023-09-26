<?php
    namespace App\Model\Entity\LoginEntity;
    use App\DatabaseManager\Database;

    class Utilizador{
        public $id_utilizador;
        public $nome_completo;
        public $utilizador;
        public $palavra_passe;

        public  function cadastrar(){
            $this->id_utilizador = (new Database('utilizadores'))->insert([
                'nome_completo'      =>  $this->nome_completo,
                'utilizador'         =>  $this->utilizador,
                'palavra_passe'      =>  $this->palavra_passe
            ]);

            return true;
        }

        public  function actualizar(){
            return (new Database('utilizadores'))->update('id_utilizador = '.$this->id_utilizador, [
                'nome_completo'      =>  $this->nome_completo,
                'utilizador'         =>  $this->utilizador,
                'palavra_passe'      =>  $this->palavra_passe
            ]);
        }

        public  function excluir(){
            return (new Database('utilizadores'))->delete('id_utilizador = '.$this->id_utilizador);
        }


        
        public static function getUserByUsername($utilizador){
            return (new Database('utilizadores')) -> select('utilizador = "'.$utilizador.'"')->fetchObject(self::class);
        }

        public static function getUsers($where = null, $order = null, $limit = null, $fields = "*"){
            return (new Database('utilizadores'))->select($where, $order, $limit, $fields);
        }

        public static function getUserById($id){
            return self::getUsers('id_utilizador = '.$id)->fetchObject(self::class);
        }
    }
?>