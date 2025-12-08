@extends('layouts.app')
@section('page-title', 'Portfolios List')
@section('main-page', 'Portfolios')
@section('sub-page', 'List')
@section('content')

    <div class="row">
        <div class="col-md-12 ">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="card-title mb-0">Portfolios</h6>
                        <a href="{{ route('portfolios.create') }}" class="btn btn-primary">Create</a>
                    </div>
                    <div class="table-responsive">
                        <table id="portfolios_dataTable" class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    @role(\App\Constants\AppConstant::ROLE_ADMIN)
                                        <th id="user-column-header">User</th>
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
                    data: 'description',
                    name: 'description'
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
                });
            }
            console.log("columns : ", columns);
            var portfolioBaseUrl = "{{ url('/portfolios') }}";
            var table = $('#portfolios_dataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('portfolios.index') }}",
                columns: columns,
                drawCallback: function(settings) {
                    feather.replace();
                    // Add portfolio URL to each row
                    var api = this.api();
                    api.rows().every(function() {
                        var row = this.node();
                        var portfolioId = $(row).data('portfolio-id');
                        if (portfolioId) {
                            var portfolioUrl = portfolioBaseUrl + '/' + portfolioId;
                            $(row).attr('data-portfolio-url', portfolioUrl);
                        }
                    });
                },
            });

            // Make rows clickable except for action buttons
            $('#portfolios_dataTable tbody').on('click', 'tr', function(e) {
                var $target = $(e.target);
                var $row = $(this);
                var $clickedCell = $target.closest('td');
                
                // Don't navigate if clicking on buttons or in the action column (last column)
                if ($target.closest('button').length > 0 || $clickedCell.is(':last-child')) {
                    return;
                }
                
                // Navigate to portfolio URL
                var portfolioUrl = $row.data('portfolio-url');
                if (portfolioUrl) {
                    window.location.href = portfolioUrl;
                }
            });
            
            // Add cursor pointer style to indicate rows are clickable
            $('#portfolios_dataTable tbody').on('mouseenter', 'tr', function() {
                if ($(this).data('portfolio-url')) {
                    $(this).css('cursor', 'pointer');
                }
            });
        });
    </script>

    @push('scripts')
        <script src="{{ asset('nobleui/assets/vendors/datatables.net/jquery.dataTables.js') }}"></script>
        <script src="{{ asset('nobleui/assets/js/data-table.js') }}"></script>
        <script src="{{ asset('nobleui/assets/vendors/datatables.net-bs5/dataTables.bootstrap5.js') }}"></script>
    @endpush
@endsection
