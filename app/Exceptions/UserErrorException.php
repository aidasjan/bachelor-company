<?php

namespace App\Exceptions;

use Exception;

class UserErrorException extends Exception
{
    public function render($request){
        if ($request->ajax() || $request->wantsJson()) {
            return array(
                'success' => false,
                'message' => $this->getMessage(),
            );
        }
        else {
            return redirect()->back()->withInput($request->input())->withErrors([$this->getMessage()]);
        }
    }
}
