<?php
 
namespace App\Exceptions\Api;
 
use Exception;
use App\Helpers\Api;
 
class InvalidLocaleException extends Exception
{
    /**
     * Report the exception.
     *
     * @return bool|null
     */
    public function report()
    {
        //
    }
 
    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        if ($request->is('api/*')) {          
            return Api::errorResponse(400, 'Locale does not exist');                        
        }        
    }
}