<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CertificateController extends Controller
{
    /**
     * Get user certificates
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        
        $certificates = Certificate::byUser($user->id)->orderBy('created_at', 'desc')->get();
        // Check if user has any certificates
        if ($certificates->isEmpty()) {
            return response()->json([
                'success' => true,
                'message' => 'You have not completed any certifications yet. Start learning to earn your first certificate!',
                'data' => []
            ]);
        }
        
        return response()->json([
            'success' => true,
            'data' => $certificates->map(function ($cert) {
                return [
                    'id' => $cert->id,
                    'user_name' => $cert->user_name,
                    'course_name' => $cert->course_name,
                    'certificate' => $cert->certificate,
                    'certificate_id' => $cert->certificate_id,
                    'issue_date' => $cert->formatted_issue_date,
                    'status' => $cert->certificate == 1 ? 'completed' : 'pending'
                    //'status_badge' => $cert->status_badge,
                ];
            })
        ]);
    }

    /**
     * Get single certificate details
     */
    public function show($id): JsonResponse
    {
        $certificate = Certificate::with('user')->find($id);
        
        if (!$certificate) {
            return response()->json([
                'success' => false,
                'message' => 'Certificate not found'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $certificate->id,
                'user_id' => $certificate->user_id,
                'user_name' => $certificate->user_name,
                'mobile' => $certificate->mobile,
                'course_name' => $certificate->course_name,
                'certificate' => $certificate->certificate,
                'certificate_id' => $certificate->certificate_id,
                'issue_date' => $certificate->formatted_issue_date,
                'expiry_date' => $certificate->expiry_date?->format('d M Y'),
                'issued_by' => $certificate->issued_by,
                'description' => $certificate->description,
            ]
        ]);
    }

    /**
     * Store new certificate
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:tbl_user,id',
            'user_name' => 'required|string',
            'course_name' => 'required|string',
            'certificate' => 'sometimes|boolean',
            'mobile' => 'required|string',
            'issue_date' => 'sometimes|date',
            'expiry_date' => 'sometimes|date|after:issue_date',
        ]);

        $validated['certificate_id'] = Certificate::generateCertificateId();
        
        $certificate = Certificate::create($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Certificate created successfully',
            'data' => $certificate
        ], 201);
    }

    /**
     * Update certificate status
     */
    public function updateStatus(Request $request, $id): JsonResponse
    {
        $certificate = Certificate::find($id);
        
        if (!$certificate) {
            return response()->json([
                'success' => false,
                'message' => 'Certificate not found'
            ], 404);
        }
        
        $certificate->update([
            'certificate' => $request->certificate ?? true,
            'issue_date' => $request->certificate ? now() : null,
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Certificate status updated',
            'data' => $certificate
        ]);
    }
}