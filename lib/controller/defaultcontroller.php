<?php

namespace Ramapriya\Slim\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class DefaultController extends AbstractController
{
    public function index(ServerRequestInterface $request, ResponseInterface $response, array $args = []): ResponseInterface
    {
        return $response;
    }
}