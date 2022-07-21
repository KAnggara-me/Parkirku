@extends('layouts.main')

@section('content')
  @if ($data->status == 5)
    <div class="alert alert-danger h3 text-center">
      <h3>Plat Tidak Sesuai</h3>
    </div>
  @elseif ($data->status == 4)
    <div class="alert alert-info h3 text-center">
      <h3>Plat Sesuai</h3>
    </div>
  @elseif ($data->status == 3)
    <div class="alert alert-info h3 text-center">
      <h3>Plat Diproses</h3>
    </div>
  @elseif ($data->status == 2)
    <div class="alert alert-info h3 text-center">
      <h3>User Telah Masuk & Plat Didapatkan</h3>
    </div>
  @elseif ($data->status == 1)
    <div class="alert alert-primary h3 text-center">
      <h3>User Telah Masuk</h3>
    </div>
  @elseif ($data->status == 0)
    <div class="alert alert-info h3 text-center">
      <h3>User Telah Keluar</h3>
    </div>
  @endif
  <div class="row row-cols-1 row-cols-md-2 g-4 mb-5">
    <div class="col">
      <div class="card">
        <h5 class="text-success mt-2 text-center">Masuk</h5>
        <img id="loginPhoto" src="storage/{{ $data->image1 }}" onerror="this.onerror=null; this.src='img/image.png'" alt="{{ $data->plate1 }}">
        <div class="card-body">
          <h4 class="card-title">Plat : {{ $data->plate1 }}</h4>
          <hr>
          <h5 class="card-title">UID : {{ $data->uid }}</h5>
          <h5 class="card-title">Masuk pada : {{ $data->login }}</h5>
        </div>
      </div>
    </div>

    <div class="col">
      <div class="card">
        <h5 class="text-danger mt-2 text-center">Keluar</h5>
        <img id="logoutPhoto" src="storage/{{ $data->image2 }}" onerror="this.onerror=null; this.src='img/image.png'" alt="{{ $data->plate1 }}">
        <div class="card-body">
          <h4 class="card-title">Plat : {{ $data->plate2 }}</h4>
          <hr>
          <h5 class="card-title">UID : {{ $data->uid }}</h5>
          <h5 class="card-title">Keluar pada : {{ $data->logout != null ? $data->logout : 'Belum Keluar' }}</h5>
        </div>
      </div>
    </div>
  </div>
@endsection
