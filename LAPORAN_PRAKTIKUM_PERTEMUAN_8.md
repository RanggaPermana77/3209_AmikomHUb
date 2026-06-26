# LAPORAN PRAKTIKUM PERTEMUAN 8
## AUTHENTICATION & MIDDLEWARE
### Digital Business (SI148)
**Universitas AMIKOM Yogyakarta**  
**Program Studi S1 Sistem Informasi**

---

## 1. PENDAHULUAN

Praktikum ini bertujuan untuk mengimplementasikan sistem autentikasi (login) manual dan middleware keamanan pada aplikasi AmikomEventHub menggunakan framework Laravel. Sistem autentikasi ini dirancang khusus untuk Admin Penyelenggara dengan pembatasan akses ke halaman dashboard dan fitur manajemen.

---

## 2. TUJUAN PEMBELAJARAN

Setelah menyelesaikan praktikum ini, praktikan diharapkan dapat:

✅ **Memahami alur kerja Authentication** pada framework Laravel  
✅ **Membuat fungsi Login manual** tanpa bergantung sepenuhnya pada Starter Kit  
✅ **Memahami konsep Middleware** sebagai lapisan keamanan aplikasi  
✅ **Mengimplementasikan Route Protection** untuk membatasi akses hanya kepada Admin yang telah login  

---

## 3. IMPLEMENTASI TAHAP DEMI TAHAP

### 3.1 Modifikasi Tabel Users (Database Migration)

**File:** `database/migrations/2014_10_12_000000_create_users_table.php`

Perubahan utama adalah mengubah kolom `role` dari tipe `string` menjadi `enum` untuk validasi data yang lebih ketat:

```php
$table->enum('role', ['admin', 'user'])->default('user');
```

**Alasan:** Menggunakan enum memastikan hanya ada dua nilai yang valid: 'admin' atau 'user', mencegah data yang tidak sesuai.

---

### 3.2 Injeksi Admin Perdana (Database Seeder)

**File:** `database/seeders/DatabaseSeeder.php`

Admin default dibuat menggunakan seeder dengan password terenkripsi:

```php
use App\Models\User;

public function run() {
    User::create([
        'name' => 'Admin Amikom',
        'email' => 'admin@amikom.ac.id',
        'password' => bcrypt('password'),
        'role' => 'admin'
    ]);
}
```

**Kredensial Default:**
- Email: `admin@amikom.ac.id`
- Password: `password`

---

### 3.3 Membuat View Halaman Login

**File:** `resources/views/auth/login.blade.php`

Login form dibuat dengan HTML/CSS yang responsif dan user-friendly:

**Fitur Login View:**
- Form input email dan password
- CSRF token untuk keamanan form
- Validasi error messages dari server
- Design modern dengan gradient background
- Responsive layout untuk mobile devices

**Form Structure:**
```blade
<form method="POST" action="{{ route('admin.login.post') }}">
    @csrf
    
    <div class="form-group">
        <label>Email Address</label>
        <input type="email" name="email" required>
    </div>
    
    <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" required>
    </div>
    
    <button type="submit">Sign In</button>
</form>
```

---

### 3.4 Membuat AuthController

**File:** `app/Http/Controllers/Admin/AuthController.php`

Controller ini menangani 3 fungsi utama:

#### **1. showLogin() - Menampilkan Form Login**
```php
public function showLogin()
{
    return view('auth.login');
}
```

#### **2. login() - Memproses Login**
```php
public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->route('admin.dashboard');
    }

    return back()->withErrors([
        'email' => 'Email atau Password tidak terdaftar.',
    ]);
}
```

**Penjelasan:**
- `Auth::attempt()` mencocokkan email dan password dengan data di database
- `regenerateToken()` membuat session baru untuk keamanan
- Jika gagal, error ditampilkan kembali ke form login

#### **3. logout() - Menghapus Session Login**
```php
public function logout(Request $request)
{
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
}
```

---

### 3.5 Mengamankan Routes dengan Middleware

**File:** `routes/web.php`

Routes diorganisir dalam group dengan prefix dan middleware:

```php
Route::prefix('admin')->name('admin.')->group(function () {
    // Route LOGIN (Tidak dilindungi)
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.post');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    // Route ADMIN (Dilindungi dengan Middleware)
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('events', EventController::class);
        Route::resource('categories', CategoryController::class);
        // ... routes lainnya
    });
});
```

**Middleware yang digunakan:**
- `auth` - Memverifikasi user sudah login
- `admin` - Memverifikasi role user adalah 'admin'

---

### 3.6 Membuat Custom Middleware IsAdmin

**File:** `app/Http/Middleware/IsAdmin.php`

Middleware custom ini memeriksa apakah user memiliki role 'admin':

```php
public function handle(Request $request, Closure $next): Response
{
    if (auth()->check() && auth()->user()->role === 'admin') {
        return $next($request);
    }

    return redirect('/')->with('error', 'Access denied.');
}
```

**Pendaftaran di Kernel:**

File: `app/Http/Kernel.php`

```php
protected $middlewareAliases = [
    // ... middleware lainnya
    'admin' => \App\Http\Middleware\IsAdmin::class,
];
```

---

## 4. TESTING & VALIDASI

### Test 1: Halaman Login dapat Diakses ✅

**URL:** `http://127.0.0.1:8000/admin/login`  
**Hasil:** Halaman login ditampilkan dengan form email dan password  
**Status:** ✅ BERHASIL

---

### Test 2: Login dengan Kredensial Benar ✅

