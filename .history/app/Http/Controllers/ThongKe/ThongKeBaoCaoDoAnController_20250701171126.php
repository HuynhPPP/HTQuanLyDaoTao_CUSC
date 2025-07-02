<?php

namespace App\Http\Controllers\ThongKe;

use App\Http\Controllers\Controller;
use App\Models\ClassModel;
use App\Models\Report;
use App\Models\Teacher;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Shared\Converter;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReportsExport;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ThongKeBaoCaoDoAnController extends Controller
{
    //
}
