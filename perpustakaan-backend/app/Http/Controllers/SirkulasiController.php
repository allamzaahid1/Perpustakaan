<?php

namespace App\Http\Controllers;

use App\Services\Sirkulasi\SirkulasiService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SirkulasiController extends Controller
{
    protected $sirkulasiService;

    public function __construct(SirkulasiService $sirkulasiService)
    {
        $this->sirkulasiService = $sirkulasiService;
    }

    public function index(Request $request)
    {
        $data = $this->sirkulasiService->index($request);
        return response()->json(['success' => true, 'data' => $data]);
    }
}