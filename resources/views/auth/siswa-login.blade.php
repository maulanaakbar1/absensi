<h2>Login Siswa</h2>

@if(session('error'))
    <p style="color:red">{{ session('error') }}</p>
@endif

<form method="POST" action="{{ route('siswa.login.submit') }}">
    @csrf
    <input type="email" name="email" placeholder="Email" required>
    <br><br>
    <input type="password" name="password" placeholder="Password" required>
    <br><br>
    <button type="submit">Login</button>
</form>