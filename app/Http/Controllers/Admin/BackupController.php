<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Spatie\Backup\Tasks\Backup\BackupJobFactory;
use Illuminate\Support\Facades\Artisan;
use Prologue\Alerts\Facades\Alert;
use Illuminate\Support\Facades\Storage;

class BackupController extends CrudController
{
    public function index()
    {
        $files = collect(Storage::disk('local')->files('Laravel'))
            ->filter(fn($file) => str_ends_with($file, '.zip')); // show only .zip backups

        return view('admin.backup.index', compact('files'));
    }


    public function run()
    {
        Artisan::call('backup:run');
        \Alert::success('Atsarginė kopija sėkmingai sukurta!')->flash();
        return back();
    }

    public function download($file)
    {
        $filePath = 'Laravel/' . $file;

        if (!Storage::disk('local')->exists($filePath)) {
            abort(404);
        }

        return Storage::disk('local')->download($filePath);
    }

}
