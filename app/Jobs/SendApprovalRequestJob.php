<?php

namespace App\Jobs;

use App\Models\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class SendApprovalRequestJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $documentId;

    /**
     * Create a new job instance.
     *
     * @param int $documentId
     */
    public function __construct(int $documentId)
    {
        $this->documentId = $documentId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $document = Document::find($this->documentId);

        if (!$document) {
            return;
        }

        $department = $document->department;
        $manager = $department?->manager;

        if (!$manager) {
            return;
        }

        // Create approval request if not exists
        if (!$document->approvalRequests()->where('approver_id', $manager->id)->exists()) {
            $document->approvalRequests()->create([
                'user_id' => $document->user_id,      // user who uploaded
                'approver_id' => $manager->id,
                'status' => 'pending',
                'assigned_by' => $document->user_id,
            ]);
        }
    }
}
