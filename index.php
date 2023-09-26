<?php
    require __DIR__.'/includes/app.php';
    use App\Http\Router;

    $objRouter = new Router(URL);
        include __DIR__.'/routes/Login/loginRoutes.php';
        include __DIR__.'/routes/admin.php';

    $objRouter->run()
              ->sendResponse();
?>