<?php

namespace App\Http\Controllers;

use Closure;
use Throwable;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function tryOrCatch(Closure $trial, array $args)
    {
        try {

            return $trial($args);

        } catch (Throwable $e) {

            Log::error($e->__toString());
            return view('errors.generalHttp');

        } catch (Exception $e) {

            Log::error($e->__toString());
            return view('errors.generalHttp');
        }
    }
}
