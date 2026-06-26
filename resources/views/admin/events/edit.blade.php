@extends('layouts.admin')

@section('content')
<div class="p-6 max-w-4xl mx-auto">

    <h2 class="text-2xl font-bold mb-6 text-gray-800">
        Menyunting Pengaturan Event
    </h2>

    <form action="{{ route('admin.events.update', $event->id) }}"
          method="POST"
          enctype="multipart/form-data"
          class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block mb-2 font-medium text-gray-700">Judul Event</label>
            <input type="text" name="title" value="{{ old('title', $event->title) }}"
                   class="w-full border border-gray-300 p-2.5 rounded focus:ring focus:ring-blue-200"
                   required>
            @error('title')
                <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block mb-2 font-medium text-gray-700">Kategori Event</label>
            <select name="category_id"
                    class="w-full border border-gray-300 p-2.5 rounded"
                    required>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}"
                        {{ old('category_id', $event->category_id) == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id')
                <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block mb-2 font-medium text-gray-700">Deskripsi Pendek</label>
            <textarea name="description"
                      class="w-full border border-gray-300 p-2.5 rounded"
                      rows="3"
                      required>{{ old('description', $event->description) }}</textarea>
            @error('description')
                <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-4">
            <div>
                <label class="block mb-2 font-medium text-gray-700">Tanggal & Waktu</label>
                <input type="datetime-local" name="date"
                       value="{{ old('date', $event->date) }}"
                       class="w-full border border-gray-300 p-2.5 rounded"
                       required>
                @error('date')
                    <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block mb-2 font-medium text-gray-700">Rencana Harga Masuk (Rp)</label>
                <input type="number" name="price"
                       value="{{ old('price', $event->price) }}"
                       class="w-full border border-gray-300 p-2.5 rounded"
                       required>
                @error('price')
                    <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block mb-2 font-medium text-gray-700">Kapasitas Stok Kuota</label>
                <input type="number" name="stock"
                       value="{{ old('stock', $event->stock) }}"
                       class="w-full border border-gray-300 p-2.5 rounded"
                       required>
                @error('stock')
                    <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="mb-6">
            <label class="block mb-2 font-medium text-gray-700">Lokasi / Gedung</label>
            <input type="text" name="location"
                   value="{{ old('location', $event->location) }}"
                   class="w-full border border-gray-300 p-2.5 rounded"
                   required>
            @error('location')
                <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-6">
            <label class="block mb-2 font-medium text-gray-700">Poster Event (Opsional)</label>
            @if($event->poster_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($event->poster_path))
                <div class="mb-4">
                    <img src="{{ asset('storage/' . $event->poster_path) }}" alt="Current Poster" class="w-32 h-48 object-cover rounded">
                    <p class="text-sm text-gray-500 mt-2">Poster saat ini</p>
                </div>
            @endif
            <input type="file" name="poster" accept="image/*" class="w-full border border-gray-300 p-2.5 rounded">
            <p class="text-sm text-gray-500 mt-2">Pilih file baru untuk mengganti poster</p>
            @error('poster')
                <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        <div class="flex justify-end border-t pt-4">
            <button type="submit"
                    class="bg-blue-600 text-white px-8 py-2.5 rounded font-semibold hover:bg-blue-700 shadow-md">
                Simpan Perubahan
            </button>
        </div>

    </form>
</div>
@endsection