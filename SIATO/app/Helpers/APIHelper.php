<?php

namespace App\Helpers;

use App\Pegawai;

class APIHelper
{
    public static function isPermitted($api_key, $permitted_role) {
        if($api_key) {
            $pegawai = Pegawai::where('api_key', $api_key)->first();
            if($pegawai && in_array($pegawai->role, $permitted_role)) {
                return true;
            }
            else {
                return false;
            }
        }
        else {
            return false;
        }
    }

    public static function JSONResponse($response)
    {
        return response()->json([
            'error' => $response['error'],
            'message' => $response['message'],
            'data' => $response['data']
        ]);
    }
}