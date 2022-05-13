<?php

declare(strict_types=1);

namespace Fabiang\DoctrineDynamic\Exception;

use DomainException as BaseDomainException;

class DomainException extends BaseDomainException implements ExceptionInterface
{
}
