<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AuditLog;

class AuditLogController extends Controller
{
    /**
     * Get all audit logs (read-only)
     */
    public function index(Request $request)
    {
        return response()->json(AuditLog::paginate(15));
    }

    /**
     * Get a specific audit log
     */
    public function show(AuditLog $auditLog)
    {
        return response()->json($auditLog);
    }
}
