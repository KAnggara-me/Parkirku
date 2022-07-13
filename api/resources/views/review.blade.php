@extends('layouts.main')

@section('content')
  @if ($message = Session::get('success'))
    <div class="alert alert-danger alert-block">
      <strong>{{ $message }}</strong>
    </div>
  @endif
  <table class="table-info table-hover table-striped-columns table-responsive mt-5 table text-center">
    <thead class="table-dark">
      <tr>
        <th scope="col">ID</th>
        <th scope="col">UID</th>
        <th scope="col">Login Plate</th>
        <th scope="col">Logout Plate</th>
        <th scope="col">Login Time</th>
        <th scope="col">Logout Time</th>
        <th scope="col">Action</th>
      </tr>
    </thead>
    <tbody>

      @foreach ($data as $d)
        <tr>
          <th scope="row">{{ $d->id }}</th>
          <td class="fst-italic">{{ strtolower($d->uid) }}</td>
          <td>{{ strtoupper($d->plate1) }}</td>
          <td>{{ strtoupper($d->plate2) }}</td>
          <td>{{ strtoupper($d->login) }}</td>
          <td>{{ strtoupper($d->logout) }}</td>
          <td>
            <a type="button" class="btn btn-primary" href="/detail/{{ strtolower($d->uid) }}">Detail</a>
            <a type="button" class="btn btn-danger" href="/delete/{{ strtolower($d->uid) }}">Delete</a>
          </td>
        </tr>
      @endforeach

    </tbody>
  </table>
@endsection
