@extends('layouts.admin')

@section('content')
<div class="p-6">

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Manajemen Event</h2>
        <a href="{{ route('admin.events.create') }}"
           class="bg-indigo-600 text-white px-4 py-2 rounded font-semibold hover:bg-indigo-700">
            Tambah Event
        </a>
    </div>

    <!-- Search Form -->
    <div class="mb-6">
        <form action="{{ route('admin.events.index') }}" method="GET" class="flex gap-2">
            <input type="text" name="search" placeholder="Cari event..." value="{{ request('search') }}"
                   class="flex-1 border border-gray-300 px-4 py-2 rounded focus:ring focus:ring-indigo-200">
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded font-semibold hover:bg-indigo-700">
                Cari
            </button>
            @if(request('search'))
                <a href="{{ route('admin.events.index') }}" class="bg-gray-400 text-white px-6 py-2 rounded font-semibold hover:bg-gray-500">
                    Reset
                </a>
            @endif
        </form>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-4 rounded mb-5 border border-green-200">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="w-full bg-white rounded-lg shadow-sm border border-gray-200 text-left">
            
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200">
                    <th class="p-4 font-semibold text-gray-600">Poster</th>
                    <th class="p-4 font-semibold text-gray-600">Judul Event</th>
                    <th class="p-4 font-semibold text-gray-600">Kategori</th>
                    <th class="p-4 font-semibold text-gray-600">Tanggal</th>
                    <th class="p-4 font-semibold text-gray-600">Aksi Pilihan</th>
                </tr>
            </thead>

            <tbody>
                @foreach($events as $event)
                <tr class="border-b border-gray-100 hover:bg-gray-50">
                    <td class="p-4">
                        <img src="{{ ($event->poster_path && Storage::disk('public')->exists($event->poster_path))
                            ? asset('storage/' . $event->poster_path)
                            : 'https://placehold.co/16x20' }}" class="w-16 h-20 rounded-xl object-cover shadow-sm">
                    </td>
                    <td class="p-4 text-gray-800">
                        {{ $event->title }}
                    </td>

                    <td class="p-4 text-indigo-600">
                        {{ $event->category->name ?? '-' }}
                    </td>

                    <td class="p-4 text-gray-600">
                        {{ \Carbon\Carbon::parse($event->date)->format('d M Y, H:i') }}
                    </td>

                    <td class="p-4 flex gap-2">
                        <a href="{{ route('admin.events.edit', $event->id) }}"
                           class="bg-blue-50 text-blue-600 border border-blue-200 px-3 py-1.5 rounded text-sm font-semibold hover:bg-blue-600 hover:text-white transition">
                            Edit Data
                        </a>
                        <form action="{{ route('admin.events.destroy', $event->id) }}"
                              method="POST"
                              onsubmit="return confirm('Anda yakin ingin menghapus data acara ini secara permanen?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="bg-red-100 text-red-600 border border-red-200 px-3 py-1.5 rounded text-sm font-semibold hover:bg-red-600 hover:text-white transition">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>

        </table>
    </div>

</div>
@endsection