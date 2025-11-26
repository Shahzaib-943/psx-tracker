<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Flasher\Prime\FlasherInterface;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\StoreRoleRequest;
use App\Services\CreateResourceService;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    protected $createResourceService;

    public function __construct(CreateResourceService $createResourceService)
    {
        $this->createResourceService = $createResourceService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $roles = Role::get(['id', 'name']);
            return DataTables::of($roles)
                ->addIndexColumn()
                ->addColumn('actionButton', function ($row) {
                    $editUrl = route('roles.edit', $row->id);
                    $deleteUrl = route('roles.destroy', $row->id);
                    $actionButtons = '<button type="button" class="btn btn-primary btn-icon" onclick="window.location.href=\'' . $editUrl . '\'">
                        <i data-feather="edit"></i>
                        </button>
                        <button type="button" class="btn btn-danger btn-icon delete-button" data-url="' . $deleteUrl . '" data-type="role">
                            <i data-feather="trash-2"></i>
                        </button>';
                    return $actionButtons;
                })
                ->rawColumns(['actionButton'])
                ->make(true);
        }

        return view('roles.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(FlasherInterface $flasher)
    {
        return view('roles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $request)
    {
        try {
            $this->createResourceService->create(Role::class, $request->validated());
            flash()->success('Role Created Successfully.');
            return to_route('roles.index');

        } catch (\Exception $e) {
            Log::error('Role creation failed: ' . $e->getMessage());
            flash()->error('An unexpected error occurred. Please try again.');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        if ($role->delete()) {
            return response()->json(['success' => 'Role deleted successfully.']);
        } else {
            return response()->json(['error' => 'Failed to delete role.'], 500);
        }
    }
}
