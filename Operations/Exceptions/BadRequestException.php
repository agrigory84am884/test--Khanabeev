<?php

namespace Operations\Exceptions;

class BadRequestException extends OperationsException
{
    protected $code = 400;
}