<?php

namespace App\Interfaces;

interface Auditable
{
    public function getUserCreateIdColumn(): string;

    public function getUserUpdateIdColumn(): string;

    public function getUserDeleteIdColumn(): ?string;
}
