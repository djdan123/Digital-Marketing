<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Advertiser;
use App\Models\Media;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): Response
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['nullable', 'in:advertiser,media_manager'],
            'company_name' => ['nullable', 'string', 'max:255'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->string('password')),
            'role' => $request->role ?? 'advertiser',
        ]);

        if ($request->role === 'media_manager') {
            $media = Media::create([
                'name' => $request->company_name ?: $request->name,
                'type' => 'radio',
                'status' => 'active',
                'pricing_type' => 'fixed',
                'base_price' => 0,
                'description' => 'Média créé lors de l’inscription du media manager',
            ]);

            $user->forceFill(['media_id' => $media->id])->save();
        }

        event(new Registered($user));

        Auth::login($user);

        return response()->noContent();
    }
}
