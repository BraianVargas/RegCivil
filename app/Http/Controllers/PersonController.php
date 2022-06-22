<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Person;

class PersonController extends Controller
{
   public function find($type, $number){

    $person = Person::where('document_type_id', $type)->where('document_number', $number)->first();
    if (!$person) {
        return response()->json(['success' => FALSE, 'message' => 'No se ha encontrado la persona!'], 400);
    }

    return response()->json(['success' => TRUE, 'data' => $person], 200);
   }
}
