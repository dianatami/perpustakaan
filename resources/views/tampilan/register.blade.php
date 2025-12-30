<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

    <h3>{{$judul}}</h3>
    @if (session()->has('error'))
    <div class="alert alert-danger alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <strong>{{ session('error')}}</strong>
    </div>
    @endif
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('message')}}
        </div>
    @endif
    <form action="{{route('tampilan.register')}}" method="POST">
        @csrf
        <label>Nama</label><br>
        <input type="text" name="nama" id="" required><br>
        <label>Email</label><br>
        <input type="email" name="email" id="" required><br>
        <label>Password</label><br>
        <input type="Password" name="password" id="" required><br>
        <label>No Handphone</label><br>
        <input type="text" name="hp" id="" required><br>
        <br>
        <button type="submit">Register</button><br>
        <a href="{{route('tampilan.login')}}">Login</a>
    </form>

</body>
</html>