@extends('layouts.app')
@section('page-title', 'Event Types List')
@section('main-page', 'Event Types')
@section('sub-page', 'List')
@section('content')

    <div class="row">
        <div class="col-md-12 ">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Data Table</h6>
                    <div class="table-responsive">
                        <table id="event-types_dataTable" class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Type</th>
                                    @role(\App\Constants\AppConstant::ROLE_ADMIN)
                                        <th id="user-column-header">User</th>
                                        <th id="is_common-column">Is Common</th>
                                    @endrole
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="user-role" value="{{ auth()->user()->hasRole('admin') ? 'admin' : 'user' }}">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(function() {
            let userRole = @json(auth()->user()->getRoleNames()->first());
            const ADMIN = @json(\App\Constants\AppConstant::ROLE_ADMIN);
            columns = [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'type',
                    name: 'type'
                },
                {
                    data: 'actionButton',
                    name: 'actionButton',
                    orderable: false,
                    searchable: false
                }
            ];
            if (userRole === ADMIN) {
                columns.splice(3,0,{
                    data: 'user',
                    name: 'user',
                    render: function(data, type, row) {
                        return data ? data : 'N/A';
                    }
                }, {
                    data: 'is_common',
                    name: 'is_common',
                    render: function(data, type, row) {
                        return data ? data : 'N/A';
                    }
                });
            }
            console.log("columns : ", columns);
            $('#event-types_dataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('finance-categories.index') }}",
                columns: columns,
                drawCallback: function(settings) {
                    feather.replace();
                },
            });
        });
    </script>

    @push('scripts')
        <script src="{{ asset('nobleui/assets/vendors/datatables.net/jquery.dataTables.js') }}"></script>
        <script src="{{ asset('nobleui/assets/js/data-table.js') }}"></script>
        <script src="{{ asset('nobleui/assets/vendors/datatables.net-bs5/dataTables.bootstrap5.js') }}"></script>
    @endpush
@endsection
