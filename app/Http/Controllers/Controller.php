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
     * App environment config value, e.g. 'local' or 'production'
     *
     * @var string
     */
    protected $appEnvironment;

    public function __construct()
    {
        $this->appEnvironment = config('app.env');
    }

    /**
     * Respond to a thrown Throwable in local development: Rethrow it.
     *
     * @param Throwable $throwable
     *
     * @return void
     */
    protected function catchLocally($throwable)
    {
        throw $throwable;
    }

    /**
     * Respond to a thrown Throwable in production: Log it and return
     * a general error-message page.
     *
     * @param Throwable $throwable
     *
     * @return \Illuminate\Http\Response
     */
    protected function catchInProduction($throwable)
    {
        Log::error($throwable->__toString());
        return view('errors.general');
    }
}
