<?php

namespace Operations\Factory;

use Operations\Dto\OperationDto;
use Operations\Exceptions\BadRequestException;
use Operations\Exceptions\NotFoundException;
use Operations\Notification\Contractor;
use Operations\Notification\Seller;
use Operations\Notification\Employee;
use Operations\Notification\ReturnEvent;
use Operations\Notification\Sanders\ClientEmailSender;
use Operations\Notification\Sanders\ClientSmsSender;
use Operations\Notification\Sanders\EmployeesEmailSender;

final class ReturnEventFactory
{
    public function __construct(
        private OperationDto $operationDto
    ) {}

    public function createEvent(): ReturnEvent
    {
        $this->validateDto();

        $reseller = $this->getReseller($this->operationDto->getResellerId());
        $client = $this->getClient($this->operationDto->getClientId(), $this->operationDto->getResellerId());
        $creator = $this->getEmployee($this->operationDto->getCreatorId(), 'Creator');
        $expert = $this->getEmployee($this->operationDto->getExpertId(), 'Expert');

        $statusChangeEvent = new ReturnEvent($$this->operationDto, $reseller, $client, $creator, $expert);
        $statusChangeEvent->attach(new EmployeesEmailSender());
        $statusChangeEvent->attach(new ClientEmailSender());
        $statusChangeEvent->attach(new ClientSmsSender());

        return $statusChangeEvent;
    }

    /**
     * Validate the operation data
     * 
     * @throws BadRequestException
    */
    private function validateDto(): void 
    {
        if (!$this->operationDto->getResellerId()) {
            throw new BadRequestException('Empty resellerId');
        }

        if (!$this->operationDto->getClientId()) {
            throw new BadRequestException('Empty clientId');
        }

        if (!$this->operationDto->getCreatorId()) {
            throw new BadRequestException('Empty creatorId');
        }

        if (!$this->operationDto->getExpertId()) {
            throw new BadRequestException('Empty expertId');
        }

        if (!$this->operationDto->getNotificationType()) {
            throw new BadRequestException('Empty notificationType');
        }

    }

    /**
     * Get Reseller data
     * 
     * @param int $resellerId
     * @return Seller
     * @throws NotFoundException
     */
    private function getReseller(int $resellerId): Seller
    {
        $reseller = Seller::getById($resellerId);
        if (empty($reseller->id)) {
            throw new NotFoundException('Seller not found!');
        }
        return $reseller;
    }

    /**
     * Get Client data
     * 
     * @param int $clientId
     * @param int $resellerId
     * @return Contractor
     * @throws NotFoundException
     */
    private function getClient(int $clientId, int $resellerId): Contractor
    {
        $client = Contractor::getById($clientId);
        if (empty($client->id) || $client->type !== Contractor::TYPE_CUSTOMER || $client->Seller->id !== $resellerId) {
            throw new NotFoundException('Client not found!');
        }
        return $client;
    }

    /**
     * Get Employee data
     * 
     * @param int $employeeId
     * @param string $role
     * @return Employee
     * @throws NotFoundException
     */
    private function getEmployee(int $employeeId, string $role): Employee
    {
        $employee = Employee::getById($employeeId);
        if (empty($employee->id)) {
            throw new NotFoundException("{$role} not found!");
        }
 
        return $employee;
    }
}