**Langkah:**
1. Akses `http://127.0.0.1:8000/admin/login`
2. Masukkan email: `admin@amikom.ac.id`
3. Masukkan password: `password`
4. Klik tombol "Sign In"

**Hasil:** User di-redirect ke dashboard (`/admin`)  
**Status:** ✅ BERHASIL

**Output Dashboard:**
- Sidebar navigasi dengan menu: Dashboard, Kelola Event, Laporan Transaksi, Kategori, Kelola Partner, Keluar
- Main content menampilkan: Total Pendapatan, Tiket Terjual, Event Aktif, Pesanan Pending
- Tabel Transaksi Terakhir dengan data-data sample

---

### Test 3: Route Protection (Tanpa Login) ❌ → 🔒

**Langkah:**
1. Buka browser baru/incognito
2. Coba akses `http://127.0.0.1:8000/admin/`

**Perilaku Middleware:**
- Request untuk akses `/admin/` ditangkap oleh middleware `auth`
- Karena user belum login, middleware memblokir akses
- User di-redirect ke halaman login

**Status:** ✅ ROUTE PROTECTION BEKERJA

---

### Test 4: Logout Functionality

**Langkah:**
1. Dari dashboard, klik tombol "Keluar" (logout) di sidebar
2. Form submit POST ke route `admin.logout`
3. Session user dihapus dan user di-redirect ke home

**Status:** ✅ LOGOUT BERHASIL

---

## 5. ALUR AUTENTIKASI SISTEM

```
┌─────────────────────────────────────────────────────────────┐
│                    USER ACCESS FLOW                          │
└─────────────────────────────────────────────────────────────┘

User pada Home
    │
    ├─ Click "Admin Panel"
    │
    v
http://127.0.0.1:8000/admin
    │
    ├─ Middleware 'auth' cek session
    │   ├─ Jika ada session login → LANJUT
    │   ├─ Jika TIDAK ada session → REDIRECT ke /admin/login
    │
    v (Jika LOGIN)
Route Group dengan middleware ['auth', 'admin']
    │
    ├─ Middleware 'admin' cek role === 'admin'
    │   ├─ Jika role = admin → LANJUT ke Dashboard
    │   ├─ Jika role ≠ admin → REDIRECT ke home
    │
    v
Admin Dashboard ditampilkan
    │
    ├─ Sidebar berisi: Dashboard, Events, Categories, Partners, Logout
    ├─ Main content: Stats cards, Transaction table
    │
    v
Admin klik "Keluar"
    │
    ├─ Submit form POST ke /admin/logout
    ├─ AuthController::logout() dipanggil
    ├─ Session dihapus (invalidate)
    ├─ CSRF token di-regenerate
    │
    v
Redirect ke Home Page (/)
    │
    └─ User kembali ke public page, admin session hilang
```

---

## 6. FITUR KEAMANAN YANG DIIMPLEMENTASIKAN

### 6.1 CSRF Protection
- Semua form menggunakan `@csrf` token
- Mencegah Cross-Site Request Forgery attacks

### 6.2 Password Hashing
- Password di-hash menggunakan `bcrypt()`
- Password tidak disimpan dalam plain text

### 6.3 Session Management
- `regenerateToken()` membuat session baru setelah login
- Session invalidate saat logout untuk mencegah reuse

### 6.4 Role-Based Access Control
- User enum role ('admin' atau 'user')
- Middleware `IsAdmin` memverifikasi role sebelum akses

### 6.5 Input Validation
- Email validation menggunakan Laravel validator
- Password required validation
- Server-side validation untuk keamanan

---

## 7. FILE YANG DIMODIFIKASI/DIBUAT

| No | File | Tipe | Deskripsi |
|----|------|------|-----------|
| 1 | `database/migrations/2014_10_12_000000_create_users_table.php` | Modified | Ubah role ke enum |
| 2 | `database/seeders/DatabaseSeeder.php` | Modified | Tambah admin default |
| 3 | `resources/views/auth/login.blade.php` | Created | Form login |
| 4 | `app/Http/Controllers/Admin/AuthController.php` | Created | Logic login/logout |
| 5 | `app/Http/Middleware/IsAdmin.php` | Created | Custom middleware |
| 6 | `app/Http/Kernel.php` | Modified | Register middleware alias |
| 7 | `routes/web.php` | Modified | Setup route groups & middleware |
| 8 | `resources/views/layouts/admin.blade.php` | Modified | Update logout button ke form |

---

## 8. KESIMPULAN

Implementasi Authentication & Middleware pada aplikasi AmikomEventHub telah berhasil dilakukan dengan baik. Sistem keamanan berlapis telah diterapkan untuk:

✅ Autentikasi user dengan email dan password  
✅ Pembatasan akses dashboard hanya untuk Admin  
✅ Session management yang aman  
✅ CSRF protection  
✅ Role-based access control

Semua testing menunjukkan bahwa:
- Login berhasil dengan kredensial yang benar
- Route protection bekerja mencegah akses tanpa login
- Middleware successfully memverifikasi role admin
- Logout berfungsi dengan menghapus session dengan aman

---

## 9. REFERENSI

- Laravel Authentication: https://laravel.com/docs/authentication
- Laravel Middleware: https://laravel.com/docs/middleware
- Laravel Authorization: https://laravel.com/docs/authorization

---

**Tanggal Pengerjaan:** 2 Juni 2026  
**Status:** ✅ SELESAI  
**Nilai Fitur:** Implementasi Middleware IsAdmin untuk double protection ✅

