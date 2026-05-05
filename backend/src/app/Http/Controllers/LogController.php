<?php

namespace App\Http\Controllers;

use App\Models\Log as AdminLog;
use Illuminate\Http\Request;

class LogController extends Controller
{
    /**
     * Obtiene todos los logs para el filtrado en el frontend.
     */
    public function index()
    {
        return AdminLog::with('user:id,name')
            ->orderBy('created_at', 'desc')
            ->get();
    }
}