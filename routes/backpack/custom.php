<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BackupController;
use Rap2hpoutre\LaravelLogViewer\LogViewerController;
// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix' => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace' => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::crud('user', 'UserCrudController');
    Route::crud('project', 'ProjectCrudController');
    Route::crud('comments', 'CommentsCrudController');
    Route::crud('user-application', 'UserApplicationCrudController');


    Route::get('backup', [BackupController::class, 'index'])->name('admin.backup.index');
    Route::post('backup', [BackupController::class, 'run'])->name('admin.backup.run');
    Route::get('backup/download/{file}', [BackupController::class, 'download'])->name('admin.backup.download');

    Route::get('logs', [LogViewerController::class, 'index'])->name('admin.logs.index');
    


    Route::crud('employers', 'EmployersCrudController');
}); // this should be the absolute last line of this file