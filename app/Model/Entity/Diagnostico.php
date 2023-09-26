<?php
    namespace App\Model\Entity;
    use App\DatabaseManager\Database;

    class Diagnostico{
        public $codigo_diagnostico;
        public $diagnostico;                    
        
        public  function cadastrar(){
            $this->codigo_diagnostico = (new Database('diagnostico'))->insert([
                'diagnostico'           =>  $this->diagnostico
            ]);
            return true;
        }

        public static function getDiagnostico($where = null, $order = null, $limit = null, $fields = "*"){
            return (new Database('diagnostico'))->select($where, $order, $limit, $fields);
        }

        public static function getDiagnosticoById($id){
            return self::getDiagnostico('codigo_diagnostico = '.$id)->fetchObject(self::class);
        }

        public  function actualizar(){
            return (new Database('diagnostico'))->update('codigo_diagnostico = '.$this->codigo_diagnostico, [
                'diagnostico'           =>  $this->diagnostico
            ]);
        }

    }
?>