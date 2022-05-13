<?php

declare(strict_types=1);

namespace Fabiang\DoctrineDynamic\Configuration\Exception;

use Fabiang\DoctrineDynamic\Exception\UnderflowException as BaseUnderflowException;

class UnderflowException extends BaseUnderflowException implements ExceptionInterface
{
}
