<?php
    use App\Http\Response;
    use App\Controller\Dashboard\DefinicoesController;

    $objRouter->get('/definicoes', [
        'middlewares'   => [
            'requere-admin-login'
        ],
        function ($request){
            return new Response(200, DefinicoesController::getDefinicoes($request));
        }
    ]);

    $objRouter->post('/definicoes', [
        'middlewares'   => [
            'requere-admin-login'
        ],
        function ($request){
            return new Response(200, DefinicoesController::setNewConfig($request));
        }
    ]);  
?>