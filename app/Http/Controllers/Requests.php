<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Requests extends Controller
{
    public function test(Request $request) {
        $data = $request->all();

        $color = new Color();

        $color->randomize();

        return response()->json($color->getInfo(), 200);
    }
}
