@extends('layouts.app')
@section('page-title', 'Records List')
@section('main-page', 'Finance Records')
@section('sub-page', 'List')
@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Records</h6>
                    <div class="table-responsive">
                        <table id="finance-records-dataTable" class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Finance Type</th>
                                    <th>Category</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                    <th>Description</th>
                                    <th id="user-column-header">User</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" id="user-role" value="{{ auth()->user()->hasRole('admin') ? 'admin' : 'user' }}">
    <!-- Custom Placeholder for the 'hello' message -->
    {{-- <div class="d-flex justify-content-between align-items-center mb-3">
    <p id="custom-text" class="mb-0">Hello</p>
</div> --}}

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(function() {
            let userRole = @json(auth()->user()->getRoleNames()->first());
            const ADMIN = @json(\App\Models\User::ROLE_ADMIN);
            $('#finance-records-dataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('finance-records.index') }}",
                drawCallback: function(settings) {
                    feather.replace(); // Initialize icons
                    let userColumn = $('#user-column-header');
                    userColumn.css('display', userRole === ADMIN ? '' :
                        'none');
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'type',
                        name: 'type'
                    },
                    {
                        data: 'category',
                        name: 'category'
                    },
                    {
                        data: 'amount',
                        name: 'amount'
                    },
                    {
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'description',
                        name: 'description'
                    },
                    {
                        data: 'user',
                        name: 'user',
                        visible: userRole === 'admin',
                        render: function(data, type, row) {
                            return data ? data : 'N/A';
                        }
                    },

                    {
                        data: 'actionButton',
                        name: 'actionButton',
                        orderable: false,
                        searchable: false
                    }
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
