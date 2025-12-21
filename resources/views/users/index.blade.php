@extends('layouts.app')
@section('page-title', 'Users List')
@section('main-page', 'Users')
@section('sub-page', 'List')
@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    {{-- <h6 class="card-title mb-3">Users</h6> --}}
                    @can('create users')
                        <div id="dt-create-btn" class="d-none">
                            <a href="{{ route('users.create') }}" class="btn btn-primary">
                                Create
                            </a>
                        </div>
                    @endcan
                    <div class="table-responsive">
                        <table id="users_dataTable" class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
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
            $('#users_dataTable').DataTable({
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

                ajax: "{{ route('users.index') }}",

                columns: [
                    { data: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'name' },
                    { data: 'email' },
                    { data: 'role_name' },
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