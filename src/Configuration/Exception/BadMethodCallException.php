<?php

declare(strict_types=1);

namespace Fabiang\DoctrineDynamic\Configuration\Exception;

use Fabiang\DoctrineDynamic\Exception\BadMethodCallException as BaseBadMethodCallException;

class BadMethodCallException extends BaseBadMethodCallException implements ExceptionInterface
{
}
