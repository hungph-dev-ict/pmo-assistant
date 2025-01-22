<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TenantRegisteredMail extends Mailable
{
    use Queueable, SerializesModels;

    public $tenant;

    /**
     * Create a new message instance.
     */
    public function __construct($tenant)
    {
        $this->tenant = $tenant;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Tenant Registration Successful')
                    ->view('emails.tenant-registered')
                    ->with([
                        'tenantName' => $this->tenant->name,
                        'tenantEmail' => $this->tenant->headUser->email,
                    ]);
    }
}
