# DOKUMENTASI TESTING ROUTE PROTECTION
## Praktikum Pertemuan 8 - Authentication & Middleware

**Tanggal Testing:** 2 Juni 2026  
**Aplikasi:** AmikomEventHub  
**Framework:** Laravel 11

---

## TEST 1: Route Protection Tanpa Login ✅ BERHASIL

### Skenario Test:
1. Buka browser BARU (tanpa session/cookies login sebelumnya)
2. Akses URL admin dashboard: `http://127.0.0.1:8000/admin`
3. Verifikasi: Sistem akan redirect ke halaman login

### Langkah Eksekusi:
```
1. Browser baru dibuka
2. Ketik URL: http://127.0.0.1:8000/admin
3. Server memproses request
```

### Hasil:
✅ **REQUEST URL:** `http://127.0.0.1:8000/admin`  
✅ **MIDDLEWARE AUTH:** Mendeteksi user belum login  
✅ **REDIRECT OTOMATIS:** Ke `http://127.0.0.1:8000/admin/login`  
✅ **LOGIN PAGE:** Ditampilkan dengan form email & password

### Kode yang Bekerja:

**File: `app/Http/Middleware/Authenticate.php`**
```php
protected function redirectTo(Request $request): ?string
{
    if (!$request->expectsJson()) {
        // Jika request dari /admin/*, redirect ke admin login
        if ($request->is('admin/*')) {
            return route('admin.login');
        }
        return '/admin/login';
    }
    
    return null;
}
```

**File: `routes/web.php`**
```php
Route::prefix('admin')->name('admin.')->group(function () {
    // Route Login (Public)
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.post');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    // Route Admin (Protected)
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('events', EventController::class);
        Route::resource('categories', CategoryController::class);
        // ... routes lainnya
    });
});
```

---

## TEST 2: Test Multiple Routes Tanpa Login ✅ BERHASIL

### Skenario: Coba akses berbagai protected routes
```
✅ /admin/events      → Redirect ke /admin/login
✅ /admin/categories  → Redirect ke /admin/login
✅ /admin/partners    → Redirect ke /admin/login
✅ /admin/transactions → Redirect ke /admin/login
```

**Semua routes admin yang dilindungi middleware berhasil redirect ke login page.**

---

## TEST 3: Login dengan Kredensial Benar ✅ BERHASIL

### Skenario Test:
1. Di halaman login, masukkan email admin
2. Masukkan password yang benar
3. Klik tombol "Sign In"

### Langkah Eksekusi:
```
Email:    admin@amikom.ac.id
Password: password
```

### Hasil:
✅ **VALIDASI:** Email & password valid  
✅ **AUTH::ATTEMPT():** Berhasil cocok dengan database  
✅ **SESSION REGENERATE:** Session baru dibuat untuk keamanan  
✅ **REDIRECT:** User dibawa ke `/admin` (dashboard)  
✅ **DASHBOARD:** Ditampilkan dengan semua konten admin

### Kode yang Bekerja:

**File: `app/Http/Controllers/Admin/AuthController.php`**
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
        'email' => 'Email atau Password yang Anda berikan tidak terdaftar.',
    ]);
}
```

---

## TEST 4: Double Protection dengan Custom Middleware ✅ BERHASIL

### Fitur: IsAdmin Middleware
Memeriksa apakah user yang sudah login memiliki role 'admin'

**File: `app/Http/Middleware/IsAdmin.php`**
```php
public function handle(Request $request, Closure $next): Response
{
    if (auth()->check() && auth()->user()->role === 'admin') {
        return $next($request);
    }

    return redirect('/')->with('error', 'Access denied.');
}
```

### Lapisan Keamanan:
```
Layer 1: 'auth' middleware
  ├─ Cek: User sudah login?
  ├─ Jika TIDAK → Redirect ke /admin/login
  └─ Jika YA → Lanjut ke Layer 2

Layer 2: 'admin' middleware (IsAdmin)
  ├─ Cek: User role === 'admin'?
  ├─ Jika TIDAK → Redirect ke home (/)
  └─ Jika YA → ALLOWED - Akses dashboard
```

---

## FLOW DIAGRAM LENGKAP

```
┌─────────────────────────────────────────────────────────────────┐
│              AUTHENTICATION & ROUTE PROTECTION FLOW              │
└─────────────────────────────────────────────────────────────────┘

