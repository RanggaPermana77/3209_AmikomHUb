# PENJELASAN SEDERHANA SCREENSHOT SISTEM AUTENTIKASI
## Praktikum Pertemuan 8: Route Protection dan Authorization Middleware

**Disusun untuk:** Praktikum Pemrograman Web - Pertemuan 8  
**Tanggal:** 2 Juni 2026  
**NIM:** 24.12.3209  
**Nama:** Rangga Permana Mokoagow

---

## 📌 PENGANTAR

Dokumen ini menjelaskan empat komponen utama dalam sistem login dan proteksi halaman admin. Setiap komponen dijelaskan dengan bahasa sederhana agar mudah dipahami.

---

## 1️⃣ SCREENSHOT: `php artisan make:middleware IsAdmin`

### Apa itu Middleware IsAdmin?

Middleware IsAdmin adalah "penjaga pintu" untuk halaman admin. Fungsinya adalah mengecek apakah user yang membuka halaman adalah admin atau bukan. Jika bukan admin, user tidak boleh masuk.

### Perintah Command

```bash
php artisan make:middleware IsAdmin
```

**Penjelasan perintah:**
- `php artisan`: Alat untuk membuat file-file di Laravel
- `make:middleware`: Perintah untuk membuat middleware baru
- `IsAdmin`: Nama middleware yang akan dibuat

### Hasil Perintah

Perintah di atas akan membuat file bernama `IsAdmin.php` di folder `app/Http/Middleware/`. File ini berisi kode dasar middleware yang siap diisi logika.

### Logika Kerja Middleware IsAdmin

```php
public function handle(Request $request, Closure $next): Response
{
    // CEK 1: Apakah user sudah login?
    // CEK 2: Apakah role user adalah 'admin'?
    
    if (auth()->check() && auth()->user()->role === 'admin') {
        // Jika kedua cek BENAR, user boleh lanjut
        return $next($request);
    }
    
    // Jika ada yang salah, user dihalau ke halaman utama
    return redirect('/')->with('error', 'Akses ditolak.');
}
```

**Cara Kerjanya:**
1. Middleware mengecek apakah ada user yang login (`auth()->check()`)
2. Middleware mengecek apakah role user adalah admin (`auth()->user()->role === 'admin'`)
3. Jika KEDUA kondisi benar, request bisa lanjut ke halaman berikutnya
4. Jika ada yang salah, user dikirim kembali ke halaman utama dengan pesan error

### Alur Middleware

```
User membuka halaman admin
         ↓
Middleware IsAdmin mengecek
    ↓            ↓
  Admin?      Bukan?
    ↓            ↓
  Masuk       Keluar ke Home
```

---

## 2️⃣ SCREENSHOT: HALAMAN LOGIN

### Apa itu Halaman Login?

Halaman login adalah tempat user memasukkan email dan password. Halaman ini akan mengirim data ke server untuk diverifikasi. Jika benar, user akan diizinkan masuk ke halaman admin.

### Struktur Halaman Login

Halaman login memiliki beberapa bagian penting:

**1. Formulir Input**
```html
<form method="POST" action="{{ route('admin.login.post') }}">
    <!-- Email input -->
    <input type="email" name="email" required>
    
    <!-- Password input -->
    <input type="password" name="password" required>
    
    <!-- Tombol submit -->
    <button type="submit">Sign In</button>
</form>
```

**Penjelasan:**
- `type="email"`: Memastikan input yang dimasukkan adalah email yang benar
- `type="password"`: Password tidak akan terlihat di layar (berupa titik-titik)
- `required`: Harus diisi sebelum bisa kirim form

**2. CSRF Token**
```html
@csrf
```

**Apa itu CSRF Token?**
- CSRF = Cross-Site Request Forgery (serangan dari website lain)
- Token adalah kode unik untuk setiap form
- Token ini mencegah orang lain membuat form palsu
- Ketika form dikirim, server memverifikasi token ini

**3. Pesan Error**
```html
@error('email')
    <span class="error-message">{{ $message }}</span>
@enderror
```

