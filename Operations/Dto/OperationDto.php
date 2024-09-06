<?php

namespace Operations\Dto;

class OperationDto
{
    private int $resellerId;
    private int $notificationType;
    private int $clientId;
    private int $creatorId;
    private int $expertId;
    private int $differencesFrom;
    private int $differencesTo;
    private int $complaintId;
    private string $complaintNumber;
    private int $consumptionId;
    private string $consumptionNumber;
    private string $agreementNumber;
    private string $date;

    private function __construct()
    {
    }

    public static function createFromRequestData(array $data): self
    {
        $operationDto = new OperationDto();
        $operationDto->setResellerId((int)($data['resellerId'] ?? 0));
        $operationDto->setNotificationType((int)($data['notificationType'] ?? 0));
        $operationDto->setClientId((int)($data['clientId'] ?? 0));
        $operationDto->setCreatorId((int)($data['creatorId'] ?? 0));
        $operationDto->setExpertId((int)($data['expertId'] ?? 0));
        $operationDto->setDifferencesFrom((int)($data['differences']['from'] ?? 0));
        $operationDto->setDifferencesTo((int)($data['differences']['to'] ?? 0));
        $operationDto->setComplaintId((int)($data['complaintId'] ?? 0));
        $operationDto->setComplaintNumber((string)($data['complaintNumber'] ?? ''));
        $operationDto->setConsumptionId((int)($data['consumptionId'] ?? 0));
        $operationDto->setConsumptionNumber((string)($data['consumptionNumber'] ?? ''));
        $operationDto->setAgreementNumber((string)($data['agreementNumber'] ??  ''));
        $operationDto->setDate((string)($data['date'] ?? ''));

        return $operationDto;
    }

    public function getResellerId(): int
    {
        return $this->resellerId;
    }

    private function setResellerId(int $resellerId): void
    {
        $this->resellerId = $resellerId;
    }

    public function getNotificationType(): int
    {
        return $this->notificationType;
    }

    private function setNotificationType(int $notificationType): void
    {
        $this->notificationType = $notificationType;
    }

    public function getClientId(): int
    {
        return $this->clientId;
    }

    private function setClientId(int $clientId): void
    {
        $this->clientId = $clientId;
    }

    public function getCreatorId(): int
    {
        return $this->creatorId;
    }

    private function setCreatorId(int $creatorId): void
    {
        $this->creatorId = $creatorId;
    }

    public function getExpertId(): int
    {
        return $this->expertId;
    }

    private function setExpertId(int $expertId): void
    {
        $this->expertId = $expertId;
    }

    public function getDifferencesFrom(): int
    {
        return $this->differencesFrom;
    }

    private function setDifferencesFrom(int $differencesFrom): void
    {
        $this->differencesFrom = $differencesFrom;
    }

    public function getDifferencesTo(): int
    {
        return $this->differencesTo;
    }

    private function setDifferencesTo(int $differencesTo): void
    {
        $this->differencesTo = $differencesTo;
    }

    public function getComplaintId(): int
    {
        return $this->complaintId;
    }

    private function setComplaintId(int $complaintId): void
    {
        $this->complaintId = $complaintId;
    }

    public function getComplaintNumber(): string
    {
        return $this->complaintNumber;
    }

    private function setComplaintNumber(string $complaintNumber): void
    {
        $this->complaintNumber = $complaintNumber;
    }

    public function getConsumptionId(): int
    {
        return $this->consumptionId;
    }

    private function setConsumptionId(int $consumptionId): void
    {
        $this->consumptionId = $consumptionId;
    }

    public function getConsumptionNumber(): string
    {
        return $this->consumptionNumber;
    }

    private function setConsumptionNumber(string $consumptionNumber): void
    {
        $this->consumptionNumber = $consumptionNumber;
    }

    public function getAgreementNumber(): string
    {
        return $this->agreementNumber;
    }

    private function setAgreementNumber(string $agreementNumber): void
    {
        $this->agreementNumber = $agreementNumber;
    }


    public function getDate(): string
    {
        return $this->date;
    }

    private function setDate(string $date): void
    {
        $this->date = $date;
    }
}