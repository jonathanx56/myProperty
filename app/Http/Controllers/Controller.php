<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @SWG\Swagger(
 *     basePath="",
 *     schemes={"http", "https"},
 *     host=L5_SWAGGER_CONST_HOST,
 *     @SWG\SecurityScheme(
 *          securityDefinition="default",
 *          type="apiKey",
 *          in="header",
 *          name="Authorization"
 *      ),
 *     @SWG\Info(
 *         version="1.0.0",
 *         title="myProperty API",
 *         description="developed by Jonathan",
 *         @SWG\Contact(
 *             email="jonathanx56@hotmail.com"
 *         ),
 *     )
 * )
 */

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
