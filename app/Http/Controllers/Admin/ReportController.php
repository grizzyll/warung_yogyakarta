<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OrderExport;
use App\Exports\RestockExport;
use Carbon\Carbon;

class ReportController extends Controller
{
    // Halaman Menu Laporan
    public function index()
    {
        return view('admin.reports.index');
    }

    // Download Penjualan
    public function exportOrders()
    {
        $fileName = 'Laporan_Penjualan_' . Carbon::now()->format('Y-m-d') . '.xlsx';
        return Excel::download(new OrderExport, $fileName);
    }

    // Download Belanja
    public function exportRestocks()
    {
        $fileName = 'Laporan_Belanja_' . Carbon::now()->format('Y-m-d') . '.xlsx';
        return Excel::download(new RestockExport, $fileName);
    }
}