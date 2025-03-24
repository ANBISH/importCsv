<?php

namespace App\Http\Controllers;

use App\Exports\UserExport;
use App\Http\Requests\CsvImportRequest;
use App\Jobs\ProcessCsvImport;
use Maatwebsite\Excel\Facades\Excel;

class CsvController extends Controller
{
    public function index()
    {
        return view('csv.index');
    }

    public function import(CsvImportRequest $request)
    {
        $path = $request->file('csv_file')->store('csv_imports');

       ProcessCsvImport::dispatch($path);

        return back()->with('success', 'Імпорт розпочато. Дані обробляються у фоновому режимі.');
    }

    public function export()
    {
        return Excel::download(new UserExport, 'export.csv');
    }
}
