<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Console\Helper\Helper;

class Project extends Model
{

    protected $guarded = [];

    public $old = [];

    public function path()
    {
        return "/projects/{$this->id}";
    }

    public function owner()
    {
        return $this->belongsTo(\App\User::class, 'owner_id');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function addTask($body)
    {
        $task = $this->tasks()->create(compact('body'));
        // Activity::create([
        //     'project_id' => $this->id,
        //     'description' => 'created_task'
        // ]);

        return $task;
    }

    public function activity()
    {
        return $this->hasMany(Activity::class)->latest();
    }

    public function recordActivity($description)
    {
        // var_dump(array_diff($this->old, $this->toArray()));
        /* NOTE refactoring activity feature to accossiation */
        $this->activity()->create([
            'description' => $description,
            'changes' => $this->activityChanges($description)
        ]);

        // \App\Activity::create([
        //     'project_id' => $this->id,
        //     'description' => $type,
        // ]);
    }

    protected function activityChanges($description)
    {
        if ($description == 'updated') {
            return [
                'before' => array_except(array_diff($this->old, $this->getAttributes()), 'updated_at'),
                'after' => array_except($this->getChanges(), 'updated_at'),
            ];
        }
    }
}
