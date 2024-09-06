<?php

namespace Operations\Exceptions;

class NotFoundException extends OperationsException
{
    protected $code = 404;
}