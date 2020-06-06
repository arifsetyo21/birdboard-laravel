<?php

namespace Tests\Feature;

use App\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    /* NOTE Using faker trait, and Refresh DB after testing */
    use WithFaker, RefreshDatabase;

    /** @test */
    public function guest_cannot_create_projects()
    {
        $project = factory('App\Project')->create();

        $this->get('/projects')->assertRedirect('login');
        $this->get($project->path())->assertRedirect('login');
        $this->post('/projects', $project->toArray())->assertRedirect('login');
    }

    /** @test */
    public function a_user_can_create_a_project()
    {
        $this->withoutExceptionHandling();
        $attributes = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
        ];

        /* NOTE login as a user and create project */
        $this->signIn();

        $this->get('/projects/create')->assertStatus(200);

        $response = $this->post('/projects', $attributes);

        $project = Project::where($attributes)->first();

        $response->assertRedirect($project->path());

        $this->assertDatabaseHas('projects', $attributes);

        $this->get('/projects')->assertSee($attributes['title']);
    }

    /** @test */
    public function a_project_requires_a_title()
    {
        /* NOTE factory()->make() will return object */
        // $attributes = factory('App\Project')->make(['title' => '']);

        /* NOTE factory()->raw will return array */
        $this->signIn();
        $attributes = factory('App\Project')->raw(['title' => '']);

        $this->post('/projects', $attributes)->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_project_requires_a_description()
    {
        $this->signIn();
        $attributes = factory('App\Project')->raw(['description' => '']);
        $this->post('/projects', $attributes)->assertSessionHasErrors('description');
    }

    /** @test */
    public function a_user_can_view_their_project()
    {
        $this->signIn();

        $this->withoutExceptionHandling();

        $project = factory(\App\Project::class)->create(['owner_id' => auth()->id()]);

        $this->get($project->path())
            ->assertSee($project->title)
            ->assertSee(\Illuminate\Support\Str::limit($project->description, 100));
    }

    /** @test */
    public function an_authenticated_user_cannot_view_the_projects_of_others()
    {
        $this->signIn();

        // $this->withoutExceptionHandling();

        $project = factory(\App\Project::class)->create();

        $this->get($project->path())->assertStatus(403);
    }
}
