<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Flasher\Prime\FlasherInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreRoleRequest;
use App\Services\CreateResourceService;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    protected $createResourceService;

    public function __construct(CreateResourceService $createResourceService)
    {
        $this->createResourceService = $createResourceService;

        $this->middleware('permission:view roles')->only('index');
        $this->middleware('permission:create roles')->only('create', 'store');
        $this->middleware('permission:edit roles')->only('edit', 'update');
        $this->middleware('permission:delete roles')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $roles = Role::get(['id', 'name', 'public_id']);
            return DataTables::of($roles)
                ->addIndexColumn()
                ->addColumn('actionButton', function ($row) {
                    $actionButtons = '';
                    /** @var \App\Models\User|null $user */
                    $user = Auth::user();
                    
                    // Check if user has permission to edit users using Spatie Permission
                    if ($user && $user->can('edit roles')) {
                        $editUrl = route('roles.edit', $row->public_id);
                        $actionButtons .= '<button type="button" class="btn btn-primary btn-icon me-2" onclick="window.location.href=\'' . $editUrl . '\'">
                            <i data-feather="edit"></i>
                        </button>';
                    }
                    
                    // Check if user has permission to delete users using Spatie Permission
                    if ($user && $user->can('delete roles')) {
                        $deleteUrl = route('roles.destroy', $row->public_id);
                        $actionButtons .= '<button type="button" class="btn btn-danger btn-icon delete-button" data-url="' . $deleteUrl . '" data-type="role">
                            <i data-feather="trash-2"></i>
                        </button>';
                    }
                    
                    return $actionButtons ?: '-';
                })
                ->rawColumns(['actionButton'])
                ->make(true);
        }

        return view('roles.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = Permission::get(['id', 'name']);
        
        // Group permissions by module (last word in permission name)
        $groupedPermissions = $permissions->groupBy(function ($permission) {
            $parts = explode(' ', $permission->name);
            $module = end($parts);
            // Convert kebab-case to title case (e.g., "system-settings" -> "System Settings")
            return ucwords(str_replace('-', ' ', $module));
        });
        
        return view('roles.create', compact('groupedPermissions'));
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
    }
    
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        $permissions = Permission::get(['id', 'name']);
        
        // Get role's assigned permission IDs
        $rolePermissionIds = $role->permissions->pluck('id')->toArray();
        
        // Group permissions by module (last word in permission name)
        $groupedPermissions = $permissions->groupBy(function ($permission) {
            $parts = explode(' ', $permission->name);
            $module = end($parts);
            return ucwords(str_replace('-', ' ', $module));
        });
        
        return view('roles.edit', compact('role', 'groupedPermissions', 'rolePermissionIds'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreRoleRequest $request, Role $role)
    {
        try {
        $validatedData = $request->validated();
        if ($role->update($validatedData)) {
            $role->permissions()->sync($validatedData['permissions']);
            flash()->success('Role Updated Successfully.');
            return to_route('roles.index');
        } else {
            flash()->error('An unexpected error occurred. Please try again.');
            return redirect()->back()->withInput();
        }
        } catch (\Exception $e) {
            Log::error('Role update failed: ' . $e->getMessage());
            flash()->error('An unexpected error occurred. Please try again.');
            return redirect()->back()->withInput();
        }
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
