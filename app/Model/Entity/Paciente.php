<?php
    namespace App\Model\Entity;
    use App\DatabaseManager\Database;

    class Paciente{
        public $codigo_paciente;
        public $nome_paciente;                    
        public $estado_civil;                  
        public $genero;                       
        public $provincia;                    
        public $distrito;                      
        public $bairro;                                                  
        public $telefone;                      
        public $email;                          
        public $data_nascimento;
        public $id_chat; 
        public $criado_em;  
        public $atualizado_em;
        
        public  function cadastrar(){
            $this->$codigo_paciente = (new Database('pacientes'))->insert([
                'nome_paciente'         =>  $this->nome_paciente,
                'estado_civil'          =>  $this->estado_civil,
                'genero'                =>  $this->genero,
                'provincia'             =>  $this->provincia,
                'distrito'              =>  $this->distrito,
                'bairro'                =>  $this->bairro,
                'telefone'              =>  $this->telefone,
                'email'                 =>  $this->email,
                'data_nascimento'       =>  $this->data_nascimento,
                'criado_em'             =>  $this->criado_em,
                'atualizado_em'         =>  $this->atualizado_em,
                'id_chat'               =>  $this->id_chat
            ]);
            return true;
        }

        public static function getPacientes($where = null, $order = null, $limit = null, $fields = "*"){
            return (new Database('pacientes'))->select($where, $order, $limit, $fields);
        }

        public static function getPacienteById($id){
            return self::getPacientes('codigo_paciente = '.$id)->fetchObject(self::class);
        }

        public  function actualizar(){
            return (new Database('pacientes'))->update('codigo_paciente = '.$this->codigo_paciente, [
                'nome_paciente'         =>  $this->nome_paciente,
                'estado_civil'          =>  $this->estado_civil,
                'genero'                =>  $this->genero,
                'provincia'             =>  $this->provincia,
                'distrito'              =>  $this->distrito,
                'bairro'                =>  $this->bairro,
                'telefone'              =>  $this->telefone,
                'email'                 =>  $this->email,
                'data_nascimento'       =>  $this->data_nascimento,
                'atualizado_em'         =>  $this->atualizado_em,
                'id_chat'               =>  $this->id_chat
            ]);
        }

       /* 
        public  function excluir(){
            return (new Database('pacientes'))->delete('codigo_paciente = '.$this->codigo_paciente);
        }
        */
    }

?>