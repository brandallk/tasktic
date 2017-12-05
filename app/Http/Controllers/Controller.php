<?php

namespace App\Http\Controllers;

use Throwable;
use Exception;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function tryToDo(Closure $trialStatements)
    {
        try {
            
            $trialStatements();

        } catch (Throwable $e) {

            Log::error($e->__toString());
            return view('errors.generalHttp');

        } catch (Exception $e) {

            Log::error($e->__toString());
            return view('errors.generalHttp');
        }
    }
}
