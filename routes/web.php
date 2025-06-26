<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-email', function () {
    Mail::raw('✅ This is a test email sent via Gmail SMTP from Laravel!', function ($message) {
        $message->to('mohndalmsre43@gmail.com') // ← هون حط إيميلك الحقيقي لتوصلك الرسالة
        ->subject('Laravel Gmail SMTP Test');
    });

    return '✅ Email sent!';
});
