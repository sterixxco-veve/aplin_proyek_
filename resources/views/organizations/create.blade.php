@extends('layouts.app')

@section('content')
<div class="container">

    <h2>Buat Organization</h2>

    <form method="POST" action="/organizations" enctype="multipart/form-data">
        @csrf

        <div>
            <label>Nama Organization</label><br>
            <input type="text" name="nama_org" required>
        </div>

        <div style="margin-top:10px;">
            <label>Logo</label><br>
            <input type="file" name="logo">
        </div>

        <button type="submit" style="margin-top:15px;">
            Simpan
        </button>
    </form>

</div>
@endsection