<?php

use App\Http\Controllers\AuthController;

use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\IuranRTController;
use App\Http\Controllers\IuranRWController;
use App\Http\Controllers\KeuanganRTController;
use App\Http\Controllers\KeuanganRWController;
use App\Http\Controllers\KritikSaranController;
use App\Http\Controllers\ManajemenDetailIuranRTPengguna;
use App\Http\Controllers\ManajemenDetailIuranRWRT;
use App\Http\Controllers\PembayaranIuranRTController;
use App\Http\Controllers\PembayaranIuranWargaController;
use App\Http\Controllers\ProkerController;
use App\Http\Controllers\RTController;
use App\Http\Controllers\RWController;
use App\Http\Controllers\SetPasswordController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\WargaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/masuk', [AuthController::class, 'masuk'])->name('masuk');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/requestacc', [UsersController::class, 'requestCreate'])->name('account.requestCreate');
Route::post('/request', [UsersController::class, 'requestStore'])->name('account.requestStore');
Route::get('/forgot-password', [ForgotPasswordController::class, 'forgotPassword'])->name('forgot-password');
Route::post('/forgot-password', [ForgotPasswordController::class, 'validation'])->name('forgot-password.validation');
Route::get('/reset-password/{id}', [SetPasswordController::class, 'setPassword'])->name('reset-password');
Route::patch('/reset-password/{id}', [SetPasswordController::class, 'updatePassword'])->name('reset-password.update');

// route khusus untuk penjabat rt ['Admin_RT', 'Ketua_RT']
Route::middleware('role:Admin_RT|Ketua_RT')->group(function () {
    Route::get('/kritik-saran/rt', [KritikSaranController::class, 'listKritikRT'])->name('kritikRT.list');
    Route::get('/kritik-saran/rt/show/{id}', [KritikSaranController::class, 'showKritikRT'])->name('kritikRT.show');
    Route::patch('/kritik-saran/rt/{id}/dibaca', [KritikSaranController::class, 'kritikRTDibaca'])->name('kritikRT.dibaca');
    Route::patch('/kritik-saran/rt/{id}/selesai', [KritikSaranController::class, 'kritikRTSelesai'])->name('kritikRT.selesai');
});

// route khusus untuk penjabat rw ['Admin_RW', 'Ketua_RW']
Route::middleware('role:Admin_RW|Ketua_RW')->group(function () {
    Route::get('/kritik-saran/rw', [KritikSaranController::class, 'listKritikRW'])->name('kritikRW.list');
    Route::get('/kritik-saran/rw/show/{id}', [KritikSaranController::class, 'showKritikRW'])->name('kritikRW.show');
    Route::patch('/kritik-saran/rw/{id}/dibaca', [KritikSaranController::class, 'kritikRWDibaca'])->name('kritikRW.dibaca');
    Route::patch('/kritik-saran/rw/{id}/selesai', [KritikSaranController::class, 'kritikRWSelesai'])->name('kritikRW.selesai');
});

// route untuk admin [Admin_RT, Admin_RW]
Route::middleware('role:Admin_RT|Admin_RW')->group(function () {
    // route untuk menambahkan data program kerja
    Route::resource('/proker', ProkerController::class)->names([
        'index' => 'proker.index',
        'create' => 'proker.create',
        'store' => 'proker.store',
        'edit' => 'proker.edit',
        'update' => 'proker.update',
        'destroy' => 'proker.destroy',
        'show' => 'proker.show',
    ]);
});


Route::middleware('role:Admin_RT|Super_Admin|Admin_RW')->group(function () {
    // route untuk menambahkan data akun dari warga
    Route::resource('/account', UsersController::class)->names([
        'index' => 'account.index',
        'create' => 'account.create',
        'store' => 'account.store',
        'edit' => 'account.edit',
        'update' => 'account.update',
        'show' => 'account.show', ## ini untuk show profile saja?
        'destroy' => 'account.destroy'
    ]);

    // route untuk menambahkan data warga
    Route::resource('/warga', WargaController::class)->names([
        'index' => 'warga.index',
        'create' => 'warga.create',
        'store' => 'warga.store',
        'edit' => 'warga.edit',
        'update' => 'warga.update',
        'destroy' => 'warga.destroy'
    ]);
});


