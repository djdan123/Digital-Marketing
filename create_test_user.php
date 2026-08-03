<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$user = App\Models\User::where('email', 'test@example.com')->first();
if (!$user) {
    $user = new App\Models\User;
    $user->name = 'Test User';
    $user->email = 'test@example.com';
    $user->password = Illuminate\Support\Facades\Hash::make('password123');
    $user->email_verified_at = now();
    $user->save();
    echo 'created:' . $user->email;
} else {
    $user->name = 'Test User';
    $user->password = Illuminate\Support\Facades\Hash::make('password123');
    $user->email_verified_at = now();
    $user->save();
    echo 'updated:' . $user->email;
}
