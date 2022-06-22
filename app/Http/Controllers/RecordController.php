<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TypeDocument;
use App\TypeProcedure;
use App\TypeProceeding;
use App\Office;
use App\Delegation;

class RecordController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:turno-registrar', ['only' => ['register']]);
    }

       /**
     * Show the step One Form for creating a new product.
     *
     * @return \Illuminate\Http\Response
     */
    public function search()
    {
        $delegations = Delegation::select('name', 'id', 'address', 'zone')
            ->get();

        return view('records.search', compact('delegations'));
    }

    /**
     * Show the step One Form for creating a new product.
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $today = date('Y-m-d');

        $types_documents = TypeDocument::select('name', 'id')->get();
        $types_procedures = TypeProcedure::select('name', 'id')->get();
        $types_proceedings = TypeProceeding::select('name', 'id')->get();
        $offices = Office::select('name', 'id')->get();
        $delegations = Delegation::select('name', 'delegations.id AS id')
            ->join('turns', 'delegations.id', '=', 'turns.delegation_id')
            ->where("turns.date", ">", $today)
            ->groupBy('delegations.id')
            ->get();

        return view('records.register', compact('types_documents', 'types_procedures', 'types_proceedings', 'offices', 'delegations'));
    }
}
