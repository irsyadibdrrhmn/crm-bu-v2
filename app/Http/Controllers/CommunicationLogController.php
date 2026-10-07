<?php

namespace App\Http\Controllers;

use App\Models\CommunicationLog;
use Illuminate\Http\Request;

class CommunicationLogController extends Controller
{
    public function index()
    {
        $logs = CommunicationLog::with('user')->latest()->paginate(20);
        return view('communication-logs.index', compact('logs'));
    }
}

