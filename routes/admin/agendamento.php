<?php
    use App\Http\Response;
    use App\Controller\Dashboard\AgendamentoController;

    $objRouter->get('/agendamento', [
        'middlewares'   => [
            'requere-admin-login'
        ],
        function ($request){
            return new Response(200, AgendamentoController::getAgendamento($request));
        }
    ]);


    $objRouter->get('/agendar/{date}', [
        'middlewares'   => [
            'requere-admin-login'
        ],
        function ($request, $date){
            return new Response(200, AgendamentoController::getAgendar($request, $date));
        }
    ]);

    $objRouter->get('/telegram/{date}/{datetime}', [
        'middlewares'   => [
            'requere-admin-login'
        ],
        function ($request, $date, $datetime){
            return new Response(200,  AgendamentoController::getPacienteAgendamento($request, $date, $datetime));
        }
    ]);

    $objRouter->post('/telegram/{date}/{datetime}', [
        'middlewares'   => [
            'requere-admin-login'
        ],
        function ($request, $date, $datetime){
            return new Response(200,  AgendamentoController::setNewAgendamento($request, $date, $datetime));
        }
    ]);

    /*$objRouter->get('/funcionario/cadastrar', [
        'middlewares'   => [
            'requere-admin-login'
        ],
        function ($request){
            return new Response(200, FuncionarioController::getNewFuncionario($request));
        }
    ]);

    $objRouter->post('/funcionario/cadastrar', [
        'middlewares'   => [
            'requere-admin-login'
        ],
        function ($request){
            return new Response(200, FuncionarioController::setNewFuncionario($request));
        }
    ]);

    $objRouter->get('/funcionario/{id}/edit', [
        'middlewares'   => [
            'requere-admin-login'
        ],
        function ($request, $id){
            return new Response(200, FuncionarioController::getEditFuncionario($request, $id));
        }
    ]);

    $objRouter->post('/pacientes/{id}/edit', [
        'middlewares'   => [
            'requere-admin-login'
        ],
        function ($request, $id){
            return new Response(200, PacientesController::setEditPaciente($request, $id));
        }
    ]);*/
?>