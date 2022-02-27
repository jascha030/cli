<?php

declare(strict_types=1);

namespace Jascha030\CLI\Helpers;

function user(): ?string
{
    return $_SERVER['SUDO_USER'] ?? $_SERVER['USER'];
}
