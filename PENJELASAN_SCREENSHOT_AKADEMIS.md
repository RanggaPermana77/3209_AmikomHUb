# PENJELASAN AKADEMIS SCREENSHOT IMPLEMENTASI SISTEM AUTENTIKASI DAN MIDDLEWARE
## Praktikum Pertemuan 8: Route Protection dan Authorization Middleware

**Disusun untuk:** Praktikum Pemrograman Web - Pertemuan 8  
**Tanggal:** 2 Juni 2026  
**NIM Mahasiswa:** 24.12.3209  
**Nama Mahasiswa:** Rangga Permana Mokoagow

---

## PENDAHULUAN

Dokumen ini menyajikan penjelasan akademis mendalam mengenai empat komponen kritis dalam implementasi sistem autentikasi dan otorisasi pada aplikasi web Laravel 11 AmikomEventHub. Penjelasan mencakup aspek teknis, konseptual, dan arsitektur perangkat lunak yang mendasari setiap komponen.

---

## 1. SCREENSHOT: `php artisan make:middleware IsAdmin`

### 1.1 Konteks dan Tujuan

Middleware `IsAdmin` merupakan komponen esensial dalam implementasi pola **Authorization** (otorisasi) berbasis peran (*role-based authorization*) pada sistem aplikasi. Command Laravel artisan `make:middleware` digunakan untuk membangkitkan (*scaffold*) kerangka kerja middleware secara otomatis dengan konvensi naming dan struktur kode yang sesuai dengan standar framework Laravel.

### 1.2 Fungsi Command Artisan

```bash
php artisan make:middleware IsAdmin
```

**Interpretasi Teknis:**
- **php artisan**: Utilitas command-line interface (CLI) bawaan framework Laravel
- **make:middleware**: Generator command untuk membuat file middleware baru
- **IsAdmin**: Nama middleware (akan otomatis diperbaiki ke PascalCase jika diperlukan)

**Hasil Eksekusi:**
Command ini menghasilkan file baru bernama `IsAdmin.php` di direktori `app/Http/Middleware/` dengan struktur dasar:

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }
}
```

### 1.3 Penjelasan Akademis

#### 1.3.1 Namespace dan Autoloading

Deklarasi `namespace App\Http\Middleware;` mengikuti standar **PSR-4** (PHP Standard Recommendation) untuk autoloading kelas. Ini memungkinkan Laravel menggunakan PHP's class autoloader untuk menemukan dan memuat kelas tanpa require/include eksplisit.

#### 1.3.2 Implementasi Middleware Pattern

Middleware mengimplementasikan pola **HTTP Middleware** (Pipeline Pattern), sebuah mekanisme interceptor yang memungkinkan request HTTP diproses melalui serangkaian layer sebelum mencapai handler akhir (controller/route).

**Alur Eksekusi:**

```
┌─────────────────┐
│ HTTP Request    │
└────────┬────────┘
         │
    ┌────▼────────────────────┐
    │ Middleware Pipeline     │
    │ ┌──────────────────────┐│
    │ │ Authenticate         ││
    │ ├──────────────────────┤│
    │ │ IsAdmin (Custom)     ││ ◄── Target Middleware
    │ ├──────────────────────┤│
    │ │ Other Middlewares    ││
    │ └──────────────────────┘│
    └────┬─────────────────────┘
         │
    ┌────▼────────────────┐
    │ Route Handler       │
    │ (Controller Action) │
    └────┬────────────────┘
         │
    ┌────▼──────────────┐
    │ HTTP Response    │
    └────┬─────────────┘
         │
    ┌────▼──────────────────────┐
    │ Middleware Response Chain  │
    │ (Layer by layer reverse)   │
    └─────────────────────────────┘
