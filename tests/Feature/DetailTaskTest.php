<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Tests\TestCase;

class DetailTaskTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        
        $user = User::factory()->create([
            'name' => 'user1',
            'email' => 'user1@he2b.be'
        ]);
        $user->tasks()->attach(1); // Eloquent version
        $response = $this->call('GET', '/api/tasks/personList/1');
        
        
        $tasks = json_decode($response->getContent(), true);

        $userFound = false;

        foreach ($tasks as $task) {
            if ($task['name'] === 'user1' && $task['email'] === 'user1@he2b.be') {
                $userFound = true;
                break;
            }
        }
    
        $this->assertTrue($userFound);
    }
}
