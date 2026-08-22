<?php

namespace Webkul\Email\Mails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Symfony\Component\Mime\Email as MimeEmail;

class Email extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new email instance.
     *
     * @return void
     */
    public function __construct(public $email) {}

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $senderName = core()->getConfigData('email.smtp.account.sender_name')
            ?: config('mail.from.name')
            ?: $this->email->name
            ?: (auth()->guard('user')->check() ? auth()->guard('user')->user()->name : null);

        $fromAddress = is_array($this->email->from)
            ? (current($this->email->from) ?: config('mail.from.address'))
            : ($this->email->from ?: config('mail.from.address'));

        $this->from($fromAddress, $senderName)
            ->to($this->email->reply_to)
            ->replyTo($fromAddress, $senderName)
            ->cc($this->email->cc ?? [])
            ->bcc($this->email->bcc ?? [])
            ->subject($this->email->parent_id ? $this->email->parent->subject : $this->email->subject)
            ->html($this->email->reply);

        $this->withSymfonyMessage(function (MimeEmail $message) {
            $message->getHeaders()->addIdHeader('Message-ID', $this->email->message_id);

            $rawReferences = $this->email->parent_id
                ? ($this->email->parent->reference_ids ?? [])
                : ($this->email->reference_ids ?? []);

            $references = is_array($rawReferences)
                ? \Illuminate\Support\Arr::flatten($rawReferences)
                : [$rawReferences];

            $message->getHeaders()->addTextHeader('References', implode(' ', array_filter($references)));
        });

        foreach ($this->email->attachments as $attachment) {
            $this->attachFromStorage($attachment->path);
        }

        return $this;
    }
}
