<?php
namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\Email;

class UserApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_user()
    {
        $payload = [
            'first_name' => 'Jan',
            'last_name' => 'Kowalski',
            'phone_number' => '123456789',
            'emails' => ['jan@example.com', 'kowalski@example.com']
        ];

        $response = $this->postJson('/api/users', $payload);
        $response->assertStatus(201);


        $this->assertDatabaseHas('users', ['first_name' => 'Jan']);
        $this->assertCount(2, Email::all());
    }

}
