<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_has_projects()
    {
        /* NOTE Check relation with unit test */
        $user = factory(\App\User::class)->create();

        $this->assertInstanceOf(Collection::class, $user->projects);
    }
}
