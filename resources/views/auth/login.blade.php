<x-guest-layout>

<form method="POST" action="{{ route('login') }}">
    @csrf

    <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" value="{{ old('email') }}" required>
        <div class="error">
            <x-input-error :messages="$errors->get('email')" />
        </div>
    </div>

    <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" required>
        <div class="error">
            <x-input-error :messages="$errors->get('password')" />
        </div>
    </div>

    <div class="remember-row">
        <input type="checkbox" name="remember">
        <span>Remember me</span>
    </div>

    @if (Route::has('password.request'))
        <a class="forgot" href="{{ route('password.request') }}">
            Forgot your password?
        </a>
    @endif

    <div style="margin-top:15px;">
        <button type="submit" class="btn-primary">
            Log in
        </button>
    </div>

</form>

</x-guest-layout>
