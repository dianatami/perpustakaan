@extends('layout.admin')
@section('content')
@section('title','Beranda')

    <!--contentawal-->

<h3>{{$judul}}</h3>
<p>
    Selamat Datang <b>{{Auth::user()->nama}}</b> di Perpustakaan sebagai 
    <b>@if (Auth::user()->role ==1)
        Admin
        @elseif (Auth::user()->role ==0)
        Anggota
    @endif
</b>

</p>
    <!--contentakhir-->

@endsection