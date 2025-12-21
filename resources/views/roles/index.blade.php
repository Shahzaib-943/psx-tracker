@extends('layouts.app')
@section('page-title', 'Roles List')
@section('main-page', 'Roles')
@section('sub-page', 'List')
@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    @can('create roles')
                        <div id="dt-create-btn" class="d-none">
                            <a href="{{ route('roles.create') }}" class="btn btn-primary">
                                Create
                            </a>
                        </div>
                    @endcan
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

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#roles_dataTable').DataTable({
                processing: true,
                serverSide: true,

                dom: `
                    <"d-flex justify-content-between align-items-center mb-3"
                        <"d-flex align-items-center"f>
                        <"dt-create-wrapper">
                    >
                    <"table-responsive"rt>
                    <"d-flex justify-content-between align-items-center mt-3"
                        <"dataTables_info"i>
                        <"dataTables_paginate"p>
                    >
                `,

                ajax: "{{ route('roles.index') }}",

                columns: [
                    { data: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'name' },
                    { data: 'actionButton', orderable: false, searchable: false }
                ],

                initComplete: function () {
                    let btn = $('#dt-create-btn');
                    if (btn.length) {
                        $('.dt-create-wrapper').html(btn.html());
                        btn.remove();
                    }
                },

                drawCallback: function () {
                    feather.replace();
                }
            });
        });
    </script>

    @push('scripts')
        <script src="{{ asset('nobleui/assets/vendors/datatables.net/jquery.dataTables.js') }}"></script>
        <script src="{{ asset('nobleui/assets/vendors/datatables.net-bs5/dataTables.bootstrap5.js') }}"></script>
        <script src="{{ asset('nobleui/assets/js/data-table.js') }}"></script>
    @endpush
@endsection