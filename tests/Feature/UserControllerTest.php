<?php

namespace Tests\Feature;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    public function test_crear_usuario_desde_formulario()
    {
        // Crear un usuario de prueba y autenticarlo
        $user = User::factory()->create();
        $this->actingAs($user);

        // Crear un rol para asignar al usuario
        $role = Role::create(['name' => 'docente']);

        // Simular una solicitud POST al endpoint de creación de usuarios
        $response = $this->post('/users', [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'docente',
        ]);

        // Verificar que se redirige correctamente después de crear el usuario
        $response->assertRedirect('/users');

        // Verificar que el usuario se ha guardado en la base de datos
        $this->assertDatabaseHas('users', [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
        ]);

        // Verificar que el usuario tiene el rol asignado
        $newUser = User::where('email', 'jane@example.com')->first();
        $this->assertTrue($newUser->hasRole('docente'));
    }

    public function test_actualizar_usuario_desde_formulario()
    {
        // Crear un usuario de prueba y autenticarlo
        $user = User::factory()->create();
        $this->actingAs($user);

        // Crear un rol para asignar al usuario
        $role = Role::create(['name' => 'administrativo']);

        // Crear otro usuario para actualizar
        $userToUpdate = User::factory()->create();

        // Simular una solicitud PUT al endpoint de actualización de usuarios
        $response = $this->put("/users/{$userToUpdate->id}", [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
            'role' => 'administrativo',
        ]);

        // Verificar que se redirige correctamente después de actualizar el usuario
        $response->assertRedirect('/users');

        // Verificar que los datos del usuario se han actualizado en la base de datos
        $this->assertDatabaseHas('users', [
            'id' => $userToUpdate->id,
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ]);

        // Verificar que el usuario tiene el nuevo rol asignado
        $userToUpdate->refresh();
        $this->assertTrue($userToUpdate->hasRole('administrativo'));
    }

    public function test_listar_usuarios()
    {
        // Crear un usuario de prueba y autenticarlo
        $user = User::factory()->create();
        $this->actingAs($user);

        // Simular una solicitud GET al endpoint de listado de usuarios
        $response = $this->get('/users');

        // Verificar que la respuesta es exitosa (código 200)
        $response->assertStatus(200);

        // Verificar que la vista correcta se está mostrando
        $response->assertViewIs('Users/Index');

        // Verificar que el usuario de prueba aparece en la lista
        $response->assertSee($user->name);
    }

    public function test_eliminar_usuario()
    {
        // Crear un usuario de prueba y autenticarlo
        $user = User::factory()->create();
        $this->actingAs($user);

        // Crear otro usuario para eliminar
        $userToDelete = User::factory()->create();

        // Simular una solicitud DELETE al endpoint de eliminación de usuarios
        $response = $this->delete("/users/{$userToDelete->id}");

        // Verificar que se redirige correctamente después de eliminar el usuario
        $response->assertRedirect('/users');

        // Verificar que el usuario ya no existe en la base de datos
        $this->assertDatabaseMissing('users', [
            'id' => $userToDelete->id,
        ]);
    }
}
