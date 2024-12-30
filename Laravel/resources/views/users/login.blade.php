
<x-layout>
    <section id='section-login-form'>
        <h1>Login</h1>
        <form method="POST" action="/users/authenticate">
            @csrf
            <div>
                <label for='email' >* email</label>
                <input type='email' name='email' id='email' value="{{old('email')}}">
                @error('email')
                    <p>{{$message}}</p>
                @enderror
            </div>
            <div>
                <label for='login-password' >* password</label>
                <input type='password' name='password' id='login-password' >
                @error('password')
                    <p>{{$message}}</p>
                @enderror
            </div>
            <input type="submit" name='login-submit' id='login-submit' value='login'>
        </form>
    </section>
</x-layout>