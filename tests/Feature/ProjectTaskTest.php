<?php

namespace Tests\Feature;

use App\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProjectTaskTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */
    public function a_project_can_have_tasks()
    {
        $this->withoutExceptionHandling();

        $this->signIn();

        $project = auth()->user()->projects()->create(
            factory('App\Project')->raw()
        );

        $this->post($project->path() . '/tasks', ['body' => 'Test task']);

        $this->get($project->path())->assertSee('Test task');
    }

    /** @test */
    public function a_task_requires_a_body()
    {
        $this->signIn();

        $project = auth()->user()->projects()->create(
            factory(Project::class)->raw()
        );

        $attributes = factory('App\Task')->raw(['body' => '']);

        $this->post($project->path() . '/tasks', $attributes)->assertSessionHasErrors('body');
    }

    /** @test */
    public function only_owner_of_a_project_may_add_task()
    {
        $this->signIn();

        $project = factory('App\Project')->create();

        $this->post($project->path() . '/tasks', ['body' => 'Test task'])->assertStatus(403);

        $this->assertDatabaseMissing('tasks', ['body' => 'Test task']);
    }
}
