<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    protected $model = Role::class;

    public function definition()
    {
        return [
            'name' => 'admin',
            'guard_name' => 'api',
            'description' => 'Rôle administrateur',
        ];
    }
}
