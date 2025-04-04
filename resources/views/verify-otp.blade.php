@extends('front_layout')
@section('title', 'Verify OTP')
@section('content')
<div class="container register-container">
    <div class="col-md-4">
        <div class="register-card">
            <h3 class="text-center mb-4">Verify Your OTP</h3>
            @if (session('success'))
                <p class="text-green-500"></p>
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <p class="text-red-500"></p>
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <div class="alert alert-danger">
                        {{ $error }}
                    </div>
                @endforeach
            @endif
            <form action="{{ route('otp.verify') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="form-label">Enter your receive OTP</label>
                    <input type="text" class="form-control otp" name="otp"
                           placeholder="Enter your OTP">
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Verify</button>
                </div>
            </form>
            <a href="{{ route('otp.resend') }}" class="d-flex justify-content-end mt-2">Resend OTP</a>
        </div>
    </div>
</div>
@endsection