```

#### 1.3.3 Method Signature

```php
public function handle(Request $request, Closure $next): Response
```

**Komponen:**
- **`$request`**: Objek Illuminate\Http\Request yang merepresentasikan HTTP request dari klien
- **`$next`**: Closure yang merepresentasikan middleware berikutnya dalam pipeline atau route handler
- **Return Type `Response`**: Middleware harus mengembalikan Symfony\Component\HttpFoundation\Response

#### 1.3.4 Implementasi Logika Middleware

Implementasi lengkap IsAdmin middleware:

```php
public function handle(Request $request, Closure $next): Response
{
    // Autentikasi check: Memverifikasi user sudah login
    if (auth()->check() && auth()->user()->role === 'admin') {
        // Kondisi terpenuhi: Lanjut ke middleware/controller berikutnya
        return $next($request);
    }
    
    // Kondisi tidak terpenuhi: Kembalikan error response
    return redirect('/')->with('error', 'Access denied.');
}
```

**Logika Keputusan:**
1. `auth()->check()`: Memverifikasi apakah ada user yang terautentikasi
2. `auth()->user()->role === 'admin'`: Memverifikasi role user adalah 'admin'
3. Kedua kondisi harus TRUE, jika tidak user diredirect ke halaman utama

### 1.4 Kesimpulan Bagian 1

Middleware `IsAdmin` adalah implementasi dari konsep **Authorization Middleware** yang berfungsi sebagai gatekeeper (penjaga pintu) untuk melindungi resource yang hanya boleh diakses oleh pengguna dengan role administrator. Command artisan `make:middleware` memfasilitasi pembangkitan struktur middleware sesuai konvensi Laravel.

---

## 2. SCREENSHOT: HALAMAN LOGIN

### 2.1 Konteks dan Tujuan

Halaman login merupakan antarmuka (*user interface*) yang memfasilitasi proses **authentication** (autentikasi), yaitu verifikasi identitas pengguna. Halaman ini mengimplementasikan pola **Form-based Authentication** yang umum digunakan dalam aplikasi web tradisional.

### 2.2 Struktur dan Komponen UI

#### 2.2.1 Elemen HTML Semantik

```html
<div class="login-container">
    <div class="login-box">
        <h1>Admin Login - AmikomEventHub</h1>
        <p class="info-text">Rangga Permana Mokoagow | 24.12.3209</p>
        
        <!-- Form dengan CSRF Protection -->
        <form method="POST" action="{{ route('admin.login.post') }}" class="login-form">
            @csrf
            
            <!-- Email Input Field -->
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       required 
                       class="form-input">
                @error('email')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            
            <!-- Password Input Field -->
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" 
                       id="password" 
                       name="password" 
                       required 
                       class="form-input">
                @error('password')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            
            <!-- Submit Button -->
            <button type="submit" class="btn-submit">Sign In</button>
        </form>
    </div>
</div>
```

### 2.3 Penjelasan Akademis

#### 2.3.1 Prinsip Form-Based Authentication

Halaman login mengimplementasikan pola autentikasi berbasis formulir yang merupakan standar industri untuk web application authentication. Proses ini melibatkan:

1. **Presentasi Formulir**: User mengisi kredensial (email & password)
2. **Transmisi Data**: Data dikirim ke server melalui HTTP POST
3. **Validasi Server**: Server memverifikasi kredensial terhadap database
4. **Session Management**: Jika valid, session baru dibuat untuk user

#### 2.3.2 CSRF Protection Mechanism

```html
@csrf
```

**Implementasi CSRF Prevention:**
- Token unik digenerate per session
- Token embedded dalam setiap form
- Server memverifikasi token pada submit untuk mencegah Cross-Site Request Forgery
- Konsep: **Synchronizer Token Pattern**

#### 2.3.3 Input Validation Framework

```html
@error('email')
    <span class="error-message">{{ $message }}</span>
