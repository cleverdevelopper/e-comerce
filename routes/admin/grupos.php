<?php
    use App\Http\Response;
    use App\Controller\Dashboard\GrupoController;

    $objRouter->get('/grupos', [
        'middlewares'   => [
            'requere-admin-login'
        ],
        function ($request){
            return new Response(200, GrupoController::getGrupos($request));
        }
    ]);

   $objRouter->get('/grupos/cadastrar', [
        'middlewares'   => [
            'requere-admin-login'
        ],
        function ($request){
            return new Response(200, GrupoController::getNewGrupo($request));
        }
    ]);

     $objRouter->post('/grupos/cadastrar', [
        'middlewares'   => [
            'requere-admin-login'
        ],
        function ($request){
            return new Response(200, GrupoController::setNewGrupo($request));
        }
    ]);

    $objRouter->get('/grupos/{id}/edit', [
        'middlewares'   => [
            'requere-admin-login'
        ],
        function ($request, $id){
            return new Response(200, GrupoController::getEditGrupo($request, $id));
        }
    ]);

    $objRouter->post('/grupos/{id}/edit', [
         'middlewares'   => [
            'requere-admin-login'
        ],
        function ($request, $id){
            return new Response(200, GrupoController::setEditGrupo($request, $id));
        }
    ]);
?>