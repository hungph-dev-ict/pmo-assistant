<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Log;

class TenantRegisteredMail extends Mailable
{
    use Queueable, SerializesModels;

    public $tenant;
    public $tenantPassword;

    /**
     * Create a new message instance.
     */
    public function __construct($tenant, $tenantPassword)
    {
        $this->tenant = $tenant;
        $this->tenantPassword = $tenantPassword;
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
                        'tenantPassword' => $this->tenantPassword,
                    ]);
    }
}
