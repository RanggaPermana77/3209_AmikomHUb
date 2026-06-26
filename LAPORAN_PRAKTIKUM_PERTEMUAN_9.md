# LAPORAN PRAKTIKUM PERTEMUAN 9
## VALIDATION & FILE UPLOAD
### Digital Business (SI148)
**Universitas AMIKOM Yogyakarta**  
**Program Studi S1 Sistem Informasi**

---

## 1. PENDAHULUAN

Praktikum ini bertujuan untuk menerapkan sistem validasi input form yang aman dan pengunggahan berkas (File Upload) untuk gambar poster kegiatan pada aplikasi AmikomEventHub. Fitur ini mengintegrasikan symlink folder publik (`storage:link`), pembersihan berkas lama saat pembaruan data, serta validasi ketat tipe data, harga minimum, dan kapasitas stok.

---

## 2. TUJUAN PEMBELAJARAN

Setelah menyelesaikan praktikum ini, praktikan diharapkan dapat:

✅ **Memahami mekanisme pengamanan input form** menggunakan sistem validasi bawaan Laravel.  
✅ **Mengimplementasikan pengunggahan berkas (File Upload)** secara spesifik untuk memproses gambar poster kegiatan.  
✅ **Menautkan sistem penyimpanan privat** dengan aksesibilitas publik menggunakan `storage:link`.  
✅ **Memperbarui kode penambahan (Create) dan pengubahan (Update)** dengan integrasi upload dan penghapusan berkas gambar lama.  

---

## 3. IMPLEMENTASI TAHAP DEMI TAHAP

### 3.1 Persiapan Storage Symlink
Pembuatan symlink bertujuan menghubungkan folder penyimpanan privat `storage/app/public` dengan direktori publik `public/storage` agar file gambar dapat dipanggil secara langsung oleh browser.
```bash
php artisan storage:link
```
*Hasil:* Terbentuk tautan simbolis di folder `public/storage` yang mengarah langsung ke `storage/app/public`.

---

### 3.2 Modifikasi View Form Tambah & Edit Event
Form HTML dimodifikasi dengan menambahkan atribut `enctype="multipart/form-data"` agar form dapat mengirimkan data biner berupa berkas (file), serta ditambahkan input field bertipe `file` dengan nama `poster`.

**Form Tambah Event (`resources/views/admin/events/create.blade.php`):**
```html
<form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data" ...>
    ...
    <div class="mb-6">
        <label class="block mb-2 font-medium text-gray-700">Poster Event (Opsional)</label>
        <input type="file" name="poster" accept="image/*" class="w-full border border-gray-300 p-2.5 rounded">
    </div>
    ...
</form>
```

**Form Edit Event (`resources/views/admin/events/edit.blade.php`):**
```html
<form action="{{ route('admin.events.update', $event->id) }}" method="POST" enctype="multipart/form-data" ...>
    @csrf
    @method('PUT')
    ...
    <div class="mb-6">
        <label class="block mb-2 font-medium text-gray-700">Poster Event (Opsional)</label>
        @if($event->poster_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($event->poster_path))
            <div class="mb-4">
                <img src="{{ asset('storage/' . $event->poster_path) }}" alt="Current Poster" class="w-32 h-48 object-cover rounded">
                <p class="text-sm text-gray-500 mt-2">Poster saat ini</p>
            </div>
        @endif
        <input type="file" name="poster" accept="image/*" class="w-full border border-gray-300 p-2.5 rounded">
    </div>
    ...
</form>
```

---

### 3.3 Penyesuaian Method store() dan update() pada EventController
Logika penyimpanan dan pembaruan pada `app/Http/Controllers/Admin/EventController.php` disesuaikan untuk menerapkan validasi berkas gambar maksimal 2MB (`nullable|image|max:2048`) serta membersihkan berkas lama saat poster diganti.

**Metode `store()`:**
```php
public function store(Request $request)
{
    $data = $request->validate([
        'category_id' => 'required|exists:categories,id',
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'date' => 'required|date',
        'location' => 'required|string|max:255',
        'price' => 'required|numeric|min:0',
        'stock' => 'required|numeric|min:1',
        'poster' => 'nullable|image|max:2048'
    ]);

    if ($request->hasFile('poster')) {
        $data['poster_path'] = $request->file('poster')->store('posters', 'public');
    }

    \App\Models\Event::create($data);

    return redirect()->route('admin.events.index')->with('success', 'Data Event berhasil ditambahkan.');
}
```

**Metode `update()`:**
```php
public function update(Request $request, Event $event)
{
    $data = $request->validate([
        'category_id' => 'required|exists:categories,id',
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'date' => 'required|date',
        'location' => 'required|string|max:255',
        'price' => 'required|numeric|min:0',
        'stock' => 'required|numeric|min:1',
        'poster' => 'nullable|image|max:2048'
    ]);

    if ($request->hasFile('poster')) {
        if ($event->poster_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($event->poster_path);
        }
        $data['poster_path'] = $request->file('poster')->store('posters', 'public');
    }

    $event->update($data);

    return redirect()->route('admin.events.index')->with('success', 'Event berhasil diperbarui.');
}
```

