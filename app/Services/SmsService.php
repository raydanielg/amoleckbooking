<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class SmsService
{
    public function send(string $number, string $message, array $options = []): array
    {
        $provider = config('sms.provider', 'valuesms');
        $number = $this->normalizeNumber($number);

        if ($provider === 'oasis') {
            return $this->sendOasis($number, $message, $options);
        }

        return $this->sendValueSms($number, $message, $options);
    }

    protected function sendValueSms(string $number, string $message, array $options = []): array
    {
        $conf = config('sms.valuesms');
        $language = $options['language'] ?? $conf['language'] ?? 'English';
        $payload = [[
            'user' => $conf['user'] ?? '',
            'pwd' => $conf['pwd'] ?? '',
            'number' => $number,
            'msg' => $language === 'Unicode' ? $this->toHex($message) : $message,
            'sender' => $conf['sender'] ?? 'SMS Alert',
            'language' => $language,
        ]];

        $url = $conf['url'] ?? 'https://valuesms.ae/sendsms_api_json.aspx';
        $resp = Http::asJson()->post($url, $payload);

        return [
            'ok' => $resp->successful(),
            'status' => $resp->status(),
            'body' => $resp->json() ?? $resp->body(),
        ];
    }

    protected function sendOasis(string $number, string $message, array $options = []): array
    {
        $conf = config('sms.oasis');
        $params = [
            'user' => $conf['user'] ?? '',
            'pwd' => $conf['pwd'] ?? '',
            'senderid' => $conf['sender'] ?? 'SMS Alert',
            'mobileno' => $number,
            'msgtext' => $message,
            'priority' => $conf['priority'] ?? 'High',
            'CountryCode' => $conf['country_code'] ?? 'ALL',
        ];
        $url = $conf['url'] ?? 'http://sms.oasistech.co.tz/sendurlcomma.aspx';
        $resp = Http::get($url, $params);

        return [
            'ok' => $resp->successful(),
            'status' => $resp->status(),
            'body' => $resp->body(),
        ];
    }

    protected function normalizeNumber(string $number): string
    {
        $n = preg_replace('/\D+/', '', $number);
        $defaultCc = trim((string) config('sms.default_country_code', ''));
        if (Str::startsWith($n, '0') && $defaultCc !== '') {
            $n = ltrim($n, '0');
            $n = $defaultCc . $n;
        }
        return $n;
    }

    protected function toHex(string $text): string
    {
        $result = '';
        $len = mb_strlen($text, 'UTF-8');
        for ($i = 0; $i < $len; $i++) {
            $code = mb_ord(mb_substr($text, $i, 1, 'UTF-8'), 'UTF-8');
            $result .= strtoupper(dechex($code));
        }
        return $result;
    }
}
