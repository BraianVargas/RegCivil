<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Permission;
use App\Role;
use DataTables;

class PermissionController extends Controller
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
        $roles = Role::pluck('name', 'id');
        if ($request->ajax()) {
            $data = Permission::latest()->get();

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

        return view('permissions.index', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $permission = Permission::updateOrCreate(['id' => $request['Record_id']], [
            'name' => $request['name'],
            'slug' => $request['slug']
        ]);

        $permission->roles()->sync($request['roles']);

        return response()->json(['success' => 'Rol guardado!']);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $record = Permission::find($id);
        $roles = $record->roles;
        $data = [$record, $roles];

        return response()->json($data);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $permission = Permission::find($id);
        $permission->roles()->detach();
        $permission->delete();

        return response()->json(['success' => 'Rol eliminado!']);
    }
}
