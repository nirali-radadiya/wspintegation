@extends('layouts.app')
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />
@endsection
@section('content')
    <div class="container mt-4">
        <div class="card shadow-lg rounded-4 border-0">
            <div class="card-header text-black text-center">
                <h3 class="mb-0">Users List</h3>
            </div>
            <div class="card-body">
                <table id="userTable" class="table table-striped table-hover table-bordered">
                    <thead class="table-dark">
                    <tr>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Register Via</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->phone }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ \App\Models\User::OTP_METHOD_ARR[$user->otp_method] }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#userTable').DataTable({
                responsive: true
            });
        });
    </script>
@endsection
