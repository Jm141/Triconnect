
<form method="POST" action="{{ route('userLogin') }}">
    @csrf

    <div class="form-group">
        <label for="email">{{ __('email') }}</label>
        <input type="email" class="form-control" id="email" name="email" required>
    </div>

    <div class="form-group">
        <label for="password">{{ __('Password') }}</label>
        <input type="password" class="form-control" id="password" name="password" required>
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-primary">{{ __('Login') }}</button>
    </div>
</form>

<div class="mt-3">
    <a href="{{ route('user.registerTeacher') }}">Don't have an account? Register here</a>
</div>
