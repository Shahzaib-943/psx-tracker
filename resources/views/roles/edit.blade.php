@extends('layouts.app')
@section('page-title', 'Edit Role')
@section('main-page', 'Roles')
@section('sub-page', 'Edit')
@section('content')
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Edit Role</h4>
                <form id="editRoleForm" action="{{ route('roles.update', $role->public_id) }}" method="POST">
                    @method('PUT')
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input id="name" class="form-control @error('name') is-invalid @enderror" name="name"
                            type="text" value="{{ old('name') ?? $role->name }}" placeholder="Name">
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="permissions" class="form-label">Select Permissions</label>
                    </div>
                    <div class="row">
                        @foreach ($groupedPermissions as $module => $modulePermissions)
                            @php
                                $moduleKey = \Illuminate\Support\Str::slug($module, '_');
                            @endphp
                            <div class="col-md-6 mb-4" data-module-wrapper="{{ $moduleKey }}">
                                {{-- <div class="card border"> --}}
                                    {{-- <div class="card-body"> --}}
                                        <div class="form-check mb-3">
                                            <input class="form-check-input module-checkbox" type="checkbox"
                                                   id="module_{{ $moduleKey }}" data-module="{{ $moduleKey }}">
                                            <label class="form-check-label fw-semibold" for="module_{{ $moduleKey }}">
                                                <h6 class="mb-0 text-primary">{{ $module }}</h6>
                                            </label>
                                        </div>
                                        <div class="row">
                                            @foreach ($modulePermissions as $permission)
                                                <div class="col-12 mb-2">
                                                    <div class="form-check">
                                                        <input
                                                            class="form-check-input permission-checkbox"
                                                            type="checkbox"
                                                            name="permissions[]"
                                                            value="{{ $permission->id }}"
                                                            id="permission_{{ $permission->id }}"
                                                            data-module="{{ $moduleKey }}"
                                                            {{ in_array($permission->id, $rolePermissionIds ?? []) ? 'checked' : '' }}
                                                        >
                                                        <label class="form-check-label" for="permission_{{ $permission->id }}">
                                                            {{ ucfirst($permission->name) }}
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    {{-- </div> --}}
                                {{-- </div> --}}
                            </div>
                        @endforeach
                    </div>
                    <input class="btn btn-primary" type="submit" value="Submit">
                </form>
            </div>
        </div>
    </div>
    @push('scripts')
        <script src="{{ asset('js/validation.js') }}"></script>
        <script src="{{ asset('nobleui/assets/vendors/jquery-validation/jquery.validate.min.js') }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Initialize module checkboxes based on pre-selected permissions
                function updateModuleCheckboxes() {
                    $('.module-checkbox').each(function() {
                        const module = $(this).data('module');
                        const $children = $('.permission-checkbox[data-module="' + module + '"]');
                        const $moduleCheckbox = $(this)[0];
                        
                        const total = $children.length;
                        const checkedCount = $children.filter(':checked').length;
                        
                        if (!$moduleCheckbox) return;
                        
                        if (checkedCount === total) {
                            $moduleCheckbox.checked = true;
                            $moduleCheckbox.indeterminate = false;
                        } else if (checkedCount > 0) {
                            $moduleCheckbox.checked = false;
                            $moduleCheckbox.indeterminate = true;
                        } else {
                            $moduleCheckbox.checked = false;
                            $moduleCheckbox.indeterminate = false;
                        }
                    });
                }
                
                // Initialize on page load
                updateModuleCheckboxes();
                
                // When module (heading) checkbox changes, toggle all child permissions
                $('.module-checkbox').on('change', function () {
                    const module = $(this).data('module');
                    const isChecked = $(this).is(':checked');

                    const $children = $('.permission-checkbox[data-module="' + module + '"]');
                    $children.prop('checked', isChecked);

                    // Clear indeterminate state when manually toggled
                    this.indeterminate = false;
                });

                // When any child permission changes, update the module checkbox state
                $('.permission-checkbox').on('change', function () {
                    const module = $(this).data('module');
                    const $children = $('.permission-checkbox[data-module="' + module + '"]');
                    const $moduleCheckbox = $('.module-checkbox[data-module="' + module + '"]')[0];

                    const total = $children.length;
                    const checkedCount = $children.filter(':checked').length;

                    if (!$moduleCheckbox) return;

                    // Update module checkbox state
                    if (checkedCount === total) {
                        $moduleCheckbox.checked = true;
                        $moduleCheckbox.indeterminate = false;
                    } else if (checkedCount > 0) {
                        $moduleCheckbox.checked = false;
                        $moduleCheckbox.indeterminate = true;
                    } else {
                        $moduleCheckbox.checked = false;
                        $moduleCheckbox.indeterminate = false;
                    }
                });
            });
        </script>
    @endpush
@endsection
