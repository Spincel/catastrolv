<?php
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SettingController; 
use App\Http\Controllers\OfficialNumberController;
use App\Http\Controllers\ProfileController; 
use App\Http\Controllers\WelcomeController; // Import WelcomeController

Route::get('/', [WelcomeController::class, 'index']); // Use WelcomeController@index for the root route

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('admin.dashboard');

// Agrupamos todas las rutas que requieren Login
Route::middleware(['auth', 'verified'])->group(function () {

    // 1. RUTA PRINCIPAL (DASHBOARD)
    // Ahora apunta a la nueva función 'dashboard' del controlador
    Route::get('/inicio', [OfficialNumberController::class, 'dashboard'])
        ->name('dashboard');

    // 2. Rutas de Perfil (Profile)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/users', [UserController::class, 'store'])->name('users.store')->middleware('admin');
    Route::get('/users', [UserController::class, 'index'])->name('users.index')->middleware('admin');


    // 3. Rutas de Números Oficiales
    
    // Vista para crear nuevo (Formulario)
    Route::get('/numeros-oficiales/nuevo', function () {
        return view('official_numbers.index'); 
    })->name('official_numbers.index');

    // Guardar (POST)
    Route::post('/numeros-oficiales/guardar', [OfficialNumberController::class, 'store'])
        ->name('official_numbers.store');

    // Vista de Listado Simple
    Route::get('/numeros-oficiales/lista', [OfficialNumberController::class, 'list'])
        ->name('official_numbers.list');

    // Exploración individual (Detalles)
    Route::get('/numeros-oficiales/explorar/{officialNumber}', [OfficialNumberController::class, 'explore'])
        ->name('official_numbers.explore');
        
    // PDF
    Route::get('/numeros-oficiales/pdf/{officialNumber}', [OfficialNumberController::class, 'generatePdf'])
        ->name('official_numbers.pdf');
    
    // Subida de documentos
    Route::post('/numeros-oficiales/upload/{officialNumber}', [OfficialNumberController::class, 'uploadDocument'])
        ->name('official_numbers.upload_doc');

    Route::get('/configuracion', [SettingController::class, 'edit'])->name('settings.edit');
    Route::post('/configuracion', [SettingController::class, 'update'])->name('settings.update');

    // 4. Rutas de Reportes
    Route::get('/reportes', [\App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
    Route::get('/reportes/exportar', [\App\Http\Controllers\ReportController::class, 'export'])->name('reports.export');

});

require __DIR__.'/auth.php';