**Penjelasan:**
- Jika ada kesalahan (email salah atau password salah), pesan error akan ditampilkan
- Pesan error membantu user tahu apa yang salah

### Desain Halaman Login

```css
/* Background dengan warna gradien (biru ke ungu) */
background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);

/* Form di tengah layar dalam kotak putih */
.login-box {
    background: white;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    border-radius: 8px;
}
```

**Penjelasan:**
- Gradien: Warna background berubah dari biru ke ungu
- Box shadow: Memberikan efek bayangan untuk kedalaman
- Border-radius: Membuat sudut kotak sedikit melengkung

### Alur Proses Login

```
1. User buka halaman login (/admin/login)
             ↓
2. Halaman login ditampilkan
             ↓
3. User masukkan email dan password
             ↓
4. User klik tombol Sign In
             ↓
5. Form dikirim ke server (POST /admin/login)
             ↓
6. Server cek CSRF token (apa token valid?)
             ↓
7. Server cek di database (email dan password cocok?)
    ↓                          ↓
 COCOK                      TIDAK COCOK
    ↓                          ↓
Buat session            Tampilkan error
Redirect ke admin    dan minta input ulang
```

---

## 3️⃣ SCREENSHOT: ROUTE WEB

### Apa itu Routes?

Routes adalah peta jalan yang menghubungkan URL dengan halaman atau aksi. Ketika user membuka URL tertentu, Laravel menggunakan routes untuk mengetahui halaman mana yang harus ditampilkan.

### Struktur Routes dalam File `routes/web.php`

**1. Public Routes (Halaman Publik - Tidak Perlu Login)**

```php
Route::get('/', function () {
    return view('welcome');
})->name('home');
```

**Penjelasan:**
- URL `/` adalah halaman utama
- Siapa saja bisa membuka (tidak perlu login)

**2. Admin Routes Group (Kelompok Route Admin)**

```php
Route::prefix('admin')      // Semua URL dimulai dengan /admin
      ->name('admin.')      // Nama route dimulai dengan admin.
      ->group(function () {
    // Routes admin ada di sini
});
```

**Penjelasan:**
- `prefix('admin')`: Semua route dalam kelompok ini dimulai dengan `/admin`
- `name('admin.')`: Semua nama route ditambahi awalan `admin.`
- Contoh: Jika ada route `login`, namanya menjadi `admin.login` dan URL menjadi `/admin/login`

**3. Login Routes (Route Login - Tidak Perlu Middleware)**

```php
Route::get('login', [AuthController::class, 'showLogin'])
      ->name('login');

Route::post('login', [AuthController::class, 'login'])
      ->name('login.post');

Route::post('logout', [AuthController::class, 'logout'])
      ->name('logout');
```

**Penjelasan:**
- `GET /admin/login`: Menampilkan halaman form login
- `POST /admin/login`: Memproses login (username dan password dikirim)
- `POST /admin/logout`: Logout dari sistem

**Mengapa POST untuk logout?**
- POST lebih aman daripada GET
- Dengan POST, orang tidak bisa membuat link untuk logout
- Ini melindungi dari serangan CSRF

**4. Protected Routes (Route Terlindungi - Perlu Middleware)**

```php
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])
          ->name('dashboard');
    
    Route::get('/dashboard', [DashboardController::class, 'index']);
});
```

**Penjelasan:**
- `middleware(['auth', 'admin'])`: Route ini memiliki dua pemeriksaan:
  - `'auth'`: Mengecek apakah user sudah login
  - `'admin'`: Mengecek apakah user adalah admin
- `/admin`: Halaman utama admin (dashboard)
- `/admin/dashboard`: Alias (jalan alternatif) menuju dashboard

### Alur Middleware pada Routes

```
User membuka /admin/dashboard
           ↓
Middleware 'auth' jalan
├─ Apakah user login?
├─ No: Redirect ke /admin/login
└─ Yes: Lanjut ke middleware berikutnya
           ↓
Middleware 'admin' jalan
├─ Apakah role user adalah 'admin'?
├─ No: Redirect ke home
└─ Yes: Lanjut ke controller
           ↓
Controller jalankan aksi
└─ Dashboard ditampilkan
```

