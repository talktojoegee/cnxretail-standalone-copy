<?php

namespace App\Mail;

use App\Models\Contact;
use App\Models\ReceiptMaster;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReceiptMailer extends Mailable
{
    use Queueable, SerializesModels;
    public $receipt, $contact;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(ReceiptMaster $receipt, Contact $contact)
    {
        $this->receipt = $receipt;
        $this->contact = $contact;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('no-reply@cnxretail.com')
            ->subject('New Receipt')
            ->markdown('mails.receipt.receipt');
    }
}
