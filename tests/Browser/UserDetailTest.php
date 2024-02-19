<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseTruncation;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;


class UserDetailTest extends DuskTestCase
{
    use DatabaseTruncation;
    /**
     * A Dusk test example.
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1));
        });
    }

    public function test_admin_if_the_button_detail_is_visible(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/tasks/1')
                ->visit('/tasks')
                ->waitForTextIn('tr:first-child td:first-child', "Tâche 1")
                ->press("Details")
                ->pause(1000)
                ->assertVisible('a.btn.btn-outline-info[href="/user/1"]')
            ;
        });
    }


    public function test_admin_detail_is_accessible(): void
    {
        $this->browse(function (Browser $browser) {
            $user = User::find(1);
            $browser->visit('/user/1')
                ->assertSee('admin')
                ->assertSee("admin's tasks")
                ->assertSee($user->email)
            ;
        });
    }

    public function test_user_detail_is_not_accesible(): void
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->loginAs(User::factory()->create())
                ->visit('/user/1')
                ->assertSee("403")
            ;
        });
    }

    public function test_user_detail_button_is_not_visible(): void
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->loginAs(User::factory()->create())
                ->visit('/tasks')
                ->waitForTextIn('tr:first-child td:first-child', "Tâche 1")
                ->press("Details")
                ->pause(1000)
                ->assertMissing('a.btn.btn-outline-info[href="/user/1"]')
            ;
        });
    }
}