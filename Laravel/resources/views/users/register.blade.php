<x-layout>
    <section id='section-register-form'>
        <h1>Register</h1>
        <form method="POST" action="/users">
            @csrf
            <div>
                <label for='regsiter-name' >* name</label>
                <input type='text' name='name' id='regsiter-name' value="{{old('username')}}">
                @error('username')
                    <p>{{$message}}</p>
                @enderror
            </div>
            <div>
                <label for='regsiter-email' >* email</label>
                <input type='email' name='email' id='regsiter-email' value="{{old('email')}}">
                @error('email')
                    <p>{{$message}}</p>
                @enderror
            </div>
            <div>
                <label for='regsiter-password' >* password</label>
                <input type='password' name='password' id='regsiter-password' value="{{old('password')}}">
                @error('password')
                    <p>{{$message}}</p>
                @enderror
            </div>
            <div>
                <label for='password_confirmation' >* repeat password</label>
                <input type='password' name='password_confirmation' id='password_confirmation'>
                @error('password_confirmation')
                    <p>{{$message}}</p>
                @enderror
            </div>
            <input type="submit" name='register-submit' id='register-submit' value='Register'>
        </form>
    </section>
</x-layout>