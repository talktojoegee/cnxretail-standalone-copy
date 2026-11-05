<?php

namespace App\Mail;

use App\Models\Contact;
use App\Models\InvoiceMaster;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvoiceMailer extends Mailable
{
    use Queueable, SerializesModels;
    public $invoice, $contact;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Contact $contact, InvoiceMaster $invoice)
    {
        $this->invoice = $invoice;
        $this->contact = $contact;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
       return dd('read');
        return $this->from($this->invoice->getTenant->email ?? 'no-reply@cnxretail.com')
            ->subject('New Invoice')
            ->markdown('mails.invoice.invoice');
    }
}
