<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

abstract class Controller
{
     protected function shouldInclude(Request $request, string $relationship): bool
    {
        $includes = $request->get('include');
        $includes = explode(',', $includes);
        return in_array($relationship, $includes);
    }
  
}
