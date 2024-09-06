<?php

namespace Operations\Notification;

use Operations\Dto\OperationDto;
use Operations\Dto\OperationResultDto;
use Operations\Exceptions\BadRequestException;
use SplObjectStorage;
use SplObserver;
use SplSubject;

class ReturnEvent implements SplSubject
{
    public const TYPE_NEW    = 1;
    public const TYPE_CHANGE = 2;

    private SplObjectStorage $observers;
    private array $templateData = [];
    private OperationResultDto $resultDto;

    public function __construct(
        private OperationDto $operationDto,
        private Seller $reseller,
        private Contractor $client,
        private Employee $expert,
        private Employee $creator
    ) {
        $this->observers = new SplObjectStorage();
        $this->operationDto = $operationDto;
        $this->reseller = $reseller;
        $this->client = $client;
        $this->expert = $expert;
        $this->creator = $creator;
        $this->resultDto = new OperationResultDto();
    }

    public function attach(SplObserver $observer): void
    {
        $this->observers->attach($observer);
    }

    public function detach(SplObserver $observer): void
    {
        $this->observers->detach($observer);
    }

    public function notify(): void
    {
        foreach ($this->observers as $observer) {
            $observer->update($this);
        }
    }

    public function do(): OperationResultDto
    {
        $templateData = [
            'COMPLAINT_ID'       => $this->operationDto->getComplaintId(),
            'COMPLAINT_NUMBER'   => $this->operationDto->getComplaintNumber(),
            'CREATOR_ID'         => $this->operationDto->getCreatorId(),
            'CREATOR_NAME'       => $this->creator->getFullName(),
            'EXPERT_ID'          => $this->operationDto->getexpertId(),
            'EXPERT_NAME'        => $this->expert->getFullName(),
            'CLIENT_ID'          => $this->operationDto->getclientId(),
            'CLIENT_NAME'        => $this->client->getFullName(),
            'CONSUMPTION_ID'     => $this->operationDto->getConsumptionId(),
            'CONSUMPTION_NUMBER' => $this->operationDto->getConsumptionNumber(),
            'AGREEMENT_NUMBER'   => $this->operationDto->getAgreementNumber(),
            'DATE'               => $this->operationDto->getDate(),
            'DIFFERENCES'        => $this->getDifferences(),
        ];

        //  If even one variable for the template is not set, do not send notifications.
        foreach ($templateData as $key => $tempData) {
            if (empty($tempData)) {
                throw new BadRequestException("Template Data ({$key}) is empty!");
            }
        }

        $this->templateData = $templateData;
        $this->notify();

        return $this->resultDto;
    }

    public function getReseller(): Seller
    {
        return $this->reseller;
    }

    /**
     * @return mixed
     */
    public function getClient(): Contractor
    {
        return $this->client;
    }

    /**
     * @return mixed
     */
    public function getTemplateData(): array
    {
        return $this->templateData;
    }

    public function getDifferences(): string
    {
        $differences = '';
        if ($this->operationDto->getNotificationType() === self::TYPE_NEW) {
            $differences = __('NewPositionAdded', [], $this->operationDto->getResellerId());
        } elseif (
            $this->operationDto->getNotificationType() === self::TYPE_CHANGE
            && $this->operationDto->getDifferencesFrom()
            && $this->operationDto->getDifferencesTo()
        ) {
            $differences = __('PositionStatusHasChanged', [
                'FROM' => Status::getName($this->operationDto->getDifferencesFrom()),
                'TO'   => Status::getName($this->operationDto->getDifferencesTo()),
            ], $this->operationDto->getResellerId());
        }

        return $differences;
    }

    public function getOperationDto(): OperationDto
    {
        return $this->operationDto;
    }

    public function getResultDto(): OperationResultDto
    {
        return $this->resultDto;
    }
}