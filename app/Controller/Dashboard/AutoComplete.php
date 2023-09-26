<?php
    namespace App\Controller\Dashboard;
    use App\Model\Entity\Paciente as EntityPaciente;
    use App\Controller\Dashboard\ErrorController;
    use App\Utils\Funcoes;

    class AutoComplete {
        public static function getPacientesJson($request){
            if(Funcoes::Permition(8)){
                $results = '';
                $json_array = array();
                $results = EntityPaciente::getPacientes(null, 'codigo_paciente',null);
    
                While ($objPaciente = $results->fetchObject(EntityPaciente::class)){
                   $json_array[] = $objPaciente;
                }
                return json_encode($json_array);
            }else{
                return ErrorController::getError($request);
            } 
        }
    }
?>