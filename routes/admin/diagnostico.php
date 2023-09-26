<?php
    use App\Http\Response;
    use App\Controller\Dashboard\DiagnosticoController;

    $objRouter->get('/diagnostico', [
        'middlewares'   => [
            'requere-admin-login'
        ],
        function ($request){
            return new Response(200, DiagnosticoController::getDiagnostico($request));
        }
    ]);

    $objRouter->get('/diagnostico/cadastrar', [
        'middlewares'   => [
            'requere-admin-login'
        ],
        function ($request){
            return new Response(200, DiagnosticoController::getNewDiagnostico($request));
        }
    ]);

    $objRouter->post('/diagnostico/cadastrar', [
        'middlewares'   => [
            'requere-admin-login'
        ],
        function ($request){
            return new Response(200, DiagnosticoController::setNewDiagnostico($request));
        }
    ]);

    $objRouter->get('/diagnostico/{id}/edit', [
        'middlewares'   => [
            'requere-admin-login'
        ],
        function ($request, $id){
            return new Response(200, DiagnosticoController::getEditDiagnostico($request, $id));
        }
    ]);

    $objRouter->post('/diagnostico/{id}/edit', [
        'middlewares'   => [
            'requere-admin-login'
        ],
        function ($request, $id){
            return new Response(200, DiagnosticoController::setEditDiagnostico($request, $id));
        }
    ]);
?>