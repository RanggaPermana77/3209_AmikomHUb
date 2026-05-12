@extends('layouts.admin')

@section('content')
<div class="p-6 max-w-4xl mx-auto">

    <h2 class="text-2xl font-bold mb-6 text-gray-800">
        Form Tambah Kategori
    </h2>

    <form action="{{ route('admin.categories.store') }}" method="POST"
          class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 mt-2">
        @csrf

        <div class="mb-4">
            <label class="block mb-2 font-medium text-gray-700">Nama Kategori</label>
            <input type="text" name="name"
                   class="w-full border border-gray-300 p-2.5 rounded focus:ring focus:ring-indigo-200"
                   placeholder="Contoh: Seminar, Workshop, Konser"
                   required>
            @error('name')
                <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-6">
            <label class="block mb-2 font-medium text-gray-700">Slug (URL Friendly)</label>
            <input type="text" name="slug"
                   class="w-full border border-gray-300 p-2.5 rounded focus:ring focus:ring-indigo-200"
                   placeholder="Contoh: seminar, workshop, konser"
                   required>
            <p class="text-gray-500 text-sm mt-1">Gunakan huruf kecil dan strip (-) untuk spasi</p>
            @error('slug')
                <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
            @enderror
        </div>

        <div class="flex justify-between border-t pt-4">
            <a href="{{ route('admin.categories.index') }}"
               class="bg-gray-200 text-gray-700 px-8 py-2.5 rounded font-semibold hover:bg-gray-300">
                Kembali
            </a>
            <button type="submit"
                    class="bg-indigo-600 text-white px-8 py-2.5 rounded font-semibold hover:bg-indigo-700 shadow">
                Simpan Kategori
            </button>
        </div>

    </form>
</div>
@endsection
