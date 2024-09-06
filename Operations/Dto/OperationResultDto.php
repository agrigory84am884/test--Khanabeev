<?php

namespace Operations\Dto;

class OperationResultDto
{
    private bool $notificationEmployeeByEmail = true;
    private bool $notificationClientByEmail = true;
    private bool $notificationClientBySmsIsSent = true;

    public function __construct()
    {
    }

    public function getResult(): array
    {
        return [
            'notificationEmployeeByEmail' => $this->notificationEmployeeByEmail,
            'notificationClientByEmail' => $this->notificationClientByEmail,
            'notificationClientBySms' => $this->notificationClientBySmsIsSent,
        ];
    }

    public function setNotificationEmployeeByEmail(bool $notificationEmployeeByEmail): void
    {
        $this->notificationEmployeeByEmail = $notificationEmployeeByEmail;
    }

    public function setNotificationClientByEmail(bool $notificationClientByEmail): void
    {
        $this->notificationClientByEmail = $notificationClientByEmail;
    }

    public function setNotificationClientBySmsIsSent(bool $notificationClientBySmsIsSent): void
    {
        $this->notificationClientBySmsIsSent = $notificationClientBySmsIsSent;
    }
}