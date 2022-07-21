@extends('layouts.main')

@section('content')
  @if ($message = Session::get('success'))
    <div class="alert alert-danger alert-block">
      <strong>{{ $message }}</strong>
    </div>
  @endif
  <table class="table-hover table-responsive table text-center">
    <tr>
      <td class="table-danger">Plat/UID tidak sesuai</td>
      <td class="table-warning">Plat Sesuai</td>
      <td class="table-secondary">Plat Diperoses</td>
      <td class="table-primary">Plat didapatkan</td>
      <td class="table-info">User Telah Masuk</td>
      <td class="table-success">User telah Keluar</td>
    </tr>
  </table>
  <table class="table-info table-hover table-striped-columns table-responsive mt-2 table text-center">
    <thead class="table-dark">
      <tr>
        <th scope="col">UID</th>
        <th scope="col">Login Plate</th>
        <th scope="col">Logout Plate</th>
        <th scope="col">Login Time</th>
        <th scope="col">Logout Time</th>
        <th scope="col">Status</th>
        <th scope="col">Action</th>
      </tr>
    </thead>
    <tbody>

      @foreach ($data as $d)
        @if ($d->status == 5)
          <tr class="table-danger">
          @elseif ($d->status == 4)
          <tr class="table-warning">
          @elseif ($d->status == 3)
          <tr class="table-secondary">
          @elseif ($d->status == 2)
          <tr class="table-primary">
          @elseif ($d->status == 1)
          <tr class="table-info">
          @elseif ($d->status == 0)
          <tr class="table-success">
        @endif
        <th scope="row" class="fst-italic">{{ strtolower($d->uid) }}</th>
        <td>{{ strtoupper($d->plate1) }}</td>
        <td>{{ strtoupper($d->plate2) }}</td>
        <td>{{ strtoupper($d->login) }}</td>
        <td>{{ strtoupper($d->logout) }}</td>
        @if ($d->status == 5)
          <td class="table-danger">Plat/UID tidak sesuai</td>
        @elseif ($d->status == 4)
          <td class="table-warning">Plat Sesuai</td>
        @elseif ($d->status == 3)
          <td class="table-secondary">Plat Diperoses</td>
        @elseif ($d->status == 2)
          <td class="table-primary">Plat didapatkan</td>
        @elseif ($d->status == 1)
          <td class="table-info">User Telah Masuk</td>
        @elseif ($d->status == 0)
          <td class="table-success">User telah Keluar</td>
        @endif
        <td>
          <a type="button" class="btn btn-primary" href="/detail/{{ strtolower($d->uid) }}">Detail</a>
          <a type="button" class="btn btn-danger" href="/delete/{{ strtolower($d->uid) }}">Delete</a>
        </td>
        </tr>
      @endforeach

    </tbody>
  </table>
@endsection
