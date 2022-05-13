<?php

declare(strict_types=1);

namespace Fabiang\DoctrineDynamic\Configuration\Exception;

use Fabiang\DoctrineDynamic\Exception\RuntimeException as BaseRuntimeException;

class RuntimeException extends BaseRuntimeException implements ExceptionInterface
{
}
