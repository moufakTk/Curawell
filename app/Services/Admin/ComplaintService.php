<?php

namespace App\Services\Admin;

use App\Events\WhatsAppAnalysesPatient;
use App\Mail\ComplaintEmail;
use App\Models\Complaint;
use App\Enums\StylReplyOfComplaint;
use App\Services\AuthServices\VerificationService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ComplaintService
{
    public function __construct(private VerificationService $verificationService)
    {
    }

    public function getAllComplaints($perPage = 15)
    {
        return Complaint::with('complaint_patient')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function getComplaintById($id)
    {
        return Complaint::with('complaint_patient')->findOrFail($id);
    }

    public function update($request, $complaint)
    {
        $complaint->update([
            'message_reply' => $request->message_reply,
            'reply' => 1,
        ]);



        if ($complaint->phone) {
            $message = "Hello 👋 {$complaint->complaint_patient->patient_user->full_name},

Thank you for contacting us! 💌

Here's our response to your complaint:
{$complaint->message_reply}

We appreciate your feedback and thank you for helping us improve! 🌟

Best regards,
Curawell 🤝";

            $this->verificationService->whatsappMessage('0997393611', $message);
}elseif ($complaint->email) {
Mail::to($complaint->email)->send(new ComplaintEmail( $complaint->message_reply,$complaint->complaint_patient->patient_user->full_name));
        }
            return $complaint;


    }

    public function delete($complaint)
    {
        try {
            $complaint->delete();

            return true;
        } catch (\Exception $e) {
            Log::error('Error deleting complaint: ' . $e->getMessage());
            throw new \Exception('Failed to delete complaint');
        }
    }

}
