@extends('layout.anggota')
@section('title','Beranda')
@section('content')


    <!--contentawal-->



            <div class="container-fluid">
                <div class="hero p-4 mb-4 rounded-3" style="background: linear-gradient(90deg,#eef2ff 0%, #fff 60%);">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h2 class="mb-1">Halaman Beranda</h2>
                            <p class="mb-0 text-muted">Selamat Datang <strong>{{ Auth::user()->nama }}</strong> di Perpustakaan sebagai <strong>{{ Auth::user()->role == 1 ? 'Admin' : 'Anggota' }}</strong></p>
                        </div>
                        <div class="text-end">
                            <img src="https://i.ibb.co/9VhZ0Qw/reading-illustration.png" alt="reading" style="height:110px; object-fit:contain;">
                        </div>
                    </div>

                    <div class="row mt-4 g-3">
                        <div class="col-md-4">
                            <div class="card shadow-sm border-0">
                                <div class="card-body d-flex align-items-center">
                                    <div class="me-3 p-3 rounded-3" style="background:#ecfeff; color:#0f766e;">
                                        <i class="bi bi-star" style="font-size:1.6rem"></i>
                                    </div>
                                    <div>
                                        <div class="text-muted small">Buku Favorit</div>
                                        <div class="fw-bold">Lihat buku-buku favorit Anda</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card shadow-sm border-0">
                                <div class="card-body d-flex align-items-center">
                                    <div class="me-3 p-3 rounded-3" style="background:#eef2ff; color:#1e40af;">
                                        <i class="bi bi-arrow-left-right" style="font-size:1.6rem"></i>
                                    </div>
                                    <div>
                                        <div class="text-muted small">Dipinjam Sekarang</div>
                                        <div class="fw-bold">Lihat buku yang sedang Anda pinjam</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card shadow-sm border-0">
                                <div class="card-body d-flex align-items-center">
                                    <div class="me-3 p-3 rounded-3" style="background:#fff7ed; color:#92400e;">
                                        <i class="bi bi-bell" style="font-size:1.6rem"></i>
                                    </div>
                                    <div>
                                        <div class="text-muted small">Pemberitahuan</div>
                                        <div class="fw-bold">Lihat pemberitahuan terbaru Anda</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h5 class="mb-0">Buku Populer</h5>
                        <small class="text-muted">Rekomendasi untuk Anda</small>
                    </div>

                    <div class="overflow-auto" style="white-space:nowrap;">
                        @php
                            $covers = [
                                'https://picsum.photos/200/300?random=11',
                                'https://picsum.photos/200/300?random=12',
                                'https://picsum.photos/200/300?random=13',
                                'https://picsum.photos/200/300?random=14',
                                'https://picsum.photos/200/300?random=15',
                            ];
                        @endphp

                        @foreach($covers as $cover)
                            <div class="card me-3 d-inline-block" style="width:140px;">
                                <img src="{{ $cover }}" class="card-img-top" alt="cover">
                                <div class="card-body p-2">
                                    <p class="card-title mb-1" style="font-size:0.85rem">Judul Buku</p>
                                    <p class="text-muted small mb-0">Pengarang</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>

        @endsection