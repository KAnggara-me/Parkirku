@extends('layouts.main')

@section('content')
  <table class="table-info table-hover table-striped-columns table-responsive mt-2 table text-center">
    <thead class="table-dark">
      <tr>
        <th scope="col">Tanggal</th>
        <th scope="col">Pengguna Masuk</th>
        <th scope="col">Pengguna Keluar</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($data as $key => $uid)
        <tr class="table-info">
          <td>{{ $data = $key }}</td>
          <td>{{ $uid->count() }}</td>
          <td>{{ $uid->count() }}</td>
        </tr>
    </tbody>
    @endforeach
  </table>
@endsection
