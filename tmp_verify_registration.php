<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Http\Controllers\Api\Auth\RegisteredUserController;
use App\Models\Media;
use App\Models\User;
use Illuminate\Http\Request;

$request = new Request([
    'name' => 'Test',
    'email' => 't5@example.com',
    'password' => 'password',
    'password_confirmation' => 'password',
    'role' => 'media_manager',
    'company_name' => 'Radio Test',
]);

$response = (new RegisteredUserController())->store($request);
$user = User::where('email', 't5@example.com')->first();
$media = Media::where('name', 'Radio Test')->first();

echo 'status=' . $response->getStatusCode() . PHP_EOL;
echo 'user=' . ($user?->id ?? 'null') . '|' . ($user?->media_id ?? 'null') . PHP_EOL;
echo 'media=' . ($media?->id ?? 'null') . PHP_EOL;
