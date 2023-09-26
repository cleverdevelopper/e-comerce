<?php
    use App\Http\Response;
    use App\Controller\Dashboard\UtilizadoresController;

    $objRouter->get('/utilizadores', [
        'middlewares'   => [
            'requere-admin-login'
        ],
        function ($request){
            return new Response(200, UtilizadoresController::getUtilizadores($request));
        }
    ]);

?>