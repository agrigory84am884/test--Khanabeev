<?php

namespace Operations\Notification\Sanders;

use Operations\Notification\MessagesClient;
use Operations\Notification\NotificationEvents;
use Operations\Notification\ReturnEvent;
use Operations\Notification\NotificationTypes;
use SplSubject;
use function Operations\Notification\getEmailsByPermit;
use function Operations\Notification\getResellerEmailFrom;
use function Operations\Notification\__;

class EmployeesEmailSender implements \SplObserver
{
    private array $messages = [];

    public function __construct()
    {
    }

    /**
     * @param SplSubject|ReturnEvent $subject
     * @return void
     */
    public function update(SplSubject $subject): void
    {
        $emailFrom = getResellerEmailFrom($subject->getReseller()->id);
        // Retrieve employees' email addresses from the settings.
        $emails = getEmailsByPermit($subject->getReseller()->id, 'tsGoodsReturn');
        $messages = [];

        foreach ($emails as $emailTo) {
            $messages[] = [
                'type' => NotificationTypes::EMAIL,
                'emailFrom' => $emailFrom,
                'emailTo'   => $emailTo,
                'subject'   => __('complaintEmployeeEmailSubject', $subject->getTemplateData(), $subject->getReseller()->id),
                'message'   => __('complaintEmployeeEmailBody', $subject->getTemplateData(), $subject->getReseller()->id),
            ];
        }

        if (!$messages) {
            $subject->getResultDto()->setNotificationEmployeeByEmail(false);
            return;
        }

        $res = MessagesClient::sendMessage(
            $messages,
            $subject->getReseller()->id,
            $subject->getClient()->id,
            NotificationEvents::CHANGE_RETURN_STATUS,
        );

        $subject->getResultDto()->setNotificationEmployeeByEmail($res);
    }
}