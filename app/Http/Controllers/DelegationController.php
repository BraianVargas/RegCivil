<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Delegation;
use App\Turn;
use DB;

class DelegationController extends Controller
{
    public function show($id)
    {
        return Delegation::find($id);
    }

    public function getDates($id)
    {
        $today = date('Y-m-d');

        // Search if there are generated turns
        $last_date = Turn::select('date')
            ->where("date", ">", $today)
            ->where('delegation_id', $id)
            ->where('active', 1)
            ->whereNull('person_id')
            ->orderBy('date', 'DESC')
            ->first();

        if (!$last_date['date']) {
            return response()->json(['success' => TRUE, 'data' => $today], 200);
        } else {
            return response()->json(['success' => TRUE, 'data' => $last_date['date']], 200);
        }
    }

    public function getTurns($id, $date)
    {
        // Search if there are generated turns
        $turns = Turn::select('id', 'date', 'time', 'duration')
            ->where("date", "=", $date)
            ->where('delegation_id', $id)
            ->where('active', 1)
            ->whereNull('person_id')
            ->orderBy('time', 'ASC')
            ->groupBy('time')
            ->groupBy('delegation_id')
            ->groupBy('date')
            ->get();

        if (!$turns) {
            return response()->json(['success' => FALSE, 'message' => 'No existen turnos generados para el dia
            ' . $date . ' en dicha delegación.'], 400);
        }

        return response()->json(['success' => TRUE, 'data' => $turns], 200);
    }

    public function getTurnsAssigned($id, $fromdate, $todate)
    {
        // Search if there are generated turns
        $turns = Turn::select('turns.id', 'date', 'time', 'duration', 'persons.last_name', 'persons.first_name', DB::raw('CONCAT(types_documents.name, " ", persons.document_number) AS document'), 'persons.phone', 'persons.cell_phone', 'types_procedures.name as type_procedure', 'delegations.name as delegation', 'delegations.address as address')
            ->join('persons', 'turns.person_id', '=', 'persons.id')
            ->join('delegations', 'turns.delegation_id', '=', 'delegations.id')
            ->join('types_documents', 'persons.document_type_id', '=', 'types_documents.id')
            ->join('types_procedures', 'turns.type_procedure_id', '=', 'types_procedures.id')
            ->where("date", ">=", $fromdate)
            ->where("date", "<=", $todate)
            ->where('delegation_id', $id)
            ->where('active', 1)
            ->orderBy('time', 'ASC')
            ->get();

        if (!$turns) {
            return response()->json(['success' => FALSE, 'message' => 'No existen turnos asignados entre ' . $fromdate . ' y '.$todate.' en dicha delegación.'], 400);
        }

        return response()->json(['success' => TRUE, 'data' => $turns], 200);
    }
}