### Keuntungan Menggunakan Routes

1. **Aman**: Tidak semua halaman bisa diakses asal tahu URL
2. **Rapi**: Semua routes terdefinisi jelas di satu tempat
3. **Mudah Diubah**: Jika URL berubah, cukup ubah di routes
4. **Named Routes**: Bisa gunakan `route('admin.login')` tanpa hardcode URL

---

## 4️⃣ SCREENSHOT: HALAMAN DASHBOARD

### Apa itu Dashboard?

Dashboard adalah halaman admin utama yang hanya bisa diakses setelah login. Di sini, admin bisa melihat statistik (total uang, tiket terjual, event aktif, dll) dan daftar transaksi terbaru.

### Struktur Dashboard

**1. Header dengan Sapaan**

```blade
<h1>Admin Panel - AmikomEventHub</h1>
<p>Selamat datang, {{ auth()->user()->name }}</p>
```

**Penjelasan:**
- `auth()->user()->name`: Mengambil nama user yang login dari database
- Middleware sudah memastikan user pasti login, jadi data user pasti ada

**2. Kartu Statistik (Stat Cards)**

```blade
<div class="stat-card">
    <h3>Pendapatan</h3>
    <p>Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
</div>
```

**Penjelasan:**
- `$totalRevenue`: Variabel dari controller (total uang dari transaksi)
- `number_format()`: Format uang agar lebih rapi (Rp 150.000.000)

**3. Tabel Transaksi**

```blade
<table>
    <tr>
        <td>{{ $transaction->id }}</td>
        <td>{{ $transaction->user->name }}</td>
        <td>{{ $transaction->event->name }}</td>
        <td>{{ $transaction->total_price }}</td>
    </tr>
</table>
```

**Penjelasan:**
- `$transaction->id`: Nomor transaksi
- `$transaction->user->name`: Nama pembeli (dari tabel user)
- `$transaction->event->name`: Nama event (dari tabel event)
- `$transaction->total_price`: Total harga

**4. Status Badge (Tanda Status)**

```blade
<span class="status-{{ strtolower($transaction->status) }}">
    {{ ucfirst($transaction->status) }}
</span>
```

**Penjelasan:**
- Status menunjukkan apakah transaksi "completed" (selesai), "pending" (menunggu), atau "cancelled" (dibatalkan)
- Warna berbeda untuk setiap status:
  - Hijau = Selesai
  - Kuning = Menunggu
  - Merah = Dibatalkan

**5. Format Tanggal**

```blade
{{ $transaction->created_at->format('d M Y H:i') }}
```

**Penjelasan:**
- `d` = tanggal (01-31)
- `M` = bulan singkat (Jan, Feb, dll)
- `Y` = tahun 4 digit (2026)
- `H:i` = jam dan menit (14:30)
- Hasil: 02 Jun 2026 14:30

### Logika Controller untuk Dashboard

```php
public function index()
{
    // Hitung total pendapatan dari transaksi yang selesai
    $totalRevenue = Transaction::where('status', 'completed')
        ->sum('total_price');
    
    // Hitung total tiket terjual
    $totalTickets = Transaction::where('status', 'completed')
        ->sum('quantity');
    
    // Hitung event yang masih aktif
    $activeEvents = Event::where('status', 'active')->count();
    
    // Hitung total transaksi
    $totalOrders = Transaction::count();
    
    // Ambil 10 transaksi terbaru
    $transactions = Transaction::with(['user', 'event'])
        ->orderBy('created_at', 'desc')
        ->limit(10)
        ->get();
    
    // Kirim data ke halaman dashboard
    return view('admin.dashboard', [
        'totalRevenue' => $totalRevenue,
        'totalTickets' => $totalTickets,
        'activeEvents' => $activeEvents,
        'totalOrders' => $totalOrders,
        'transactions' => $transactions,
    ]);
}
```

**Penjelasan Baris per Baris:**

