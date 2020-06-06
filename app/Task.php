<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $guarded = [];

    /* NOTE This will update to column updated_at in project */
    protected $touches = ['project'];

    /* NOTE this will change convert value of column to boolean */
    protected $casts = [
        'completed' => 'boolean'
    ];

    protected static function boot()
    {
        parent::boot();

        /* NOTE Has changed with observer */
        // static::created(function ($task) {

        //     $task->project->recordActivity('created_task');
        // });

        // static::deleted(function ($task) {
        //     $task->project->recordActivity('deleted_task');
        // });
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function path()
    {
        return '/projects/' . $this->project->id . '/tasks/' . $this->id;
    }

    public function complete()
    {
        $this->update(['completed' => true]);

        $this->recordActivity('completed_task');
    }

    public function incomplete()
    {
        $this->update(['completed' => false]);

        $this->recordActivity('incompleted_task');
    }

    public function activity()
    {
        return $this->morphMany(Activity::class, 'subject')->latest();
    }

    public function recordActivity($description)
    {
        /* NOTE refactoring activity feature to accossiation */
        $this->activity()->create([
            'project_id' => $this->project_id,
            'description' => $description,
        ]);
    }
}
