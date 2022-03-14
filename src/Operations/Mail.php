<?php

namespace ShockingHuman\ServerTools\Operations;

use Symfony\Component\HttpClient\Exception\TransportException;
use Symfony\Component\HttpClient\HttpClient;

class Mail
{
    public static function send(string $key, string $to, string $subject, string $body)
    {
        try {
            $mail_client = HttpClient::create();
            $mail_status = $mail_client->request('POST', 'https://api.webdevjm.com/mail', [
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded'
                ],
                'body' => [
                    'key' => $key,
                    'to' => $to,
                    'subject' => $subject,
                    'body' => $body
                ]
            ]);
            return $mail_status->getStatusCode();
        } catch (TransportException $exception){
            return $exception->getMessage();
        }
    }
}