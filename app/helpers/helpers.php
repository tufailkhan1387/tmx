<?php

use Illuminate\Support\Facades\Http;
use Google\Client as GoogleClient;

function table_date($datetime)
{
    $date = DateTime::createFromFormat('Y-m-d\TH:i:s.u\Z', $datetime);
    if ($date instanceof DateTime) {
        return $date->format('M d, Y');
    } else {
        return 'Invalid datetime';
    }
}

function end_url()
{
    return url('/api') . '/';
}

function system_role()
{
    $user = Auth()->user();
    $user_roles =  $user->roles->pluck('name', 'name')->all();
    $system_roles = ['software_manager' => 'software_manager'];
    return ($user_roles  == $system_roles) ? true : false;
}

function user_company_id()
{
    $company_id = Auth()->user()->company_id;
    return $company_id;
}

function filter_company_id($name)
{
    $transformedName = preg_replace('/^[^#]*#/', '', $name);
    return ucfirst($transformedName);
}

function format_date($datetime)
{
    $formats = [
        'Y-m-d\TH:i:s.u\Z',
        'Y-m-d\TH:i:s\Z',
        'Y-m-d H:i:s',
        'Y-m-d',
    ];

    foreach ($formats as $format) {
        $date = DateTime::createFromFormat($format, $datetime);
        if ($date instanceof DateTime) {
            return $date->format('d/m/Y');
        }
    }

    return 'Invalid datetime';
}

function format_date_with_time($datetime)
{
    $formats = [
        'Y-m-d\TH:i:s.u\Z',
        'Y-m-d\TH:i:s\Z',
        'Y-m-d H:i:s',
        'Y-m-d',
    ];

    foreach ($formats as $format) {
        $date = DateTime::createFromFormat($format, $datetime);
        if ($date instanceof DateTime) {
            return $date->format('d/m/Y H:i:s');
        }
    }

    return 'Invalid datetime';
}

function sendNotification($fcmToken, $title, $body)
{
    $url = 'https://fcm.googleapis.com/v1/projects/notification-3804c/messages:send'; // Replace with your project ID
    $serviceAccountPath = public_path('notification-3804c-b91a8fe40ad0.json'); // Path to your service account JSON file
    // return $serviceAccountPath;

    $client = new GoogleClient();
    $client->setAuthConfig($serviceAccountPath);
    $client->addScope('https://www.googleapis.com/auth/firebase.messaging');

    $token = $client->fetchAccessTokenWithAssertion()['access_token'];

    $message = [
        'message' => [
            'token' => $fcmToken,
            'notification' => [
                'title' => $title,
                'body' => $body,
            ],
        ],
    ];

    $headers = [
        'Authorization' => 'Bearer ' . $token,
        'Content-Type' => 'application/json',
    ];

    $response = Http::withHeaders($headers)->post($url, $message);

    if ($response->successful()) {
        return ['status' => 'success', 'message' => 'Notification sent successfully', 'response' => $response->json()];
    } else {
        return ['status' => 'error', 'message' => 'Notification failed', 'response' => $response->json()];
    }
}
