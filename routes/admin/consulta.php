<?php
    use App\Http\Response;
    use App\Controller\Dashboard\ConsultasController;
    use App\Controller\Dashboard\MyConsultasController;

    $objRouter->get('/consultas', [
        'middlewares'   => [
            'requere-admin-login'
        ],
        function ($request){
            return new Response(200, ConsultasController::getConsultas($request));
        }
    ]);

    $objRouter->get('/consultas/{id}/confirmar', [
        'middlewares'   => [
            'requere-admin-login'
        ],
        function ($request, $id){
            return new Response(200, ConsultasController::getConfirmation($request, $id));
        }
    ]);

    $objRouter->post('/consultas/{id}/confirmar', [
        'middlewares'   => [
            'requere-admin-login'
        ],
        function ($request, $id){
            return new Response(200, ConsultasController::setConfirmation($request, $id));
        }
    ]);


    $objRouter->get('/myconsultas', [
        'middlewares'   => [
            'requere-admin-login'
        ],
        function ($request){
            return new Response(200, MyConsultasController::getMyConsultas($request));
        }
    ]);

    $objRouter->get('/myconsultas/{id}/consultar', [
        'middlewares'   => [
            'requere-admin-login'
        ],
        function ($request, $id){
            return new Response(200, MyConsultasController::getConsultar($request, $id));
        }
    ]);

    /*$objRouter->post('/pacientes/cadastrar', [
        'middlewares'   => [
            'requere-admin-login'
        ],
        function ($request){
            return new Response(200, PacientesController::setNewPaciente($request));
        }
    ]);

    $objRouter->get('/pacientes/{id}/edit', [
        'middlewares'   => [
            'requere-admin-login'
        ],
        function ($request, $id){
            return new Response(200, PacientesController::getEditPaciente($request, $id));
        }
    ]);

    $objRouter->post('/pacientes/{id}/edit', [
        'middlewares'   => [
            'requere-admin-login'
        ],
        function ($request, $id){
            return new Response(200, PacientesController::setEditPaciente($request, $id));
        }
    ]);


    $objRouter->get('/pacienteslist', [
        'middlewares'   => [
            'requere-admin-login'
        ],
        function ($request){
            return new Response(200, AutoComplete::getPacientesJson($request));
        }
    ]);*/
?>