<?php

declare(strict_types=1);

namespace App;

interface StateAwareInterface
{
    public function getState(): string;
    public function setState(string $state): void;

}