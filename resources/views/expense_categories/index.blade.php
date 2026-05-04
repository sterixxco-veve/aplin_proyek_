@extends('layouts.app')

@section('content')
<div class="container">

    <h2>Master Kategori Expense</h2>

    {{-- FORM TAMBAH --}}
    <form method="POST" action="/expense-categories" class="mb-4">
        @csrf
        <input type="text" name="nama_kategori" placeholder="Nama kategori" required>
        <button type="submit">Tambah</button>
    </form>

    {{-- LIST --}}
    @foreach($categories as $cat)
        <div style="display:flex; justify-content:space-between; margin-bottom:10px;">
            <span>{{ $cat->nama_kategori }}</span>

            <form method="POST" action="/expense-categories/{{ $cat->id_expense_category }}">
                @csrf
                @method('DELETE')
                <button type="submit">Hapus</button>
            </form>
        </div>
    @endforeach

</div>
@endsection