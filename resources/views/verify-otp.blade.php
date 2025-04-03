<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('{{ asset('assets/images/bgimage.jpeg') }}') no-repeat center center fixed;
            background-size: cover;
        }

        .register-container {
            min-height: 100vh;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            padding-right: 50px;
        }

        .register-card {
            background: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            width: 450px;
        }
    </style>
</head>
<body>
<div class="container register-container">
    <div class="col-md-4">
        <div class="register-card">
            <h3 class="text-center mb-4">Verify Your OTP</h3>
            @if (session('success'))
                <p class="text-green-500"></p>
                <div class="alert alert-danger">
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</body>
</html>
