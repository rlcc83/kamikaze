<?php

namespace App\Interface;

interface CompatibilityLeadsInterface
{
    public function getCompatibles(array $leads): array;
}
