<?php

namespace App\Traits;

use App\Models\User;
use App\Observers\AuditoriaObserver;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait Seguimiento
{
    public function getUserCreateIdColumn(): string
    {
        return 'user_create_id';
    }

    public function getUserUpdateIdColumn(): string
    {
        return 'user_update_id';
    }

    public function getUserDeleteIdColumn(): ?string
    {
        return 'user_delete_id';
    }

    public function userCreate(): BelongsTo
    {
        return $this->belongsTo(User::class, $this->getUserCreateIdColumn());
    }

    public function userUpdate(): BelongsTo
    {
        return $this->belongsTo(User::class, $this->getUserUpdateIdColumn());
    }

    public function userDelete(): BelongsTo
    {
        $column = $this->getUserDeleteIdColumn();

        if ($column === null) {
            throw new \RuntimeException(sprintf(
                'The model [%s] does not have a user delete column.',
                static::class
            ));
        }

        return $this->belongsTo(User::class, $column);
    }

    public function creador(): BelongsTo
    {
        return $this->userCreate();
    }

    public function actualizador(): BelongsTo
    {
        return $this->userUpdate();
    }

    public function eliminador(): BelongsTo
    {
        return $this->userDelete();
    }

    protected static function bootSeguimiento(): void
    {
        static::observe(AuditoriaObserver::class);
    }
}
