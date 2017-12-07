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

    /**
     * Wrap the given closure in a try/catch block. Define a shared way of
     * handling Throwables & Exceptions that can be used by most controller
     * methods.
     *
     * @param Closure $trial  Contains the calling controller-method's procedures.
     * @param array $args  Associative array containing the calling controller-
     * method's arguments, so they can be passed to, and extracted by, the closure.
     *
     * @return \Illuminate\Http\Response  Either the desired response or, in case
     * a Throwable or Exception is thrown, a general error-message page
     */
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
