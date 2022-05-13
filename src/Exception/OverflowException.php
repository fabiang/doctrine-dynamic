<?php

declare(strict_types=1);

namespace Fabiang\DoctrineDynamic\Exception;

use OverflowException as BaseOverflowException;

class OverflowException extends BaseOverflowException implements ExceptionInterface
{
}
