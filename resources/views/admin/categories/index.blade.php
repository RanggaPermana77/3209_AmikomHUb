@extends('layouts.admin')

@section('content')
<header class="flex justify-between items-center mb-10">
    <div>
        <h1 class="text-3xl font-black">Kelola Kategori</h1>
        <p class="text-slate-500 font-medium">Tambah dan atur kategori event di sini.</p>
    </div>
    <button
        class="px-6 py-3 bg-indigo-600 text-white rounded-2xl font-bold shadow-lg shadow-indigo-100 hover:bg-indigo-700 active:scale-95 transition flex items-center gap-2">
        <i class="fa-solid fa-plus w-5 h-5"></i>
        Tambah Kategori
    </button>
</header>

<div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-lg overflow-hidden">
    <div class="px-8 py-6 bg-gradient-to-r from-indigo-50 to-indigo-100 border-b border-indigo-200 flex gap-4">
        <input type="text" placeholder="Cari kategori..."
            class="flex-1 px-5 py-3 rounded-xl border-slate-200 border bg-white focus:ring-2 focus:ring-indigo-500 outline-none transition">
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gradient-to-r from-indigo-600 to-indigo-700 text-white uppercase text-[10px] font-black tracking-widest">
                <tr>
                    <th class="px-8 py-4 w-16">No</th>
                    <th class="px-8 py-4">Nama Kategori</th>
                    <th class="px-8 py-4">Jumlah Event</th>
                    <th class="px-8 py-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-8 py-6 font-bold text-indigo-600">1</td>
                    <td class="px-8 py-6">
                        <p class="font-bold">Musik</p>
                    </td>
                    <td class="px-8 py-6">
                        <span class="text-slate-600 font-medium">12 event</span>
                    </td>
                    <td class="px-8 py-6 flex gap-2">
                        <button class="px-3 py-2 bg-blue-100 text-blue-600 rounded-lg font-bold text-sm hover:bg-blue-200 transition">
                            <i class="fa-solid fa-pen-to-square w-4 h-4"></i>
                        </button>
                        <button class="px-3 py-2 bg-red-100 text-red-600 rounded-lg font-bold text-sm hover:bg-red-200 transition">
                            <i class="fa-solid fa-trash w-4 h-4"></i>
                        </button>
                    </td>
                </tr>

                <tr class="hover:bg-slate-50 transition">
                    <td class="px-8 py-6 font-bold text-indigo-600">2</td>
                    <td class="px-8 py-6">
                        <p class="font-bold">Workshop</p>
                    </td>
                    <td class="px-8 py-6">
                        <span class="text-slate-600 font-medium">8 event</span>
                    </td>
                    <td class="px-8 py-6 flex gap-2">
                        <button class="px-3 py-2 bg-blue-100 text-blue-600 rounded-lg font-bold text-sm hover:bg-blue-200 transition">
                            <i class="fa-solid fa-pen-to-square w-4 h-4"></i>
                        </button>
                        <button class="px-3 py-2 bg-red-100 text-red-600 rounded-lg font-bold text-sm hover:bg-red-200 transition">
                            <i class="fa-solid fa-trash w-4 h-4"></i>
                        </button>
                    </td>
                </tr>

                <tr class="hover:bg-slate-50 transition">
                    <td class="px-8 py-6 font-bold text-indigo-600">3</td>
                    <td class="px-8 py-6">
                        <p class="font-bold">Olahraga</p>
                    </td>
                    <td class="px-8 py-6">
                        <span class="text-slate-600 font-medium">5 event</span>
                    </td>
                    <td class="px-8 py-6 flex gap-2">
                        <button class="px-3 py-2 bg-blue-100 text-blue-600 rounded-lg font-bold text-sm hover:bg-blue-200 transition">
                            <i class="fa-solid fa-pen-to-square w-4 h-4"></i>
                        </button>
                        <button class="px-3 py-2 bg-red-100 text-red-600 rounded-lg font-bold text-sm hover:bg-red-200 transition">
                            <i class="fa-solid fa-trash w-4 h-4"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection