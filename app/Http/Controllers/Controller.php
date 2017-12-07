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
     * @return mixed  Either \Illuminate\Http\Response (the desired response or a
     * general error-message page) or void (because a Throwable is rethrown)
     */
    protected function tryOrCatch(Closure $trial, array $args)
    {
        $appEnvironment = config('app.env');

        try {

            return $trial($args);

        } catch (Throwable $e) {

            if ($appEnvironment == 'production') {
                $this->catchProduction($e);
            } else {
                $this->catchLocal($e);
            }

        } catch (Exception $e) {

            if ($appEnvironment == 'production') {
                $this->catchProduction($e);
            } else {
                $this->catchLocal($e);
            }
        }
    }

    /**
     * Respond to a thrown Throwable when in local development: Rethrow it.
     *
     * @param Throwable $e
     *
     * @return void
     */
    private function catchLocal($e)
    {
        throw $e;
    }

    /**
     * Respond to a thrown Throwable when in production: Log it and return
     * a general error-message page.
     *
     * @param Throwable $e
     *
     * @return \Illuminate\Http\Response
     */
    private function catchProduction($e)
    {
        Log::error($e->__toString());
        return view('errors.generalHttp');
    }
}
