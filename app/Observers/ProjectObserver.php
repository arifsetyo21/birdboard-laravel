<?php

namespace App\Observers;

use App\project;

class ProjectObserver
{


    /**
     * Handle the project "created" event.
     *
     * @param  \App\project  $project
     * @return void
     */
    public function created(project $project)
    {
        $this->recordActivity('created', $project);
    }

    /**
     * Handle the project "updated" event.
     *
     * @param  \App\project  $project
     * @return void
     */
    public function updated(project $project)
    {
        $this->recordActivity('updated', $project);
    }

    protected function recordActivity($type, $project)
    {
        \App\Activity::create([
            'project_id' => $project->id,
            'description' => $type,
        ]);
    }
}