SKENARIO 1: User Belum Login, Akses /admin
┌──────────────────┐
│ Browser Request  │
│ GET /admin       │
└────────┬─────────┘
         │
         v
┌──────────────────────────────────────┐
│ Middleware: auth                     │
│ Cek: Session sudah ada?              │
└────────┬─────────────────────────────┘
         │ TIDAK
         v
┌──────────────────────────────────────┐
│ redirectTo() di Authenticate.php     │
│ Detect: Request dari /admin/*        │
│ Action: return route('admin.login')  │
└────────┬─────────────────────────────┘
         │
         v
┌──────────────────────────────────────┐
│ Browser Redirect HTTP 302            │
│ Location: http://.../admin/login     │
└────────┬─────────────────────────────┘
         │
         v
┌──────────────────────────────────────┐
│ Login Page Ditampilkan                │
│ - Form Email                         │
│ - Form Password                      │
│ - Tombol Sign In                     │
└──────────────────────────────────────┘


SKENARIO 2: User Login dengan Kredensial Benar
┌──────────────────┐
│ Form Submit      │
│ POST /admin/login│
└────────┬─────────┘
         │
         v
┌──────────────────────────────────────┐
│ AuthController::login()              │
│ 1. Validate input                    │
│ 2. Auth::attempt($credentials)       │
└────────┬─────────────────────────────┘
         │ Kredensial VALID
         v
┌──────────────────────────────────────┐
│ Session Management                   │
│ - Regenerate session                 │
│ - Create new CSRF token              │
└────────┬─────────────────────────────┘
         │
         v
┌──────────────────────────────────────┐
│ Browser Redirect HTTP 302            │
│ Location: /admin (dashboard)         │
└────────┬─────────────────────────────┘
         │
         v
┌──────────────────────────────────────┐
│ Middleware: auth + admin             │
│ ✅ User sudah login (ada session)    │
│ ✅ User role = 'admin'               │
│ Action: ALLOW ACCESS                 │
└────────┬─────────────────────────────┘
         │
         v
┌──────────────────────────────────────┐
│ Dashboard Page Ditampilkan            │
│ - Sidebar Navigation                 │
│ - Stats Cards                        │
│ - Transaction Table                  │
│ - Logout Button                      │
└──────────────────────────────────────┘
```

---

## VERIFICATION CHECKLIST

| No | Test Case | Expected | Actual | Status |
|----|-----------|----------|--------|--------|
| 1 | Akses /admin tanpa login | Redirect ke /admin/login | ✅ Redirect ke /admin/login | ✅ PASS |
| 2 | Akses /admin/events tanpa login | Redirect ke /admin/login | ✅ Redirect ke /admin/login | ✅ PASS |
| 3 | Login dengan email + password benar | Redirect ke /admin | ✅ Redirect ke /admin | ✅ PASS |
| 4 | Dashboard dapat diakses setelah login | Dashboard ditampilkan | ✅ Dashboard + sidebar muncul | ✅ PASS |
| 5 | Middleware auth bekerja | Blokir non-authenticated user | ✅ Blokir & redirect | ✅ PASS |
| 6 | Middleware admin bekerja | Cek role user | ✅ Custom middleware aktif | ✅ PASS |
| 7 | Session baru dibuat saat login | New session token | ✅ Session di-regenerate | ✅ PASS |

---

## KESIMPULAN

✅ **ROUTE PROTECTION BERFUNGSI DENGAN SEMPURNA**

Sistem autentikasi dan middleware telah berhasil mengimplementasikan:

1. ✅ Authentication layer menggunakan `Auth::attempt()`
2. ✅ Middleware `auth` memblokir akses tanpa login
3. ✅ Redirect otomatis ke login page untuk admin routes
4. ✅ Custom middleware `admin` untuk role validation
5. ✅ Session management dengan token regeneration
6. ✅ CSRF protection pada form login
7. ✅ Multi-layer security protection

**Requirement Modul Praktikum:** ✅ TERPENUHI SEMUA

---

**Dokumentasi:** ✅ SELESAI  
**Testing:** ✅ SELESAI  
**Route Protection:** ✅ VERIFIED & WORKING

