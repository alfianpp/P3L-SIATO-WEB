<?php

namespace App\Classes;

class APIResponse
{
    var $error;
    var $message;
    var $data;

    public function __construct()
    {
        $this->error =  false;
        $this->message = '';
        $this->data = null;
    }

    public function make()
    {
        return response()->json([
            'error' => $this->error,
            'message' => $this->message,
            'data' => $this->data
        ]);
    }
}