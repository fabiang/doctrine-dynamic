<?php

declare(strict_types=1);

namespace Fabiang\DoctrineDynamic\Exception;

use UnderflowException as BaseUnderflowException;

class UnderflowException extends BaseUnderflowException implements ExceptionInterface
{
}
