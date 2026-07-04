<?php

namespace App\Observers;

use App\Interfaces\Auditable;
use Illuminate\Support\Facades\Auth;

class AuditoriaObserver
{
    public function creating(Auditable $model): void
    {
        if (!Auth::check()) {
            return;
        }

        $column = $model->getUserCreateIdColumn();
        $model->{$column} = Auth::id();
    }

    public function updating(Auditable $model): void
    {
        if (!Auth::check()) {
            return;
        }

        $column = $model->getUserUpdateIdColumn();
        $model->{$column} = Auth::id();
    }

    public function deleting(Auditable $model): void
    {
        $column = $model->getUserDeleteIdColumn();

        if ($column === null || !Auth::check()) {
            return;
        }

        $model->{$column} = Auth::id();
    }
}
