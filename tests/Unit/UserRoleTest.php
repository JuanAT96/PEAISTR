<?php

namespace Tests\Unit;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserRoleTest extends TestCase
{
    public function test_asignar_rol_a_usuario()
    {
        // Crear un usuario y un rol
        $user = User::factory()->create();
        $role = Role::create(['name' => 'administrativo']);

        // Asignar el rol al usuario
        $user->assignRole($role);

        // Verificar que el usuario tiene el rol asignado
        $this->assertTrue($user->hasRole('administrativo'));
    }
}
