<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Mailtrap\Helper\ResponseHelper;
use Mailtrap\MailtrapClient;
use Mailtrap\Mime\MailtrapEmail;
use Symfony\Component\Mime\Address;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('send-mail {to?}', function () {
    $apiToken = env('MAILTRAP_API_TOKEN');
    $to = $this->argument('to') ?: 'ibrahimakhalil2k4@gmail.com';
    $fromEmail = 'hello@gmail.com';
    $fromName = 'Mailtrap Test';

    $isSandbox = true; // Keep true for testing
    $inboxId = env('MAILTRAP_INBOX_ID'); 

    if (!$apiToken) {
        $this->error('MAILTRAP_API_TOKEN is missing in your .env file.');
        return 1;
    }

    if (!$to) {
        $this->error('Recipient missing. Pass one: php artisan send-mail you@example.com');
        return 1;
    }

    if ($isSandbox && !is_numeric($inboxId)) {
        $this->error('Sandbox mode requires a numeric Inbox ID. Please set MAILTRAP_INBOX_ID in your .env file.');
        return 1;
    }

    $email = (new MailtrapEmail())
        ->from(new Address($fromEmail, $fromName))
        ->to(new Address($to))
        ->subject('You are awesome!')
        ->category('Integration Test')
        ->text('Congrats for sending test email with Mailtrap!');

    try {
        $response = MailtrapClient::initSendingEmails(
            apiKey: $apiToken,
            isSandbox: $isSandbox,
            inboxId: $inboxId
        )->send($email);
        $this->info('Email sent. Mailtrap response:');
        $this->line(json_encode(ResponseHelper::toArray($response), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    } catch (\Throwable $e) {
        $this->error('Mail send failed: ' . $e->getMessage());
        return 1;
    }

    return 0;
})->purpose('Send a test email with Mailtrap Sending API');
