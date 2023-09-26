<?php
    use App\Http\Response;
    use App\Controller\Dashboard\PacientesController;
    use App\Controller\Dashboard\AutoComplete;

    $objRouter->get('/pacientes', [
        'middlewares'   => [
            'requere-admin-login'
        ],
        function ($request){
            return new Response(200, PacientesController::getPacientes($request));
        }
    ]);

    $objRouter->get('/pacientes/cadastrar', [
        'middlewares'   => [
            'requere-admin-login'
        ],
        function ($request){
            return new Response(200, PacientesController::getNewPaciente($request));
        }
    ]);

    $objRouter->post('/pacientes/cadastrar', [
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
    ]);
?>