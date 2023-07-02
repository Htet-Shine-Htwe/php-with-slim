<?php

declare(strict_types = 1);

use App\Controllers\AuthController;
use App\Controllers\CategoryController;
use App\Controllers\HomeController;
use App\Middleware\AuthMiddleware;
use App\Middleware\GuestMiddleware;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {
    $app->get('/', [HomeController::class, 'index'])->add(AuthMiddleware::class);

    $app->group('',function(RouteCollectorProxy $route){
        $route->get('/login', [AuthController::class, 'loginView'])->add(GuestMiddleware::class);
        $route->get('/register', [AuthController::class, 'registerView'])->add(GuestMiddleware::class);
        $route->post('/login', [AuthController::class, 'login'])->add(GuestMiddleware::class);
        $route->post('/register', [AuthController::class, 'register'])->add(GuestMiddleware::class);
    })->add(GuestMiddleware::class);
    $app->post('/logout',[AuthController::class,'logout'])->add(AuthMiddleware::class);
  
    $app->group('/categories',function(RouteCollectorProxy $categories){
        $categories->get('', [CategoryController::class, 'index']);
        $categories->post('', [CategoryController::class, 'store']);
        $categories->delete('/{id}', [CategoryController::class, 'delete']);
        $categories->get('/{id}', [CategoryController::class, 'get']);
       
    })->add(AuthMiddleware::class);

};
