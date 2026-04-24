<?php

namespace App\Http\Controllers;

use App\Models\Microsaas;
use Illuminate\Http\JsonResponse;

class FrontendConfigController extends Controller
{
    public function __invoke(Microsaas $microsaas): JsonResponse
    {
        return response()->json([
            'name' => $microsaas->name,
            'slug' => $microsaas->slug,
            'backend_base_url' => $microsaas->backend_base_url,
            'frontend_entry_url' => $microsaas->frontend_entry_url,
            'status' => $microsaas->status,
        ]);
    }
}
