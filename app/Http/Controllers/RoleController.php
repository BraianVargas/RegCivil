<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Role;
use App\Permission;
use DataTables;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:usuario-administrar', ['only' => ['index', 'store', 'edit', 'destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $permissions = Permission::pluck('name', 'id');

        if ($request->ajax()) {
            $data = Role::latest()->get();

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

        return view('roles.index', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $role = Role::updateOrCreate(['id' => $request['Record_id']], [
            'name' => $request['name'],
            'slug' => $request['slug']
        ]);

        $role->permissions()->sync($request['permissions']);

        return response()->json(['success' => 'Rol guardado!']);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $record = Role::find($id);
        $permissions = $record->permissions;
        $data = [$record, $permissions];

        return response()->json($data);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $role = Role::find($id)->delete();
        $role->permissions()->detach();
        $role->delete();

        return response()->json(['success' => 'Rol eliminado!']);
    }
}
