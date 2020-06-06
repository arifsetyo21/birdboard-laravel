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

        static::created(function ($task) {

            $task->project->recordActivity('created_task');
        });

        /* NOTE Has changed with observer */
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

        $this->project->recordActivity('completed_task');
    }

    public function incomplete()
    {
        $this->update(['completed' => false]);

        $this->project->recordActivity('incompleted_task');
    }
}
