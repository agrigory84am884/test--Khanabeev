<?php

namespace Operations\Notification;

use Operations\Dto\OperationDto;
use Operations\Factory\ReturnEventFactory;

class ReturnOperation extends ReferencesOperation
{
    public function __construct(
        private Request $request
    )
    {
    }

    /**
     * @throws \Exception
    */
    public function doOperation(): array
    {
        $operationDto = OperationDto::createFromRequestData((array)($this->request->get() ?? []));
        $returnEvent = (new ReturnEventFactory($operationDto))->createEvent();

        return $returnEvent->do()->getResult();
    }

}