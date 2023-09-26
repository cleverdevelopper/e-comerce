<?php
    namespace App\Model\Entity;
    use App\DatabaseManager\Database;

    class Especialidade{
        public $codigo_especialidade;
        public $nome_especialidade;                    
        
        public  function cadastrar(){
            $this->$codigo_especialidade = (new Database('especialidade'))->insert([
                'nome_especialidade'           =>  $this->nome_especialidade
            ]);
            return true;
        }

        public static function getEspecialidades($where = null, $order = null, $limit = null, $fields = "*"){
            return (new Database('especialidade'))->select($where, $order, $limit, $fields);
        }

        public static function getEspecialidadeById($id){
            return self::getEspecialidades('codigo_especialidade = '.$id)->fetchObject(self::class);
        }

        public  function actualizar(){
            return (new Database('especialidade'))->update('codigo_especialidade = '.$this->codigo_especialidade, [
                'nome_especialidade'           =>  $this->nome_especialidade
            ]);
        }

    }
?>