@enderror
```

**Komponen Validasi:**
- Blade templating syntax: `@error()` directive
- Server-side validation melalui Form Request Validation
- Display error message if validation fails

#### 2.3.4 Atribut Input HTML5

**Type Safety:**
```html
<input type="email" ... >  <!-- Email format validation -->
<input type="password" ... > <!-- Masked input for security -->
```

**Security Features:**
- `type="email"`: Browser-level email format validation
- `type="password"`: Masking input (tidak visible di screen)
- `required`: HTML5 required attribute untuk client-side validation

#### 2.3.5 Form Action dan Routing

```html
<form method="POST" action="{{ route('admin.login.post') }}">
```

**Route Binding:**
- `{{ route('admin.login.post') }}`: Laravel route helper yang resolve ke `/admin/login`
- Method POST: Konvensi untuk form submission (vs. GET untuk retrieval)
- Named route: Memungkinkan change URL tanpa modifikasi template

#### 2.3.6 Aspek Desain (CSS Styling)

```css
.login-container {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.login-box {
    background: white;
    padding: 40px;
    border-radius: 8px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    width: 100%;
    max-width: 400px;
}

.form-input {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
}

.btn-submit {
    width: 100%;
    padding: 12px;
    background-color: #667eea;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
}
```

**Prinsip Desain:**
- **Gradient Background**: Visual hierarchy dengan gradient biru-ungu
- **Card Layout**: Centered card dengan shadow untuk depth
- **Responsive Design**: `max-width: 400px` untuk mobile compatibility
- **Accessibility**: Proper labels dan form structure

### 2.4 Alur Proses Login

```
┌────────────────────────────────────────┐
│ User membuka halaman /admin/login      │
└─────────────────┬──────────────────────┘
                  │
          ┌───────▼────────┐
          │ Controller      │
          │ showLogin()     │
          └───────┬────────┘
                  │
          ┌───────▼────────────────────────┐
          │ Template: auth/login.blade.php │
          │ Render form dengan CSRF token  │
          └───────┬────────────────────────┘
                  │
          ┌───────▼──────────────────┐
          │ User input credentials   │
          │ Email, Password          │
          └───────┬──────────────────┘
                  │
          ┌───────▼──────────────────┐
          │ User submit form (POST)  │
          └───────┬──────────────────┘
                  │
          ┌───────▼────────────────────────┐
          │ Route: POST /admin/login       │
          │ Handler: AuthController@login()│
          └───────┬────────────────────────┘
                  │
          ┌───────▼──────────────────────┐
          │ 1. Validate CSRF token       │
          │ 2. Validate input format     │
          │ 3. Database lookup           │
          │ 4. Password verification     │
          │ 5. Session creation          │
          └───────┬──────────────────────┘
                  │
         ┌────────▼─────────┐
         │ Login Success?   │
         └────────┬─────────┘
                  │
        ┌─────────┴──────────┐
        │                    │
   ┌────▼────┐        ┌─────▼──┐
   │ YES     │        │ NO     │
   └────┬────┘        └────┬───┘
        │                  │
  ┌─────▼───────┐   ┌──────▼──────────┐
  │ Create       │   │ Return login    │
  │ session      │   │ form with error │
  │ Redirect to  │   │ message         │
  │ /admin       │   └─────────────────┘
  └─────────────┘
```

### 2.5 Kesimpulan Bagian 2

Halaman login mengimplementasikan prinsip-prinsip keamanan web modern termasuk CSRF protection, input validation, password masking, dan form-based authentication. Desain UI-nya mendukung user experience yang optimal dengan gradient background, centered card layout, dan error handling yang jelas.

---

## 3. SCREENSHOT: ROUTE WEB

### 3.1 Konteks dan Tujuan

File `routes/web.php` merupakan konfigurasi routing yang mendefinisikan pemetaan antara HTTP request paths dan handler functions (controller actions). File ini mendemonstrasikan implementasi pola **Route Grouping** dan **Middleware Composition** untuk mengorganisir routes berdasarkan konteks (admin vs. public).

### 3.2 Struktur Route Web

#### 3.2.1 Organisasi Route Groups

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;

// ============================================
// PUBLIC ROUTES - Tidak memerlukan autentikasi
// ============================================

Route::get('/', function () {
    return view('welcome');
})->name('home');

// ============================================
// ADMIN ROUTES GROUP - Prefix + Naming
// ============================================

Route::prefix('admin')              // URL Prefix: /admin/*
      ->name('admin.')              // Route Name Prefix: admin.*
      ->group(function () {
    
    // -------------------------------------------
    // Public Admin Routes (Login)
    // -------------------------------------------
    
    Route::get('login', [AuthController::class, 'showLogin'])
          ->name('login');
    
    Route::post('login', [AuthController::class, 'login'])
          ->name('login.post');
    
    Route::post('logout', [AuthController::class, 'logout'])
          ->name('logout');
    
    // -------------------------------------------
    // Protected Admin Routes (Dashboard & Admin Panel)
    // Middleware: auth + admin
    // -------------------------------------------
    
    Route::middleware(['auth', 'admin'])->group(function () {
        
        Route::get('/', [DashboardController::class, 'index'])
              ->name('dashboard');
        
        // Alias route untuk /admin/dashboard
        Route::get('/dashboard', [DashboardController::class, 'index']);
        
        // Route lainnya untuk admin panel...
        // Route::resource('categories', CategoryController::class);
        // Route::resource('events', EventController::class);
        // Route::resource('transactions', TransactionController::class);
    });
});
```

### 3.3 Penjelasan Akademis

#### 3.3.1 Route Prefix dan Naming Convention

```php
Route::prefix('admin')->name('admin.')->group(function () {
    // ...
});
```

**Penjelasan Teknis:**

| Attribute | Fungsi | Contoh |
|-----------|--------|--------|
| `prefix('admin')` | Menambah prefix URL ke semua routes dalam group | `/admin/login` |
| `name('admin.')` | Menambah prefix nama route | `route('admin.login')` |
| `group()` | Mengelompokkan routes dengan konfigurasi yang sama | Multiple routes |

**Keuntungan:**
- DRY Principle (Don't Repeat Yourself)
- Centralized configuration
- Maintainability lebih tinggi

#### 3.3.2 HTTP Method Binding

```php
Route::get('login', ...)           // GET request
Route::post('login', ...)          // POST request
Route::post('logout', ...)         // POST request
```

**Konsep HTTP Methods:**

| Method | Tujuan | Idempotent | Safe |
|--------|--------|-----------|------|
| GET | Retrieve resource | Yes | Yes |
| POST | Create resource | No | No |
| PUT | Replace resource | Yes | No |
| PATCH | Partial update | No | No |
| DELETE | Delete resource | Yes | No |

**Signifikansi:**
- POST untuk logout: Mencegah logout via simple link click (CSRF protection)
- GET untuk login form: Safe operation, dapat di-bookmark/cache

#### 3.3.3 Middleware Composition

```php
Route::middleware(['auth', 'admin'])->group(function () {
    // Protected routes
});
```

**Middleware Pipeline Architecture:**

```
Request
  │
  ├─→ 'auth' Middleware
  │    └─→ Check: User authenticated?
  │         ├─→ No: Redirect /admin/login
  │         └─→ Yes: Continue
  │
  ├─→ 'admin' Middleware (IsAdmin)
  │    └─→ Check: User role === 'admin'?
  │         ├─→ No: Redirect /
  │         └─→ Yes: Continue
  │
  └─→ Controller Action
       └─→ Return Response
```

**Kondisi Akses Protected Route:**
1. User HARUS terautentikasi (session exists)
2. User HARUS memiliki role 'admin'
3. Jika salah satu tidak terpenuhi → Reject request

#### 3.3.4 Controller Resolution

```php
Route::get('login', [AuthController::class, 'showLogin'])
```

**Komponen:**
- `[AuthController::class, 'showLogin']`: Array yang menspesifikasi controller class dan method
- `AuthController::class`: Menggunakan PHP class constant untuk type safety
- `'showLogin'`: Method name di controller yang akan dipanggil

**Ekuivalen String:**
```php
// Jarang digunakan, kurang robust:
Route::get('login', 'Admin\AuthController@showLogin');
```

#### 3.3.5 Named Routes dan Route Helper

```php
Route::get('login', ...)->name('login');
```

**Implementasi:**

| Approach | Kelemahan | Keuntungan |
|----------|-----------|-----------|
| Hardcode URL `/admin/login` | Fragile, error-prone jika URL berubah | Tidak ada |
| Named route `route('admin.login')` | Harus remember nama | Robust, SEO-friendly |

**Penggunaan:**

```php
// Di Blade template:
<a href="{{ route('admin.login') }}">Login</a>

// Di Controller:
return redirect(route('admin.dashboard'));

// Advantages: Automatic URL generation, refactoring safe
```

#### 3.3.6 Route Grouping Pattern (Nested Groups)

```
Group Level 1: prefix('admin'), name('admin.')
    │
    ├─ Public Routes (no middleware)
    │   ├ login GET
    │   ├ login POST
    │   └ logout POST
    │
    └─ Group Level 2: middleware(['auth', 'admin'])
        │
        ├─ dashboard GET (/)
        └─ dashboard GET (/dashboard)
```

**Design Pattern: Composite Pattern**
- Allows recursive composition of routes
- Centralizes middleware configuration
- Clear separation of concerns

### 3.4 Route Verification

```bash
# Display all registered routes:
php artisan route:list

# Expected output:
GET|HEAD    /                       home
GET         /admin/login            admin.login
POST        /admin/login            admin.login.post
POST        /admin/logout           admin.logout
GET         /admin                  admin.dashboard
GET         /admin/dashboard        (no name)
```

### 3.5 Kesimpulan Bagian 3

File `routes/web.php` mengimplementasikan advanced routing patterns termasuk route grouping, middleware composition, named routes, dan controller resolution. Struktur ini memastikan:
- Clear separation antara public dan protected routes
- Centralized middleware configuration
- Maintainable dan DRY code
- Type-safe controller references

---

## 4. SCREENSHOT: HALAMAN DASHBOARD

### 4.1 Konteks dan Tujuan

Halaman dashboard merupakan **protected resource** yang hanya dapat diakses oleh pengguna yang telah terautentikasi dan memiliki role administrator. Dashboard menampilkan informasi agregat (*aggregated information*) dan metrics yang relevan untuk administrative purposes.

### 4.2 Struktur dan Komponen Dashboard

#### 4.2.1 Layout Template

```blade
@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
    <div class="dashboard-header">
        <h1>Admin Panel - AmikomEventHub</h1>
        <p>Selamat datang, {{ auth()->user()->name }}</p>
    </div>
    
    <!-- Statistics Cards Section -->
    <div class="stats-container">
        <div class="stat-card">
            <div class="stat-icon revenue-icon">💰</div>
            <div class="stat-content">
                <h3>Pendapatan</h3>
                <p class="stat-value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon ticket-icon">🎫</div>
            <div class="stat-content">
                <h3>Tiket Terjual</h3>
                <p class="stat-value">{{ $totalTickets }}</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon event-icon">📅</div>
            <div class="stat-content">
                <h3>Event Aktif</h3>
                <p class="stat-value">{{ $activeEvents }}</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon order-icon">📦</div>
            <div class="stat-content">
                <h3>Pesanan</h3>
                <p class="stat-value">{{ $totalOrders }}</p>
            </div>
        </div>
    </div>
    
    <!-- Transactions Table Section -->
    <div class="transactions-section">
        <h2>Transaksi Terbaru</h2>
        <table class="transactions-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Pembeli</th>
                    <th>Event</th>
                    <th>Jumlah</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $transaction)
                    <tr>
                        <td>#{{ $transaction->id }}</td>
                        <td>{{ $transaction->user->name }}</td>
                        <td>{{ $transaction->event->name }}</td>
                        <td>{{ $transaction->quantity }}</td>
                        <td>Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</td>
                        <td>
                            <span class="status-badge status-{{ strtolower($transaction->status) }}">
                                {{ ucfirst($transaction->status) }}
                            </span>
                        </td>
                        <td>{{ $transaction->created_at->format('d M Y H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Tidak ada transaksi</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
```

### 4.3 Penjelasan Akademis

#### 4.3.1 Template Inheritance (Blade Templating Engine)

```blade
@extends('layouts.admin')
@section('content')
    ...
@endsection
```

**Konsep Template Inheritance:**
- **Parent Template** (`layouts/admin.blade.php`): Mendefinisikan struktur umum
- **Child Template**: Mengisi section-specific content
- **Yield Points**: `@yield('content')` di parent template

**Benefit:**
- DRY principle untuk layout common
- Konsistensi UI across pages
- Mudah maintenance

#### 4.3.2 Authentication Context

```blade
<p>Selamat datang, {{ auth()->user()->name }}</p>
```

**Implementasi:**
- `auth()`: Global helper function yang return authenticated user
- `auth()->user()`: Return user object (jika authenticated, null if not)
- `{{ }}`: Blade echo syntax dengan HTML escaping untuk XSS prevention

**Security Consideration:**
- Template hanya accessible karena middleware 'auth' sudah verified
- Double assurance: Middleware pada route level + Template rendering
- No null-pointer exception karena middleware guarantee user exists

#### 4.3.3 Data Aggregation Pattern

```blade
<p class="stat-value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
```

**Alur Data Flow:**

```
Database (MySQL)
    │
    ├─→ SUM(transactions.total_price) 
    │    WHERE status = 'completed'
    │    AND created_at BETWEEN [date_range]
    │
    └─→ Controller Method
         └─→ $totalRevenue = Transaction::where(...)->sum('total_price');
             └─→ View
                  └─→ Display with number_format()
```

**Data Transformation:**
- Raw database value: `150000000`
- Formatted output: `Rp 150.000.000` (Rupiah currency)
- Laravel's `number_format()`: Untuk locale-aware formatting

#### 4.3.4 Conditional Rendering

```blade
@forelse($transactions as $transaction)
    <tr>
        <td>#{{ $transaction->id }}</td>
        <!-- ... -->
    </tr>
@empty
    <tr>
        <td colspan="7" class="text-center">Tidak ada transaksi</td>
    </tr>
@endforelse
```

**Control Structure:**
- `@forelse`: Loop with empty fallback
- Equivalent PHP:
  ```php
  if (!empty($transactions)) {
      foreach ($transactions as $transaction) { ... }
  } else {
      echo "No transactions";
  }
  ```

#### 4.3.5 Relationship Access (Eloquent ORM)

```blade
<td>{{ $transaction->user->name }}</td>
<td>{{ $transaction->event->name }}</td>
```

**Relationship Resolution:**
```php
// Model definition:
class Transaction extends Model {
    public function user() {
        return $this->belongsTo(User::class);
    }
    
    public function event() {
        return $this->belongsTo(Event::class);
    }
}

// Usage:
$transaction->user    // Lazy load User related to transaction
$transaction->event   // Lazy load Event related to transaction
```

**N+1 Query Problem:**
- Inefficient: Loop queries 1 + n (1 transaction query + n user/event queries)
- Solution: Use eager loading `with(['user', 'event'])`
- Optimized: 1 transaction query + 1 user query + 1 event query

#### 4.3.6 Dynamic CSS Class Binding

```blade
<span class="status-badge status-{{ strtolower($transaction->status) }}">
    {{ ucfirst($transaction->status) }}
</span>
```

**Styling Logic:**
```css
.status-badge {
    padding: 6px 12px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
}

.status-completed {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.status-pending {
    background-color: #fff3cd;
    color: #856404;
    border: 1px solid #ffeeba;
}

.status-cancelled {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}
```

#### 4.3.7 Date Formatting

```blade
<td>{{ $transaction->created_at->format('d M Y H:i') }}</td>
```

**Carbon DateTime Object:**
- Laravel Eloquent automatically cast timestamp columns to Carbon instances
- `format()`: Method untuk custom date formatting
- Format code: 'd' = day, 'M' = month abbreviation, 'Y' = 4-digit year, 'H:i' = time

**Output Example:**
- Database: `2026-06-02 14:30:45`
- Formatted: `02 Jun 2026 14:30`

### 4.4 Controller Logic (Backend)

```php
<?php

namespace App\Http\Controllers\Admin;

use App\Models\Transaction;
use App\Models\Event;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // Calculate statistics
        $totalRevenue = Transaction::where('status', 'completed')
            ->sum('total_price');
        
        $totalTickets = Transaction::where('status', 'completed')
            ->sum('quantity');
        
        $activeEvents = Event::where('status', 'active')
            ->count();
        
        $totalOrders = Transaction::count();
        
        // Fetch recent transactions
        $transactions = Transaction::with(['user', 'event'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        // Return view with data
        return view('admin.dashboard', [
            'totalRevenue' => $totalRevenue,
            'totalTickets' => $totalTickets,
            'activeEvents' => $activeEvents,
            'totalOrders' => $totalOrders,
            'transactions' => $transactions,
        ]);
    }
}
```

**Key Concepts:**

| Concept | Implementasi |
|---------|-------------|
| Query Scoping | `where('status', 'completed')` |
| Aggregation | `sum()`, `count()` |
| Eager Loading | `with(['user', 'event'])` |
| Ordering | `orderBy('created_at', 'desc')` |
| Limit | `limit(10)` untuk performance |
| Data Transfer | Return array ke view |

### 4.5 Access Control Verification

```
Access to /admin/dashboard:

1. Route defined in routes/web.php with middleware(['auth', 'admin'])
2. Request hits Authenticate middleware
   ├─ Check: auth()->check() ?
   └─ Result: True (user logged in)
3. Request hits IsAdmin middleware
   ├─ Check: auth()->user()->role === 'admin' ?
   └─ Result: True (user is admin)
4. Request passed to DashboardController@index()
5. Controller queries database and returns view
6. View rendered and sent to client
```

### 4.6 Kesimpulan Bagian 4

Halaman dashboard mengimplementasikan pola-pola best practice termasuk:
- Template inheritance untuk layout reusability
- Data aggregation dan transformation
- Relationship eager loading untuk query optimization
- Dynamic content rendering dengan Blade templating
- Security through multi-layer middleware verification
- Professional UI dengan status indicators dan formatted data

---

## RINGKASAN KESELURUHAN

### Implementasi Authentication & Authorization Pipeline

```
┌─────────────────────────────────────────────────┐
│ AUTHENTICATION & AUTHORIZATION SYSTEM            │
└─────────────────────────────────────────────────┘

LAYER 1: Artisan Command (php artisan make:middleware IsAdmin)
  └─ Generate middleware scaffold dengan struktur Laravel convention

LAYER 2: Login Interface (Halaman Login)
  └─ Collect & transmit credentials (email + password)
  └─ CSRF protection, input validation, error handling

LAYER 3: Route Configuration (routes/web.php)
  └─ Define route groups dengan middleware composition
  └─ Separate public vs. protected routes
  └─ Named routes untuk SEO-friendly URL generation

LAYER 4: Protected Resource (Dashboard)
  └─ Only accessible after authentication
  └─ Display user-specific data with proper authorization
  └─ Aggregated metrics dan business logic
```

### Security Architecture Summary

```
Security Layer Stack (Bottom to Top):
  │
  ├─ Password Hashing: bcrypt() untuk secure storage
  ├─ CSRF Protection: Token validation pada form submission
  ├─ Session Management: Secure session handling dengan token regeneration
  ├─ HTTP Middleware: Authentication middleware untuk request interception
  ├─ Authorization: IsAdmin middleware untuk role-based access control
  ├─ Input Validation: Client-side (HTML5) + Server-side validation
  └─ XSS Prevention: Blade templating dengan automatic HTML escaping
```

### Best Practices Diterapkan

| Practice | Implementasi | Benefit |
|----------|-------------|---------|
| DRY Principle | Route grouping, template inheritance | Reduced code duplication |
| Separation of Concerns | Controller/View/Middleware | Maintainability |
| Named Routes | `route('admin.login')` | Refactoring safe |
| Type Safety | `[AuthController::class, 'method']` | IDE support, error prevention |
| Eager Loading | `with(['user', 'event'])` | N+1 query prevention |
| Input Validation | Server-side form request | Security |
| CSRF Protection | Token validation | CSRF attack prevention |

---

## KESIMPULAN AKHIR

Keempat komponen (Middleware Generation, Login Interface, Route Configuration, Dashboard Display) membentuk sistem terintegrasi yang mengimplementasikan:

1. **Authentication Mechanism**: Memverifikasi identitas pengguna
2. **Authorization Mechanism**: Memverifikasi hak akses berdasarkan role
3. **Session Management**: Mempertahankan status login
4. **Security Best Practices**: CSRF, password hashing, input validation

Implementasi ini sesuai dengan standar keamanan web modern dan best practices Laravel framework, sehingga dapat dijadikan fondasi untuk sistem aplikasi web yang aman dan scalable.

---

**Dokumen disusun sebagai bagian dari Praktikum Pemrograman Web - Pertemuan 8**  
**Tanggal Penyusunan: 2 Juni 2026**  
**Status: Final Review**
