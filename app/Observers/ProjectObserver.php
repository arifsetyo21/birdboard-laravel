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
        $project->recordActivity('created', $project);
    }

    /**
     * Handle the project "updated" event.
     *
     * @param  \App\project  $project
     * @return void
     */
    public function updated(project $project)
    {
        $project->recordActivity('updated');
    }
}
