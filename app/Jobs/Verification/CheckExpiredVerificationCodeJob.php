<?php

namespace App\Jobs\Verification;

use App\Models\VerificationCode;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class CheckExpiredVerificationCodeJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    protected $code_id;
    public function __construct($code_id)
    {
        $this->code_id = $code_id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $code = VerificationCode::find($this->code_id);

        if ($code && $code->expires_at <= now()) {
            $code->delete();
        }
    }
}
