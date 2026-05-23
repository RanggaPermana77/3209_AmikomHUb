@extends('layouts.admin')

@section('content')
<div class="p-6">

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Manajemen Partner</h2>
        <a href="{{ route('admin.partners.create') }}"
           class="bg-indigo-600 text-white px-4 py-2 rounded font-semibold hover:bg-indigo-700">
            Tambah Partner
        </a>
    </div>

    <!-- Search Form -->
    <div class="mb-6">
        <form action="{{ route('admin.partners.index') }}" method="GET" class="flex gap-2">
            <input type="text" name="search" placeholder="Cari partner..." value="{{ request('search') }}"
                   class="flex-1 border border-gray-300 px-4 py-2 rounded focus:ring focus:ring-indigo-200">
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded font-semibold hover:bg-indigo-700">
                Cari
            </button>
            @if(request('search'))
                <a href="{{ route('admin.partners.index') }}" class="bg-gray-400 text-white px-6 py-2 rounded font-semibold hover:bg-gray-500">
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
                    <th class="p-4 font-semibold text-gray-600">Nama Partner</th>
                    <th class="p-4 font-semibold text-gray-600">Logo</th>
                    <th class="p-4 font-semibold text-gray-600">Aksi Pilihan</th>
                </tr>
            </thead>

            <tbody>
                @foreach($partners as $partner)
                <tr class="border-b border-gray-100 hover:bg-gray-50">
                    <td class="p-4 text-gray-800">
                        {{ $partner->name }}
                    </td>

                    <td class="p-4">
                        @if($partner->logo_url)
                            <img src="{{ $partner->logo_url }}" alt="{{ $partner->name }}" 
                                 class="w-16 h-16 object-cover rounded border border-gray-200">
                        @else
                            <span class="text-gray-400">Tidak ada logo</span>
                        @endif
                    </td>

                    <td class="p-4 flex gap-2">
                        <a href="{{ route('admin.partners.edit', $partner->id) }}"
                           class="bg-blue-50 text-blue-600 border border-blue-200 px-3 py-1.5 rounded text-sm font-semibold hover:bg-blue-600 hover:text-white transition">
                            Edit Data
                        </a>
                        <form action="{{ route('admin.partners.destroy', $partner->id) }}"
                              method="POST"
                              onsubmit="return confirm('Anda yakin ingin menghapus data partner ini secara permanen?');">
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