// route register warga user by adminRt
Route::middleware('role:Admin_RT')->group(function () {
    // route untuk pergi ke dashboard adminrt
    Route::get('/dashboard/adminrt', function (Request $request) {
        $data = getDashboardData($request);
        return view('\admin\DashboardAdmin', $data);
    })->name('dashboard.adminrt');

    // route untuk menambahkan data iuran rt
    Route::resource('/iuran-rt', IuranRTController::class)->names([
        'index' => 'iuranRT.index',
        'create' => 'iuranRT.create',
        'store' => 'iuranRT.store',
        'edit' => 'iuranRT.edit',
        'update' => 'iuranRT.update',
        'destroy' => 'iuranRT.destroy',
        'show' => 'iuranRT.show',
    ]);

    // route untuk menambahkan data laporan keuangan rt
    Route::resource('/laporan-keuangan-rt', KeuanganRTController::class)->names([
        'index' => 'RT.Keuangan.index',
        'create' => 'RT.Keuangan.create',
        'store' => 'RT.Keuangan.store',
        'edit' => 'RT.Keuangan.edit',
        'update' => 'RT.Keuangan.update',
        'destroy' => 'RT.Keuangan.destroy',
        'show' => 'RT.Keuangan.show',
    ]);

    Route::get('/manajemen-iuran-rt', [ManajemenDetailIuranRTPengguna::class, 'index'])->name('manajemen-detail-iuran-rt-pengguna.index');
    Route::patch('/manajemen-iuran-rt/{id}/selesai', [ManajemenDetailIuranRTPengguna::class, 'detailSelesai'])->name('manajemen-detail-iuran-rt-pengguna.selesai');
    Route::get('/manajemen-iuran-rt/{id}/gagal', [ManajemenDetailIuranRTPengguna::class, 'keGagal'])->name('manajemen-detail-iuran-rt-pengguna.keGagal');
    Route::delete('/manajemen-iuran-rt/{id}', [ManajemenDetailIuranRTPengguna::class, 'detailGagal'])->name('manajemen-detail-iuran-rt-pengguna.gagal');
    Route::get('/manajemen-iuran-rt/lihatgambar/{filename}', [ManajemenDetailIuranRTPengguna::class, 'show'])->name('manajemen-detail-rt-pengguna.show');

    Route::get('/bayar-iuran-rt', [PembayaranIuranRTController::class, 'index'])->name('bayar-iuran-rt.index');
    Route::post('/bayar-iuran-rt', [PembayaranIuranRTController::class, 'bayar'])->name('bayar-iuran-rt.bayar');
    Route::get('/bayar-iuran-rt/bayar', [PembayaranIuranRTController::class, 'konfirmasi'])->name('bayar-iuran-rt.konfirmasi');
    Route::post('/bayar-iuran-rt/bayar', [PembayaranIuranRTController::class, 'konfirmasiBayar'])->name('bayar-iuran-rt.konfirmasibayar');
});

// middleware untuk role warga
Route::middleware('role:Warga')->group(function () {
    Route::get('/dashboard/warga', function () {
        return view('/warga/DashboardWarga');
    })->name('dashboard.warga');

    Route::get('bayar-iuran', [PembayaranIuranWargaController::class, 'index'])->name('bayar-iuran.index');
    Route::post('bayar-iuran', [PembayaranIuranWargaController::class, 'bayar'])->name('bayar-iuran.bayar');
    Route::get('/bayar-iuran/bayar', [PembayaranIuranWargaController::class, 'konfirmasi'])->name('bayar-iuran.konfirmasi');
    Route::post('/bayar-iuran/bayar', [PembayaranIuranWargaController::class, 'konfirmasiBayar'])->name('bayar-iuran.konfirmasibayar');
});

