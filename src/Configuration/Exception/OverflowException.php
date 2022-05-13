<?php

declare(strict_types=1);

namespace Fabiang\DoctrineDynamic\Configuration\Exception;

use Fabiang\DoctrineDynamic\Exception\OverflowException as BaseOverflowException;

class OverflowException extends BaseOverflowException implements ExceptionInterface
{
}
