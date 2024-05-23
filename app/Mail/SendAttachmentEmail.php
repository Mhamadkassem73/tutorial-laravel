<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendAttachmentEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $body;
    public $attachmentPath;
    public $attachmentPathSignature;
    public $ccRecipients;

    public function __construct($subject, $body, $attachmentPath = null,$attachmentPathSignature = null, $ccRecipients = [])
    {
        $this->subject = $subject;
        $this->body = $body;
        $this->attachmentPath = $attachmentPath;
        $this->attachmentPathSignature = $attachmentPathSignature;
        $this->ccRecipients = $ccRecipients;
    }

    public function build()
    {
        $mail = $this->subject($this->subject)->view('emails.attachment', ['bodyContent' => $this->body]);

        if (!empty($this->ccRecipients)) {
            foreach ($this->ccRecipients as $recipient) {
                $mail->cc($recipient);
            }
        }

        if ($this->attachmentPath && file_exists($this->attachmentPath)) {
            $mail->attach($this->attachmentPath);
        }

        if($this->attachmentPathSignature && file_exists($this->attachmentPathSignature))
        {
            $mail->attach($this->attachmentPathSignature);
        }

        return $mail;
    }
}
