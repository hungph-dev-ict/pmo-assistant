<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class TenantUserRegisteredMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $password;
    public $tenantName;
    public $role;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, $password, $tenantName, $role)
    {
        $this->user = $user;
        $this->password = $password;
        $this->tenantName = $tenantName;
        $this->role = $role;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Your Account Has Been Created!')
                    ->view('emails.tenant_user_registered')
                    ->with([
                        'name' => $this->user->name,
                        'email' => $this->user->email,
                        'password' => $this->password,
                        'tenantName' => $this->tenantName,
                        'role' => $this->role,
                    ]);
    }
}
