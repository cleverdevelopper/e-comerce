<?php
    namespace App\Model\Entity;
    use App\DatabaseManager\Database;

    class Consulta{
        public $codigo_consulta;
        public $data_consulta;
        public $horario_agendado;
        public $diagnostico; 
        public $observacoes;
        public $sintomas;
        public $codigo_paciente;
        public $codigo_funcionario;
        
        public  function cadastrar(){
            $this->$codigo_consulta = (new Database('consultas'))->insert([
                'data_consulta'          =>  $this->data_consulta,
                'horario_agendado'       =>  $this->horario_agendado,
                'diagnostico'            =>  $this->diagnostico,
                'observacoes'            =>  $this->observacoes,
                'sintomas'               =>  $this->sintomas,
                'codigo_paciente'        =>  $this->codigo_paciente,
                'codigo_funcionario'     =>  $this->codigo_funcionario
            ]);
            return true;
        }

        public static function getConsultas($where = null, $order = null, $limit = null, $fields = "*"){
            return (new Database('consultas'))->select($where, $order, $limit, $fields);
        }

        public static function getConsultaById($id){
            return self::getConsultas('codigo_consulta = '.$id)->fetchObject(self::class);
        }

        public  function actualizar(){
            return (new Database('consultas'))->update('codigo_consulta = '.$this->codigo_consulta, [
                'data_consulta'          =>  $this->data_consulta,
                'horario_agendado'       =>  $this->horario_agendado,
                'diagnostico'            =>  $this->diagnostico,
                'observacoes'            =>  $this->observacoes,
                'sintomas'               =>  $this->sintomas,
                'codigo_paciente'        =>  $this->codigo_paciente,
                'codigo_funcionario'     =>  $this->codigo_funcionario
            ]);
        }

       /* 
        public  function excluir(){
            return (new Database('pacientes'))->delete('codigo_paciente = '.$this->codigo_paciente);
        }
        */
    }

?>