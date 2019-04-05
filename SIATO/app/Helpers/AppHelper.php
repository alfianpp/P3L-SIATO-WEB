<?php

namespace App\Helpers;

use Illuminate\Http\Request;

use Validator;

class AppHelper
{
    public static function isFillableFilled(Request $request, $fillable, $nullable) {
        $notnull = collect($fillable)->diff($nullable)->toArray();

        if($request->has($fillable) && $request->filled($fillable)) {
            return true;
        }
        else if($request->has($notnull) && $request->filled($notnull)) {            
            return true;
        }
        else {
            return false;
        }
    }

    public static function isValidRequest(Request $request, $rules) {
        $result = [
            'isValid' => true,
            'errors' => null
        ];

        $validator = Validator::make(array_filter($request->all(), function($value) {
            return ($value !== null);
        }), $rules);
        
        if ($validator->fails()) {
            $result['isValid'] = false;
            $result['errors'] = $validator->errors();
        }
        
        return $result;
    }
}