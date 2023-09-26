<?php
    namespace App\Model\Entity;
    use App\DatabaseManager\Database;

    class Grupos{
        public $codigo_grupo;
        public $descricao;                    
        public $permissoes;  
        
        public  function cadastrar(){
            $this->$codigo_grupo = (new Database('grupos'))->insert([
                'descricao'           =>  $this->descricao,
                'permissoes'          =>  $this->permissoes
            ]);
            return true;
        }

        public static function getGrupos($where = null, $order = null, $limit = null, $fields = "*"){
            return (new Database('grupos'))->select($where, $order, $limit, $fields);
        }

        public static function getGrupoById($id){
            return self::getGrupos('codigo_grupo = '.$id)->fetchObject(self::class);
        }

        public  function actualizar(){
            return (new Database('grupos'))->update('codigo_grupo = '.$this->codigo_grupo, [
                'descricao'           =>  $this->descricao,
                'permissoes'          =>  $this->permissoes
            ]);
        }

    }
?>