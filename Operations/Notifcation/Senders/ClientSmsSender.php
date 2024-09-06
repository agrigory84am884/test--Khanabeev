<?php

namespace Operations\Notification\Sanders;

use Operations\Notification\NotificationEvents;
use Operations\Notification\NotificationManager;
use Operations\Notification\ReturnEvent;
use SplSubject;

class ClientSmsSender implements \SplObserver
{

    /**
     * @param SplSubject|ReturnEvent $subject
     * @return void
     * @throws \Exception
     */
    public function update(SplSubject $subject): void
    {
        if (
            $subject->getOperationDto()->getNotificationType() !== ReturnEvent::TYPE_CHANGE
            || !$subject->getOperationDto()->getDifferencesTo()
            || empty($subject->getClient()->mobile)
        ) {
            $subject->getResultDto()->setNotificationClientBySmsIsSent(false);
            return;
        }

         NotificationManager::send(
            $subject->getReseller()->id,
            $subject->getClient()->id,
            NotificationEvents::CHANGE_RETURN_STATUS,
            $subject->getOperationDto()->getDifferencesTo(),
            $subject->getTemplateData(),
            $error
        );

        if (!empty($error)) { // if error happened we should log it or throw an exception
            $subject->getResultDto()->setNotificationClientBySmsIsSent(false);
        }
    }
}