@extends('layouts.app')
@section('page-title', 'Role List')
@section('main-page', 'Roles')
@section('sub-page', 'List')
@section('content')

    <div class="row">
        <div class="col-md-12 ">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="card-title mb-0">Roles</h6>
                        <a href="{{ route('roles.create') }}" class="btn btn-primary">Create</a>
                    </div>
                    <div class="table-responsive">
                        <table id="roles_dataTable" class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" ></script>
    <script>
        $(document).ready(function () {
            $('#roles_dataTable').DataTable({
                processing: true,
                serverSide: true,
                drawCallback: function(settings) {
                    feather.replace();
                },
                ajax: "{{ route('roles.index') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'name', name: 'name' },
                    { data: 'actionButton', name: 'actionButton', orderable: false, searchable: false }
                ]
            });
        });
    </script>

    @push('scripts')
        <script src="{{ asset('nobleui/assets/vendors/datatables.net/jquery.dataTables.js') }}"></script>
        <script src="{{ asset('nobleui/assets/js/data-table.js') }}"></script>
        <script src="{{ asset('nobleui/assets/vendors/datatables.net-bs5/dataTables.bootstrap5.js') }}"></script>
    @endpush
@endsection
