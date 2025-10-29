<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\EmailService;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $details;
    protected $destinations;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($details, $destinations)
    {
        $this->details = $details;
        $this->destinations = $destinations;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $emailService = new EmailService();
        $emailService->sendEmail($this->details, $this->destinations);
    }
}