// middleware untuk role admin rw
Route::middleware('role:Admin_RW')->group(function () {
    Route::get('/dashboard/adminrw', function (Request $request) {
        $data = getDashboardData($request);
        return view('/admin/DashboardAdmin', $data);
    })->name('dashboard.adminrw');

    // route untuk menambahkan rt baru dibawah rw yang dimiliki
    Route::resource('/rt', RTController::class)->names([
        'index' => 'RT.index',
        'create' => 'RT.create',
        'store' => 'RT.store',
        'edit' => 'RT.edit',
        'update' => 'RT.update',
        'destroy' => 'RT.destroy',
        'show' => 'RT.show',
    ]);

    // route untuk menambahkan laporan keuangan rw
    Route::resource('/laporan-keuangan-rw', KeuanganRWController::class)->names([
        'index' => 'RW.Keuangan.index',
        'create' => 'RW.Keuangan.create',
        'store' => 'RW.Keuangan.store',
        'edit' => 'RW.Keuangan.edit',
        'update' => 'RW.Keuangan.update',
        'destroy' => 'RW.Keuangan.destroy',
        'show' => 'RW.Keuangan.show',
    ]);
    Route::get('/laporan-keuangan/lihatgambar/{filename}', [KeuanganRWController::class, 'showImage'])->name('RW.Keuangan.lihatgambar');

    Route::resource('/iuran-rw', IuranRWController::class)->names([
        'index' => 'iuranRW.index',
        'create' => 'iuranRW.create',
        'store' => 'iuranRW.store',
        'edit' => 'iuranRW.edit',
        'update' => 'iuranRW.update',
        'destroy' => 'iuranRW.destroy',
        'show' => 'iuranRW.show',
    ]);

    Route::get('/manajemen-iuran-rw', [ManajemenDetailIuranRWRT::class, 'index'])->name('manajemen-detail-iuran-rw-rt.index');
    Route::patch('/manajemen-iuran-rw/{id}/selesai', [ManajemenDetailIuranRWRT::class, 'detailSelesai'])->name('manajemen-detail-iuran-rw-rt.selesai');
    Route::get('/manajemen-iuran-rw/{id}/gagal', [ManajemenDetailIuranRWRT::class, 'keGagal'])->name('manajemen-detail-iuran-rw-rt.keGagal');
    Route::delete('/manajemen-iuran-rw/{id}', [ManajemenDetailIuranRWRT::class, 'detailGagal'])->name('manajemen-detail-iuran-rw-rt.gagal');
    Route::get('/manajemen-iuran-rw/lihatgambar/{filename}', [ManajemenDetailIuranRWRT::class, 'show'])->name('manajemen-detail-rw-rt.show');
});

// middleware untuk role ketua rt
Route::middleware('role:Ketua_RT')->group(function () {
    Route::get('/dashboard/ketuart', function () {
        return view('/rt/DashboardKetuaRT');
    })->name('dashboard.ketuart');
});

// middleware untuk role ketua rw
Route::middleware('role:Ketua_RW')->group(function () {
    Route::get('/dashboard/ketuarw', function () {
        return view('/rw/DashboardKetuaRW');
    })->name('dashboard.ketuarw');
});

// middleware untuk role super admin
Route::middleware('role:Super_Admin')->group(function () {
    Route::get('/dashboard/superadmin', function () {
        return view('SuperAdmin');
    })->name('dashboard.superadmin');

    // manajemen rw yang ada
    Route::resource('/rw', RWController::class)->names([
        'index' => 'RW.index',
        'create' => 'RW.create',
        'store' => 'RW.store',
        'edit' => 'RW.edit',
        'update' => 'RW.update',
        'destroy' => 'RW.destroy',
        'show' => 'RW.show',
    ]);
});




// middleware bagi pengguna yang sudah login, untuk seluruh role
Route::middleware('auth')->group(function () {
    Route::get('/kritik-saran', [KritikSaranController::class, 'index'])->name('kritik.index');
    Route::post('/kritik-saran', [KritikSaranController::class, 'store'])->name('kritik.store');
});

Route::get('/index', function () {
    return view('index');
});

Route::get('/dashboard/ketuarw', function () {
    return view('/rw/DashboardKetuaRW');
})->name('dashboard.ketuarw');

Route::get('/dashboard/ketuart', function () {
    return view('/rt/DashboardKetuaRT');
})->name('dashboard.ketuart');

