<?php
    namespace App\Controller\Dashboard;
    use App\Controller\Dashboard\DashboardPageController;
    use App\Utils\ViewManager;
    use App\DatabaseManager\Pagination;
    use App\Model\Entity\Paciente as EntityPaciente;
    use App\Model\Entity\Consulta as EntityConsulta;
    use App\Controller\Dashboard\ErrorController;
    use App\Utils\Funcoes;

    class AgendamentoController extends DashboardPageController{
        private static function build_calendar($month, $year){
            $daysOfWeek = array('Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado');

            if (isset($_GET['month'])) {
                $month = $_GET['month'];
            }
            
            $firstDayOfMonth = mktime(0, 0, 0, $month, 1, $year);
            $numberDays = date('t', $firstDayOfMonth);

            $dateComponents = getdate($firstDayOfMonth);

            $monthName = $dateComponents['month'];

            $dayOfWeek = $dateComponents['wday'];

            $datetoday = date('Y-m-d');
            $calendar = "<table width='100%'>";
            $calendar .= "<center><h2 class='agendamento__title'>$monthName $year</h2>";

            $calendar .= "<div class='add' style='displey: flex; justify-content: center; '><a style='margin-right: 1rem; font-weight: 500;' href='?month=" . date('m', mktime(0, 0, 0, $month - 1, 1, $year)) . "&year=" . date('Y', mktime(0, 0, 0, $month - 1, 1, $year)) . "'>Mes Passado</a> ";

            $calendar .= "<a href='".URL."/agendamento' style='margin-right: 1rem; font-weight: 500;' data-month='" . date('m') . "' data-year='" . date('Y') . "'>Corrente Mes</a> ";

            $calendar .= "<a style='margin-right: 1rem; font-weight: 500;' href='?month=" . date('m', mktime(0, 0, 0, $month + 1, 1, $year)) . "&year=" . date('Y', mktime(0, 0, 0, $month + 1, 1, $year)) . "' class='passado_btn'>Proximo Mes</a></div></center><br>";

            $calendar .= "<thead>";
            $calendar .= "<tr>";
            
            foreach ($daysOfWeek as $day) {
                $calendar .= "<th class='header'>$day</th>";
            }
        
            $currentDay = 1;
            $calendar .= "</tr></thead><tr>";
            
            if ($dayOfWeek > 0) {
                for ($k = 0; $k < $dayOfWeek; $k++) {
                    $calendar .= "<td class='empty'></td>";
                }
            }

            $month = str_pad($month, 2, "0", STR_PAD_LEFT);
            while ($currentDay <= $numberDays) {
                if ($dayOfWeek == 7) {
                    $dayOfWeek = 0;
                    $calendar .= "</tr><tr>";
                }
                $currentDayRel = str_pad($currentDay, 2, "0", STR_PAD_LEFT);
                $date = "$year-$month-$currentDayRel";
                $dayname = strtolower(date('l', strtotime($date)));
                $eventNum = 0;
                $today = $date == date('Y-m-d') ? "today" : "";
                if ($date < date('Y-m-d')) {
                    $calendar .= "<td><h4>$currentDay</h4> <button class='inagendavel__btn'>N / A</button>";
                } else {
                    $calendar .= "<td class='$today'><h4>$currentDay</h4> <a href='".URL."/agendar/" . $date . "' class='prosseguir__btn'>Agendar</a>";
                }

                $calendar .= "</td>";
            
                $currentDay++;
                $dayOfWeek++;
            }
    
            if ($dayOfWeek != 7) {
                $remainingDays = 7 - $dayOfWeek;
                for ($l = 0; $l < $remainingDays; $l++) {
                    $calendar .= "<td class='empty'></td>";
                }
            }
            $calendar .= "</tr>";
            $calendar .= "</table>";
           return $calendar;
        }

        private static function timeslots($duration, $cleanup, $inicio, $fim){
            $start = new \DateTime($inicio);
            $end = new \DateTime($fim);
            $interval = new \DateInterval("PT" . $duration . "M");
            $cleanupInterval = new \DateInterval("PT" . $cleanup . "M");
            $slots = array();

            for ($intStart = $start; $intStart < $end; $intStart->add($interval)->add($cleanupInterval)) {
                $endPeriod = clone $intStart;
                $endPeriod->add($interval);
                if ($endPeriod > $end) {
                    break;
                }
                $slots[] = $intStart->format("H:iA") . " - " . $endPeriod->format("H:iA");
            }
            return $slots;
        }


        private static function getAgendarItens($request, $duration, $cleanup, $start, $end, $date){
            $itens = '';
            $bookings = array();
            $data = "'$date'";
            
            $results = EntityConsulta::getConsultas('data_consulta = '.$data, 'codigo_consulta', null);
           
            $i = 0;
            While ($objConsulta = $results->fetchObject(EntityConsulta::class)){
                $bookings[$i] = $objConsulta->horario_agendado;
                $i += 1;
            }

            $timeslots = self::timeslots($duration, $cleanup, $start, $end);
            foreach ($timeslots as $ts) {
                if(in_array($ts, $bookings)){
                    $itens .=ViewManager::render('dashboard/modules/agendamentos/itens', [
                        'time'          => $ts,
                        'style'         => 'red',
                        'mmodal'        => ''
                    ]);
                }else{
                    $itens .=ViewManager::render('dashboard/modules/agendamentos/book', [
                        'time'          => $ts,
                        'date'          => $date,
                        'timeslot'      => $ts,
                        'style'         => 'green',
                        'modal'         => 'myBtn'
                    ]);
                }
            }
            return $itens;
        }


        public static function getPacienteAgendamento($request, $date, $datetime){
            if(Funcoes::Permition(8)){
                //criacao do horario de agendamento
                $hora = substr($datetime, 0, 7);
                $minutos = substr($datetime, 14, 21);
                $time = $hora." - ".$minutos;

                $content = ViewManager::render('dashboard/modules/agendamentos/finalizar',[
                    'navbar'            => parent::getNavbar(),
                    'indicate'          => 'Finalizar',
                    'data_consulta'     => $date,
                    'horario'           => $time,
                    'button'            => 'Agendar',
                    'status'            => self::getStatus($request)
                ]);

                return parent::getPainel('Centro-medico - Agendar', $content);
            }else{
                return ErrorController::getError($request);
            }
            
        }


        public static function setNewAgendamento($request){
            if(Funcoes::Permition(8)){
                $postVars = $request->getPostVars();

                $objConsulta = new EntityConsulta;
                $objConsulta->codigo_paciente             = $postVars['codigo_paciente'];
                $objConsulta->data_consulta               = $postVars['data_agendamento'];
                $objConsulta->horario_agendado            = $postVars['horario_agendamento'];
                $objConsulta->sintomas                    = $postVars['sintomas'];
                
                $objConsulta->cadastrar();

                $request->getRouter()->redirect('/agendamento?status=booked');
            }else{
                return ErrorController::getError($request);
            }
        }

        private static function getStatus($request){
            $queryParams = $request->getQueryParams();
            
            if(!isset($queryParams['status'])) return '';

            switch($queryParams['status']){
                case 'created':
                    return Alert::getSuccess('Paciente cadastrado com sucesso.');
                    break;
                case 'updated':
                    return Alert::getSuccess('Paciente actualizada com sucesso.');
                    break;
                case 'deleted':
                    return Alert::getSuccess('Paciente excluido com sucesso.');
                    break;
                case 'booked':
                    return Alert::getSuccess('Consulta agendada com sucesso.');
                    break;
            }
        } 
        public static function getAgendar($request, $date){
            if(Funcoes::Permition(8)){
                $duration = 15;
                $cleanup = 0;
                $start = "09:00";
                $end = "15:00";
                $mes = '';

                $content = ViewManager::render('dashboard/modules/agendamentos/agendar',[
                    'navbar'            => parent::getNavbar(),
                    'dia'               => $date,
                    'timestamp'         => self::getAgendarItens($request, $duration, $cleanup, $start, $end, $date),
                    'status'            => self::getStatus($request)
                ]);

                return parent::getPainel('Centro-medico - Agendar', $content);
            }else{
                return ErrorController::getError($request);
            }
        } 


        public static function getAgendamento($request){
            if(Funcoes::Permition(8)){
                $dateComponents = getdate();
                $month = $dateComponents['mon'];
                $year = $dateComponents['year'];

                $content = ViewManager::render('dashboard/modules/agendamentos/calendario',[
                    'navbar'            => parent::getNavbar(),
                    'calendar'          => self::build_calendar($month, $year),
                    'status'            => self::getStatus($request)
                ]);

                return parent::getPainel('Centro-medico - Agendamento', $content);
            }else{
                return ErrorController::getError($request);
            }
        } 
    }
?>