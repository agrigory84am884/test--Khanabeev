<?php

namespace Operations\Notification\Sanders;

use Operations\Notification\MessagesClient;
use Operations\Notification\NotificationEvents;
use Operations\Notification\ReturnEvent;
use Operations\Notification\NotificationTypes;
use SplSubject;
use function Operations\Notification\getResellerEmailFrom;
use function Operations\Notification\__;

class ClientEmailSender implements \SplObserver
{

    /**
     * @param SplSubject|ReturnEvent $subject
     * @return void
     */
    public function update(SplSubject $subject): void
    {
        $emailFrom = getResellerEmailFrom($subject->getOperationDto()->getResellerId());

        if (
            $subject->getOperationDto()->getNotificationType() !== ReturnEvent::TYPE_CHANGE ||
            !$subject->getOperationDto()->getDifferencesTo()
            || empty($emailFrom)
            || !$subject->getClient()->email
        ) {
            $subject->getResultDto()->setNotificationClientByEmail(false);
            return;
        }

        $res = MessagesClient::sendMessage([
            [
                'type' => NotificationTypes::EMAIL,
                'emailFrom' => $emailFrom,
                'emailTo' => $subject->getClient()->email,
                'subject' => __('complaintClientEmailSubject', $subject->getTemplateData(), $subject->getReseller()->id),
                'message' => __('complaintClientEmailBody', $subject->getTemplateData(), $subject->getReseller()->id),
            ],
        ],
            $subject->getReseller()->id,
            $subject->getClient()->id,
            NotificationEvents::CHANGE_RETURN_STATUS,
            $subject->getOperationDto()->getDifferencesTo()
        );


        $subject->getResultDto()->setNotificationClientByEmail($res);
    }
}