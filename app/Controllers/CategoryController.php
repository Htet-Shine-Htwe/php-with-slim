<?php

namespace App\Controllers;

use App\Contracts\RequestValidatorFactoryInterface;
use App\Contracts\RequestValidatorInterface;
use App\RequestValidator\CategoryCreateRequestValidator;
use App\ResponseFormatter;
use App\Services\CategoryService;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Views\Twig;

class CategoryController
{
    public function __construct(private readonly Twig $twig,private readonly RequestValidatorFactoryInterface $requestValidatorFactory,private readonly CategoryService $categoryService,private readonly ResponseFormatter $responseFormatter)
    {

    }

    public function index(Request $request, Response $response)
    {
        return $this->twig->render($response, 'categories/index.twig',
    [
        'categories' => $this->categoryService->getAll()
    ]);
    }

    public function store(Request $request, Response $response)
    {
        $data = $this->requestValidatorFactory->make(CategoryCreateRequestValidator::class)->validate($request->getParsedBody());

        $this->categoryService->create($data['name'],$request->getAttribute('user'));

        return $response->withHeader('Location','/categories')->withStatus(302);
    }

    public function delete(Request $request, Response $response,array $args)
    {
        $this->categoryService->delete((int) $args['id']);

        return $response->withHeader('Location','/categories')->withStatus(302);

    }

    public function get(Request $request, Response $response,array $args)
    {
        $category = $this->categoryService->get((int) $args['id']);

        if(!$category)
        {
            return $response->withStatus(404);
        }

        $data = ['id' => $category->getId(),'name' => $category->getName()];

      

        return $this->responseFormatter->asJson($response,$data);

    }

}