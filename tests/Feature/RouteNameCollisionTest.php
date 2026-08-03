<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class RouteNameCollisionTest extends TestCase
{
    public function test_admin_route_names_are_unique(): void
    {
        $routes = Route::getRoutes();
        $names = [];

        foreach ($routes as $route) {
            $name = $route->getName();

            if ($name === null) {
                continue;
            }

            $this->assertFalse(
                isset($names[$name]),
                "Route name [$name] is duplicated."
            );

            $names[$name] = true;
        }
    }
}
