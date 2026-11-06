<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\M_DataKaryawan;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = M_DataKaryawan::all();
        return response()->json($employees);
    }
}
