<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CertificateController extends Controller
{
    public function index(): JsonResponse
    {
        $certificates = Certificate::with('user')->get();
        return response()->json($certificates);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_id'      => 'required|exists:tbl_user,id',
            'course_name'  => 'required|string|max:150',
            'certificate'  => 'boolean',
            'status'       => 'boolean',
        ]);

        $certificate = Certificate::create($validated);
        return response()->json($certificate, 201);
    }

    public function show(Certificate $certificate): JsonResponse
    {
        $certificate->load('user');
        return response()->json($certificate);
    }

    // update, destroy ...
}
