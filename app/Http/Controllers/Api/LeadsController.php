<?php

namespace App\Http\Controllers\Api;

use App\Catalog;
use App\Http\Controllers\Controller;
use App\Jobs\AddLeadToCatalog;

class LeadsController extends Controller
{
    public function store(Catalog $catalog)
    {
        $data = request()->validate([
            'phone' => ['required'],
            'email' => ['sometimes', 'nullable'],
            'first_name' => ['sometimes', 'nullable'],
            'last_name' => ['sometimes', 'nullable'],
        ]);

        AddLeadToCatalog::dispatch($catalog, $data);

        return response()->json([
            'success' => true,
        ]);
    }
}
