<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Delegation;
use App\Turn;
use App\Person;
use App\Retirement;
use Illuminate\Support\Facades\Auth;
use DB;

class TurnController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:turno-administrar', ['only' => ['generate', 'create', 'search', 'cancel']]);
    }

    public function search()
    {
        $delegations = Delegation::select('name', 'id')->get();

        return view('turns.search', compact('delegations'));
    }

    public function cancel(Request $request)
    {
        $date = implode('-', array_reverse(explode('-', $request['date'])));

        // Search if there are generated turns
        $turns = Turn::where('date', $date)
            ->where('delegation_id', $request['delegation_id'])
            ->where('active', 1)
            ->count();

        if ($turns == 0) {
            return response()->json(['success' => FALSE, 'message' => 'No existen turnos generados para el dia
            ' . $request['date'] . ' en dicha delegación.<br>Por favor seleccione otro rango.'], 400);
        }

        // Get the turns generated
        $query = Turn::where('date', $date)
            ->where('delegation_id', $request['delegation_id']);

        // Get the people data
        $results = $query->join('persons', 'turns.person_id', '=', 'persons.id')
            ->select(\DB::raw('TRUNCATE(DATEDIFF(CURRENT_DATE, STR_TO_DATE(persons.birth_date, "%Y-%m-%d"))/365, 0) AS age'), \DB::raw('CONCAT(persons.last_name, ", ", persons.first_name) as name'), 'birth_date', 'address', 'phone', 'document_number')
            ->orderBy('persons.last_name', 'ASC')
            ->orderBy('persons.first_name', 'ASC')
            ->get();

        // Update the status
        $query->update(['active' => 0]);

        return response()->json(['success' => TRUE, 'data' => $results], 200);
    }

    public function generate()
    {
        $delegations = Delegation::select('name', 'id')->get();
        $durations = array('5', '10', '15', '20', '25', '30', '35', '40', '45', '50', '60');

        return view('turns.generate', compact('delegations', 'durations'));
    }

    public function create(Request $request)
    {
        $dates = explode(" - ", $request['dates']);
        $start_date = date('Y-m-d', strtotime($dates[0]));
        $end_date =  date('Y-m-d', strtotime($dates[1]));
        $start_time = date('H:i', strtotime($request['time_start']));
        $end_time =  date('H:i', strtotime($request['time_end']));
        $duration = intval($request['duration']);
        $number_turns = intval($request['number_turns']);
        $delegation_id = $request['delegation_id'];

        //Search if there are generated laps
        $turns = Turn::whereBetween('date', [$start_date, $end_date])
            ->where('delegation_id', $request['delegation_id'])
            ->where('active', 1)
            ->orderBy('date', 'ASC')
            ->first();

        if ($turns) {
            return response()->json(['success' => FALSE, 'message' => 'Ya existen turnos generados para el dia
            ' . $turns['date'] . ' en dicha delegación.<br>Por favor seleccione otro rango.'], 400);
        }
        $records = array();
        $date = $start_date;
        $now = date('Y-m-d H:i:s');

        while (strtotime($date) <= strtotime($end_date)) {
            $time = $start_time;

            while ($time <= $end_time) {
                $i = 0;
                while ($number_turns > $i) {
                    $records[] = [
                        'date' => $date,
                        'time' => $time,
                        'creator_id' => Auth::user()->id,
                        'delegation_id' => $delegation_id,
                        'active' => 1,
                        'duration' => $duration,
                        'created_at' => $now,
                        'updated_at' => $now
                    ];

                    $i++;
                }
                $time = date('H:i', strtotime("+" . $duration . " minutes", strtotime($time)));
            }
            $date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
        }

        DB::table('turns')->insert($records);

        return response()->json(['success' => TRUE], 200);
    }

    public function findOrCreatePerson($arr, $data = array())
    {
        if ($arr['person_id'] == 0) {
            $person = Person::where('document_number', $arr['document_number'])->where('document_type_id', $arr['document_type_id'])->first();
            if (!$person) {
                $person = new Person;
            }
        } else {
            $person = Person::find($arr['person_id']);
        }

        $person->document_type_id =  $arr['document_type_id'];
        $person->document_number = $arr['document_number'];
        $person->first_name = ($arr['first_name'] ? $arr['first_name'] : $person->first_name);
        $person->last_name = ($arr['last_name'] ? $arr['last_name'] : $person->last_name);
        $person->gender = ($arr['gender'] ? $arr['gender'] : $person->gender);

        if ($data) {
            $person->birth_date = ($data['birth_date'] ? $data['birth_date'] : $person->birth_date);
            $person->address = ($data['address'] ? $data['address'] : $person->address);
            $person->location =  ($data['location'] ? $data['location'] : $person->location);
            $person->postal_code =  ($data['postal_code'] ? $data['postal_code'] : $person->postal_code);
            $person->phone = ($data['phone'] ? $data['phone'] : $person->phone);
            $person->cell_phone = ($data['cell_phone'] ? $data['cell_phone'] : $person->cell_phone);
            $person->another_phone = ($data['another_phone'] ? $data['another_phone'] : $person->another_phone);
            $person->email = ($data['email'] ? $data['email'] : $person->email);
        }

        $person->save();

        return $person;
    }

    public function createRetirement(Request $request)
    {
        $arr = $request['deliveryInfo'][0]['retirement'];
        $retirement = $this->findOrCreatePerson($arr);

        $now = date('Y-m-d H:i:s');

        foreach ($request['proceedingsInfo'] as $proceeding) {
            $arr_interested = $proceeding['interested'];
            $interested = $this->findOrCreatePerson($arr_interested);

            if ($proceeding['type_proceeding_id'] == 2 or $proceeding['type_proceeding_id'] == 4) {
                $arr_spouse = $proceeding['spouse'];

                $spouse = $this->findOrCreatePerson($arr_spouse);
                $spouse_id = $spouse->id;
            } else {
                $spouse_id = NULL;
            }

            $records[] = [
                'type_proceeding_id' => $proceeding['type_proceeding_id'],
                'office_id' => $proceeding['office_id'],
                'interested_id' => $interested->id,
                'spouse_id' => $spouse_id,
                'retirement_id' => $retirement->id,
                'turn_id' => $request['turn_id_selected'],
                'tome_book' => $proceeding['tome_book'],
                'number_copies' => $proceeding['number_copies'],
                'status' => 0,
                'created_at' => $now,
                'updated_at' => $now
            ];
        }

        DB::table('retirements')->insert($records);
    }

    public function reserve(Request $request)
    {
        try {
            $arr = array(
                'person_id' => $request['person_id'],
                'document_type_id' => $request['document_type_id'],
                'document_number' => $request['document_number'],
                'first_name' => $request['first_name'],
                'last_name' => $request['last_name'],
                'gender' => $request['gender'],
            );

            $data = array(
                'birth_date' => $request['birth_date'],
                'address' => $request['address'],
                'location' => $request['location'],
                'postal_code' => $request['postal_code'],
                'phone' => $request['phone'],
                'cell_phone' => $request['cell_phone'],
                'another_phone' => $request['another_phone'],
                'email' => $request['email'],
            );

            $person = $this->findOrCreatePerson($arr, $data);

            if ($request['type_procedure_id'] == 1) {
                $this->createRetirement($request);
            }

            $turn = Turn::find($request['turn_id_selected']);

            if (!$turn->person_id) {
                $turn->allocator_id = Auth::user()->id;
                $turn->person_id = $person->id;
                $turn->type_procedure_id = $request['type_procedure_id'];

                $turn->save();

                return response()->json(['success' => TRUE, 'data' => $turn], 200);
            }

            return response()->json(['success' => FALSE, 'message' => 'El turno ya ha sido asignado a otra persona!'], 400);
        } catch (\Exception $e) {
            return response()->json(['success' => FALSE, 'message' => $e->getMessage()], 400);
        }
    }
}