| Kode | Arti |
|------|------|
| `Transaction::where('status', 'completed')` | Ambil transaksi yang statusnya "selesai" |
| `.sum('total_price')` | Jumlahkan semua harga transaksi |
| `.where('status', 'active')` | Filter yang statusnya aktif |
| `.count()` | Hitung jumlah data |
| `.orderBy('created_at', 'desc')` | Urut dari yang terbaru |
| `.limit(10)` | Ambil hanya 10 data saja (untuk performa) |
| `.with(['user', 'event'])` | Ambil juga data user dan event yang terkait |

### Alur Data dari Database ke Halaman

```
Database MySQL
    ↓
Query: "Berapa total uang dari transaksi yang selesai?"
    ↓
Hasil: 150000000
    ↓
Controller menerima hasil
    ↓
Format menjadi: Rp 150.000.000
    ↓
Kirim ke view (halaman)
    ↓
Halaman display: Rp 150.000.000
```

### Keamanan Dashboard

Dashboard dilindungi oleh dua layer:

```
Layer 1: Middleware 'auth'
└─ Mengecek: "Apakah user sudah login?"
   ├─ Tidak: Redirect ke login
   └─ Ya: Lanjut

Layer 2: Middleware 'admin'
└─ Mengecek: "Apakah role user adalah admin?"
   ├─ Tidak: Redirect ke home
   └─ Ya: Tampilkan dashboard
```

---

## 📊 RINGKASAN KESELURUHAN

### Bagaimana Sistem Bekerja?

```
STEP 1: User belum login
   ↓
User buka /admin/dashboard
   ↓
Middleware 'auth' tangkap
   ↓
Middleware: "Kamu belum login!"
   ↓
Redirect ke /admin/login

STEP 2: User di halaman login
   ↓
User input email: admin@amikom.ac.id
User input password: password
   ↓
User klik Sign In
   ↓
Server cek database
   ↓
Email dan password cocok!
   ↓
Buat session (tandai user sudah login)
   ↓
Redirect ke /admin

STEP 3: User di dashboard
   ↓
User buka /admin/dashboard
   ↓
Middleware 'auth': "OK, kamu sudah login"
   ↓
Middleware 'admin': "OK, kamu admin"
   ↓
Controller jalankan
   ↓
Query database untuk statistik
   ↓
Format data
   ↓
Tampilkan dashboard dengan data
```

### Lapisan Keamanan

```
1. Password Hashing
   └─ Password disimpan dengan enkripsi (bcrypt)

2. CSRF Protection
   └─ Setiap form memiliki token unik

3. Session Management
   └─ Session dibuat saat login, dihapus saat logout

4. Authentication Middleware
   └─ Mengecek apakah user login

5. Authorization Middleware
   └─ Mengecek apakah user adalah admin

6. Input Validation
   └─ Mengecek format email, password tidak kosong

7. XSS Prevention
   └─ Blade templating mengamankan output HTML
```

### Best Practice yang Digunakan

1. **DRY (Don't Repeat Yourself)**
   - Route grouping menghindari pengulangan URL prefix
   - Template inheritance menghindari pengulangan layout

2. **Separation of Concerns**
   - Controller: Logika bisnis
   - View: Tampilan halaman
   - Middleware: Keamanan

3. **Named Routes**
   - Gunakan `route('admin.login')` bukan hardcode `/admin/login`
   - Jika URL berubah, cukup ubah di routes

4. **Eager Loading**
   - Gunakan `with(['user', 'event'])` untuk menghindari N+1 query problem

5. **Security First**
   - CSRF protection pada setiap form
   - Password hashing
   - Session management

---

## ✅ KESIMPULAN

Keempat komponen ini bekerja bersama membentuk sistem login dan proteksi halaman:

1. **Middleware IsAdmin** → Mengecek role user
2. **Halaman Login** → Tempat user input kredensial
3. **Routes** → Peta jalan menghubungkan URL dengan halaman
4. **Dashboard** → Halaman admin yang terlindungi

Sistem ini memastikan hanya admin yang sudah login yang bisa mengakses halaman admin. Semua proses dilindungi dengan multiple security layers untuk mencegah akses tidak sah.

---

**Dokumen ini dibuat dengan bahasa yang sederhana namun tetap akademis, agar mudah dipahami oleh mahasiswa.**

