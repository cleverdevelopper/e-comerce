<?php
    namespace App\Model\Entity;
    use App\DatabaseManager\Database;

    class Funcionario{
        public $codigo_funcionario; 
        public $nome_funcionario; 
        public $sexo;
        public $especialidade;
        public $formacao;
        public $bairro; 
        public $rua;
        public $provincia;
        public $distrito;
        public $telefone;
        public $email;
        public $utilizador; 
        public $palavra_passe;
        public $criado_em;
        public $atualizado_em;
        public $grupos;
        
        public  function cadastrar(){
            $this->$codigo_funcionario = (new Database('funcionario'))->insert([
                'nome_funcionario'          =>  $this->nome_funcionario,
                'sexo'                      =>  $this->sexo,
                'especialidade'             =>  $this->especialidade,
                'formacao'                  =>  $this->formacao,
                'bairro'                    =>  $this->bairro,
                'rua'                       =>  $this->rua,
                'provincia'                 =>  $this->provincia,
                'distrito'                  =>  $this->distrito,
                'telefone'                  =>  $this->telefone,
                'email'                     =>  $this->email,
                'utilizador'                =>  $this->utilizador,
                'palavra_passe'             =>  $this->palavra_passe,
                'criado_em'                 =>  $this->criado_em,
                'atualizado_em'             =>  $this->atualizado_em,
                'grupos'                    =>  $this->grupos
            ]);
            return true;
        }

        public static function getFuncionarios($where = null, $order = null, $limit = null, $fields = "*"){
            return (new Database('funcionario'))->select($where, $order, $limit, $fields);
        }

        public static function getFuncionarioById($id){
            return self::getFuncionarios('codigo_funcionario = '.$id)->fetchObject(self::class);
        }

        public  function actualizar(){
            return (new Database('funcionario'))->update('codigo_funcionario = '.$this->codigo_funcionario, [
                'nome_funcionario'          =>  $this->nome_funcionario,
                'sexo'                      =>  $this->sexo,
                'especialidade'             =>  $this->especialidade,
                'formacao'                  =>  $this->formacao,
                'bairro'                    =>  $this->bairro,
                'rua'                       =>  $this->rua,
                'provincia'                 =>  $this->provincia,
                'distrito'                  =>  $this->distrito,
                'telefone'                  =>  $this->telefone,
                'email'                     =>  $this->email,
                'utilizador'                =>  $this->utilizador,
                'palavra_passe'             =>  $this->palavra_passe,
                'criado_em'                 =>  $this->criado_em,
                'atualizado_em'             =>  $this->atualizado_em,
                'grupos'                    =>  $this->grupos
            ]);
        }

       /* 
        public  function excluir(){
            return (new Database('pacientes'))->delete('codigo_paciente = '.$this->codigo_paciente);
        }
        */
    }

?>