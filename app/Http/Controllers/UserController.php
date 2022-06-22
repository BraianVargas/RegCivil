<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\User;
use App\Role;
use DataTables;
use Validator;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:usuario-administrar', ['only' => ['index', 'store', 'edit', 'destroy']]);
    }

    public function AuthRouteAPI(Request $request)
    {
        return $request->user();
    }

    public function settings(Request $request)
    {
        $user = Auth::user();

        return view('users.settings', compact('user'));
    }

    public function change(Request $request)
    {
        if (!(Hash::check($request->get('current-password'), Auth::user()->password))) {
            // The passwords matches
            toastr()->error('Su contraseña actual no concuerda con la contraseña registrada. Por favor, intente nuevamente.');
            return redirect()->back();
        }

        if (strlen($request->get('new-password')) < 6) {
            // The passwords a least 6 charatcers
            toastr()->error('Su nueva contraseña debe contener al menos 6 caracteres. Por favor, elija una contraseña diferente.');
            return redirect()->back();
        }

        if (strcmp($request->get('new-password-confirm'), $request->get('new-password')) != 0) {
            // Confirmed passwords
            toastr()->error('Su nueva contraseña no ha sido confirmada. Por favor, elija una contraseña diferente.');
            return redirect()->back();
        }

        if (strcmp($request->get('current-password'), $request->get('new-password')) == 0) {
            //Current password and new password are same
            toastr()->error('Su nueva contraseña no puede ser igual a la contraseña actual. Por favor, elija una contraseña diferente.');
            return redirect()->back();
        }


        $rules = [
            'current-password' => 'required',
            'new-password' => 'required',
            'new-password-confirm' => 'required',
        ];

        $messages = [
            'required' => 'Requerido',
        ];

        $validatedData = Validator::make($request->all(), $rules, $messages)->validate();

        //Change Password
        $user = Auth::user();
        $user->password = bcrypt($request->get('new-password'));
        $user->save();

        toastr()->success('Contraseña guardada!');

        return redirect('home');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $roles = Role::pluck('name', 'id');
        if ($request->ajax()) {
            $data = User::latest()->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Editar" class="edit btn btn-secondary btn-sm editRecord"><span title="Editar" class="fa fa-edit"></span></a>';

                    $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Eliminar" class="btn btn-danger btn-sm deleteRecord"><span title="Eliminar" class="fa fa-trash-alt"></span></a>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('users.index', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = User::updateOrCreate(['id' => $request['Record_id']], [
            'name' => $request['name'],
            'email' => $request['email'],
            'email_verified_at' => $request['email_verified_at'],
            'password' => bcrypt($request['password'])
        ]);

        $user->roles()->sync($request['roles']);

        return response()->json(['success' => 'Usuario guardado!']);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $record = User::find($id);
        $roles = $record->roles;
        $data = [$record, $roles];

        return response()->json($data);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::find($id)->delete();
        $user->roles()->detach();
        $user->delete();

        return response()->json(['success' => 'Usuario eliminado!']);
    }
}