// Define a function to get the dashboard data
function getDashboardData(Request $request)
{
    $year = $request->input('year', date('Y')); // Default to current year
    $interval = $request->input('interval', '7-days'); // Default interval

    // Replace these example values with actual data retrieval based on $year and $interval
    if ($interval === '7-days') {
        $labels = ['Day 1', 'Day 2', 'Day 3', 'Day 4', 'Day 5', 'Day 6', 'Day 7'];
        $pemasukanData = [4500, 5200, 4800, 6000, 5800, 6200, 5000]; // Example values
        $pengeluaranData = [4000, 4900, 4600, 5800, 5400, 5900, 4500]; // Example values
    } elseif ($interval === 'month') {
        $labels = ['Week 1', 'Week 2', 'Week 3', 'Week 4'];
        $pemasukanData = [15000, 18000, 17000, 20000]; // Example values
        $pengeluaranData = [14000, 17000, 16000, 19000]; // Example values
    } elseif ($interval === 'yoy') {
        $labels = ['2020', '2021', '2022', '2023'];
        $pemasukanData = [120000, 140000, 160000, 180000]; // Example values
        $pengeluaranData = [100000, 120000, 140000, 160000]; // Example values
    }

    // Calculate total Pemasukan and Pengeluaran for the Pie Chart
    $totalPemasukan = array_sum($pemasukanData);
    $totalPengeluaran = array_sum($pengeluaranData);

    // Pie Chart Data
    $piechart_data = [
        'labels' => ['Pemasukan', 'Pengeluaran'],
        'data' => [$totalPemasukan, $totalPengeluaran],
        'backgroundColor' => ['rgba(75, 192, 192, 0.6)', 'rgba(255, 99, 132, 0.6)'], // Matching colors
    ];

    // Bar Chart Data
    $barchart_data = [
        'labels' => $labels,
        'datasets' => [
            [
                'label' => 'Pemasukan',
                'data' => $pemasukanData,
                'backgroundColor' => 'rgba(75, 192, 192, 0.6)', // Color for Pemasukan
            ],
            [
                'label' => 'Pengeluaran',
                'data' => $pengeluaranData,
                'backgroundColor' => 'rgba(255, 99, 132, 0.6)', // Color for Pengeluaran
            ]
        ]
    ];

    // Line Chart Data
    $linechart_data = [
        'labels' => $labels,
        'datasets' => [
            [
                'label' => 'Pemasukan',
                'data' => $pemasukanData,
                'borderColor' => 'rgba(54, 162, 235, 1)',
                'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                'fill' => true,
            ],
            [
                'label' => 'Pengeluaran',
                'data' => $pengeluaranData,
                'borderColor' => 'rgba(255, 99, 132, 1)',
                'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                'fill' => true,
            ]
        ]
    ];

    return compact('barchart_data', 'linechart_data', 'piechart_data', 'totalPemasukan', 'totalPengeluaran', 'year', 'interval');
}

Route::get('/dashboard/admin', function (Request $request) {
    $data = getDashboardData($request);
    return view('admin.DashboardAdmin', $data);
})->name('admin-dashboard');

Route::get('/dashboard/warga', function (Request $request) {
    $data = getDashboardData($request);
    return view('warga.DashboardWarga', $data);
})->name('dashboard.warga');

Route::get('/data-warga/admin', function () {
    return view('/admin/DataWarga');
})->name('data-warga');

Route::get('/manajemen-iuran/admin', function () {
    return view('/admin/ManajemenIuran');
})->name('manajemen-iuran');

Route::get('/program-kerja/admin', function () {
    return view('/admin/ProgramKerjaAdmin');
})->name('admin-program-kerja');

Route::get('/edit-program-kerja/admin', function () {
    return view('/admin/EditProgramKerja');
})->name('edit-program-kerja');

Route::get('/tambah-program-kerja/admin', function () {
    return view('/admin/TambahProgramKerja');
})->name('tambah-program-kerja');

Route::get('/laporan-pengeluaran/admin', function () {
    return view('/admin/LaporanPengeluaran');
})->name('laporan-pengeluaran');

Route::get('/laporan-pemasukan/admin', function () {
    return view('/admin/LaporanPemasukan');
})->name('laporan-pemasukan');

Route::get('/tambah-data-pemasukan/admin', function () {
    return view('/admin/TambahDataPemasukan');
})->name('tambah-data-pemasukan');

Route::get('/tambah-data-pengeluaran/admin', function () {
    return view('/admin/TambahDataPengeluaran');
})->name('tambah-data-pengeluaran');

Route::get('/kritik-saran/admin', function () {
    return view('/admin/KritikSaranAdmin');
})->name('admin-kritik-saran');


Route::get('/program-kerja', function () {
    return view('/warga/LihatProgramKerja');
})->name('program-kerja');

Route::get('/laporan-keuangan/warga', function () {
    return view('/warga/LaporanKeuangan');
})->name('laporan-keuangan');

Route::get('/riwayat-pembayaran/warga', function () {
    return view('/warga/RiwayatPembayaran');
})->name('riwayat-pembayaran');

Route::get('/bayar/warga', function () {
    return view('/warga/Pembayaran');
})->name('pembayaran');

Route::get('/kritik-saran/warga', function () {
    return view('/warga/FormKritikSaran');
})->name('kritik-saran');

Route::get('/edit-profil/warga', function () {
    return view('/warga/editInfo');
})->name('edit.profil');

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout'); // Update with your controller and method


// // chartttt
// Route::get('/admin/DashboardAdmin', [ChartController::class, 'barChart']);
