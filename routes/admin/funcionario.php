<?php
    use App\Http\Response;
    use App\Controller\Dashboard\FuncionarioController;

    $objRouter->get('/funcionario', [
        'middlewares'   => [
            'requere-admin-login'
        ],
        function ($request){
            return new Response(200, FuncionarioController::getFuncionarios($request));
        }
    ]);

    $objRouter->get('/funcionario/cadastrar', [
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

    $objRouter->post('/funcionario/{id}/edit', [
        'middlewares'   => [
            'requere-admin-login'
        ],
        function ($request, $id){
            return new Response(200, FuncionarioController::setEditFuncionario($request, $id));
        }
    ]);
?>