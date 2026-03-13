<?php

namespace App\Http\Controllers;
use OpenApi\Attributes as OA;

const OK = 200;
const SERVER_ERROR = 500;
const NOT_FOUND = 404;
const INVALID_CONTENT = 422;
const OK_CREATED = 201;

#[OA\Info(
    version: "1.0.0",
    title: "API Laravel pour Albums et Chansons",
    description: "Documentation API Laravel avec Swagger"
)]
abstract class Controller
{
    //
}
