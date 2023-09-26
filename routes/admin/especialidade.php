<?php
    use App\Http\Response;
    use App\Controller\Dashboard\EspecialidadeController;

    $objRouter->get('/especialidade', [
        'middlewares'   => [
            'requere-admin-login'
        ],
        function ($request){
            return new Response(200, EspecialidadeController::getEspecialidades($request));
        }
    ]);

    $objRouter->get('/especialidade/cadastrar', [
        'middlewares'   => [
            'requere-admin-login'
        ],
        function ($request){
            return new Response(200, EspecialidadeController::getNewEspecialidade($request));
        }
    ]);

    $objRouter->post('/especialidade/cadastrar', [
        'middlewares'   => [
            'requere-admin-login'
        ],
        function ($request){
            return new Response(200, EspecialidadeController::setNewEspecialidade($request));
        }
    ]);

    $objRouter->get('/especialidade/{id}/edit', [
        'middlewares'   => [
            'requere-admin-login'
        ],
        function ($request, $id){
            return new Response(200, EspecialidadeController::getEditEspecialidade($request, $id));
        }
    ]);

    $objRouter->post('/especialidade/{id}/edit', [
        'middlewares'   => [
            'requere-admin-login'
        ],
        function ($request, $id){
            return new Response(200, EspecialidadeController::setEditEspecialidade($request, $id));
        }
    ]);
?>