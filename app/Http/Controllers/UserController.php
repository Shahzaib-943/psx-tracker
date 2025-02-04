<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\StoreUserRequest;
use App\Services\CreateResourceService;
use App\Http\Requests\UpdateUserRequest;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
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
            $users = User::with('roles')->get(['id', 'name', 'email']);
            return DataTables::of($users)
                ->addIndexColumn()
                ->addColumn('actionButton', function ($row) {
                    $editUrl = route('users.edit', $row->id);
                    $deleteUrl = route('users.destroy', $row->id);
                    $actionButtons = '<button type="button" class="btn btn-primary btn-icon" onclick="window.location.href=\'' . $editUrl . '\'">
                        <i data-feather="edit"></i>
                        </button>
                        <button type="button" class="btn btn-danger btn-icon delete-button" data-url="' . $deleteUrl . '" data-type="user">
                            <i data-feather="trash-2"></i>
                        </button>';
                    return $actionButtons;
                })
                ->addColumn('role_name', function ($row) {
                    return $row->roles->first() ? ucfirst($row->roles->first()->name) : 'N/A';
                })
                ->rawColumns(['actionButton'])
                ->make(true);
        }

        return view('users.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::get(['id', 'name']);
        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $validatedData = $request->validated();
        $user = User::create($validatedData);
        if ($user) {

            $user->assignRole($validatedData['role']);
        }
        // $this->createResourceService->create(User::class, $request->validated());
        flash()->success('User Created Successfully.');
        return to_route('users.index');
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
    public function edit(User $user)
    {
        if (!is_null($user)) {
            $roles = Role::get(['id', 'name']);
            return view('users.edit', compact('user', 'roles'));
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $validatedData = $request->validated();
        if (empty($validatedData['password'])) {
            unset($validatedData['password']);
        }
        if ($user->update($validatedData)) {
            flash()->success('User Updated Successfully.');
        } else {
            flash()->success('An unexpected error occurred. Please try again.');
        }
        return to_route('users.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if ($user->delete()) {
            return response()->json(['success' => 'User deleted successfully.']);
        } else {
            return response()->json(['error' => 'Failed to delete user.'], 500);
        }
    }
}