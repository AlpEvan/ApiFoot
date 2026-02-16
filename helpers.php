<?php

declare(strict_types=1);

/**
 * Escape a value
 *
 * @param mixed $value
 * @return string
 */
function escape(mixed $value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