---

### 3.4 Menampilkan Gambar Poster secara Dinamis
Ternary operator digunakan untuk memeriksa keberadaan file poster di media penyimpanan. Jika poster tidak ada, placeholder/dummy image dari `placehold.co` akan digunakan sebagai fallback.

**1. Halaman Index Admin (`resources/views/admin/events/index.blade.php`):**
```html
<img src="{{ ($event->poster_path && Storage::disk('public')->exists($event->poster_path))
    ? asset('storage/' . $event->poster_path)
    : 'https://placehold.co/16x20' }}" class="w-16 h-20 rounded-xl object-cover shadow-sm">
```

**2. Halaman Homepage (`resources/views/welcome.blade.php`):**
```html
<img src="{{ ($event->poster_path && Storage::disk('public')->exists($event->poster_path))
    ? asset('storage/' . $event->poster_path)
    : 'https://placehold.co/200x600' }}" alt="{{ $event->title }}"
     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
```

**3. Halaman Detail Event (`resources/views/event-detail.blade.php`):**
```html
<img src="{{ ($event->poster_path && Storage::disk('public')->exists($event->poster_path))
    ? asset('storage/' . $event->poster_path)
    : 'https://placehold.co/200x600' }}" alt="{{ $event->title }}"
     class="w-full rounded-[2.5rem] shadow-2xl border-8 border-white object-cover aspect-[3/4]">
```

---

## 4. JAWABAN LATIHAN / TUGAS

### 4.1 Pengisian Event Baru & File Upload
Proses input event baru berjalan dengan sukses. Berkas poster yang diunggah disimpan di direktori `storage/app/public/posters`. Laravel secara otomatis mengubah nama berkas menggunakan hash string unik (misal: `jD1oD43bN8gLp7qR...png`) untuk mencegah duplikasi atau tabrakan nama berkas di server.

---

### 4.2 Analisis Validasi Harga Tiket Negative (`-5`)

**Pertanyaan:** *Coba masukkan angka -5 pada input Harga Tiket. Apakah proses penyimpanan ditolak berkat adanya fungsi validasi min:0? Beri penjelasan.*

**Jawaban:**  
**Ya, proses penyimpanan ditolak secara otomatis.**  
Fungsi `validate()` di Controller mengevaluasi input `price` dengan aturan `min:0`. Ketika admin menginput angka `-5` (yang bernilai kurang dari 0):
1. Laravel mendeteksi pelanggaran aturan validasi `min:0`.
2. Laravel langsung menghentikan proses eksekusi kode (kode pembuat database `Event::create()` tidak dipanggil).
3. Laravel melempar `ValidationException` dan mengarahkan pengguna kembali ke halaman form sebelumnya (`redirect back`) beserta pesan kesalahan `"The price field must be at least 0."` di dalam session error.
4. Database tetap aman dari data harga yang tidak valid (jumlah data event di database tetap 0).

Hal ini juga telah diverifikasi menggunakan pengujian terotomatisasi PHPUnit.

---

## 5. TESTING & VERIFIKASI (AUTOMATED TESTS)

Untuk memastikan keandalan fungsi validasi dan pengunggahan berkas, kami membuat Unit/Feature Test khusus di `tests/Feature/EventUploadTest.php`.

**Hasil Pengujian PHPUnit:**
```bash
php artisan test --filter=EventUploadTest
```

```text
   PASS  Tests\Feature\EventUploadTest
  ✓ it rejects negative ticket price                                                                             1.24s  
  ✓ it uploads event poster and stores correctly                                                                 0.11s  
  ✓ it deletes old poster when updating new one                                                                  0.08s  
  ✓ it displays event details dinamically                                                                        0.09s  

  Tests:    4 passed (24 assertions)
  Duration: 1.77s
```

**Penjelasan Test Cases:**
1. `it_rejects_negative_ticket_price`: Memastikan input `-5` pada harga memicu kegagalan validasi dan menolak entri baru ke basis data.
2. `it_uploads_event_poster_and_stores_correctly`: Memverifikasi berkas poster berhasil diunggah ke storage fiktif (`Storage::fake`) dan path-nya disimpan di database.
3. `it_deletes_old_poster_when_updating_new_one`: Memverifikasi file poster lama di-delete dari disk saat admin mengunggah poster baru pada operasi Update.
4. `it_displays_event_details_dinamically`: Memastikan semua field dinamis (judul, kategori, tanggal format `d M Y, H:i`, lokasi, harga terformat rupiah, kapasitas tiket, dan URL checkout dinamis) dirender dengan benar di halaman detail.

---

## 6. KESIMPULAN

Sistem keamanan input form dan pengunggahan gambar poster di aplikasi AmikomEventHub telah berjalan 100% sukses. Keamanan data terjamin dari input yang tidak logis (seperti harga negatif), dan efisiensi ruang penyimpanan terjaga karena berkas poster lama dihapus otomatis ketika poster baru diunggah.

---

**Tanggal Pengerjaan:** 10 Juni 2026  
**Status:**  Selesai dan Terverifikasi ✅  
