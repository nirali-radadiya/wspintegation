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
            <h3 class="text-center mb-4">Get started with your email or phone number</h3>
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <div class="alert alert-danger">
                        {{ $error }}
                    </div>
                @endforeach
            @endif
            <form action="{{ route('register') }}" method="POST">
                @csrf
                <div class="mb-3 phone-div @if(old('otp_method') == \App\Models\User::OTP_METHOD_EMAIL) d-none @endif">
                    <label class="form-label">Phone</label>
                    <input type="text" class="form-control phone" name="phone" @error('phone') is-invalid
                           @enderror value="{{ old('phone') }}"
                           placeholder="Enter your phone">
                    @error('phone')
                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                    @enderror
                </div>
                <div
                    class="mb-3 email-div @if(old('otp_method') == \App\Models\User::OTP_METHOD_SMS || old('otp_method') == \App\Models\User::OTP_METHOD_WHATSAPP || old('otp_method') == null) d-none @endif">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control email" name="email" @error('email') is-invalid
                           @enderror value="{{ old('email') }}"
                           placeholder="Enter your email">
                    @error('email')
                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Receive OTP via:</label>
                    <div class="flex items-center space-x-4">
                        <label class="flex items-center space-x-2">
                            <input type="radio" name="otp_method" value="{{\App\Models\User::OTP_METHOD_SMS}}"
                                   class="accent-blue-500 otp-method" checked
                                   @if(old('otp_method') == \App\Models\User::OTP_METHOD_SMS) checked @endif>
                            <span>SMS</span>
                        </label>
                        <label class="flex items-center space-x-2">
                            <input type="radio" name="otp_method" value="{{\App\Models\User::OTP_METHOD_EMAIL}}"
                                   class="accent-blue-500 otp-method"
                                   @if(old('otp_method') == \App\Models\User::OTP_METHOD_EMAIL) checked @endif>
                            <span>Email</span>
                        </label>
                        <label class="flex items-center space-x-2">
                            <input type="radio" name="otp_method" value="{{\App\Models\User::OTP_METHOD_WHATSAPP}}"
                                   class="accent-blue-500 otp-method"
                                   @if(old('otp_method') == \App\Models\User::OTP_METHOD_WHATSAPP) checked @endif>
                            <span>WhatsApp</span>
                        </label>
                    </div>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    $(document).ready(function () {
        $('.otp-method').on('click', function () {
            let otpMethod = $(this).val();
            if (otpMethod == '{{\App\Models\User::OTP_METHOD_EMAIL}}') {
                $('.email-div').removeClass('d-none');
                $('.phone-div').addClass('d-none');
                $('.phone').attr('required', false);
                $('.email').attr('required', true);
            } else {
                $('.email-div').addClass('d-none');
                $('.phone-div').removeClass('d-none');
                $('.phone').attr('required', true);
                $('.email').attr('required', false);
            }
        })
    });
</script>
</body>
</html>
