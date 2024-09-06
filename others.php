<?php
// I'm not touching this file since from Readme
// it seems I don't have to do anything with this file, but I have complains here

namespace Operations\Notification;


/**
 * @property Seller $Seller
 */
class Contractor
{
    /**
     * I dont understand whi we need this const 
    */
    const TYPE_CUSTOMER = 0;
    public $id;
    public $type;
    public $name;

    public static function getById(int $resellerId): self
    {
        return new self($resellerId); // fakes the getById method
    }

    /**
     * @param array<string, mixed> $criteria
     * 
     * @return string
     */
    public static function findOne(array $criteria): self
    {
        return new self($criteria['id']);
    }

    public function getFullName(): string
    {
        return $this->name . ' ' . $this->id;
    }
}

Enum ContractorTypeEnum: string
{
    case Seller = 1;
    case Employee = 2;
    case Creator = 3;
    case Expert = 4;
}


class Seller extends Contractor
{
}

class Employee extends Contractor
{
}

class Status
{
    public $id, $name;

    public static function getName(int $id): string
    {
        $a = [
            0 => 'Completed',
            1 => 'Pending',
            2 => 'Rejected',
        ];

        return $a[$id];
    }
}

abstract class ReferencesOperation
{   

    abstract public function doOperation(): array;

    /**
     * we will not use this method instend we will use Request class 
     * let consider this method deleted
     */
    public function getRequest($pName)
    {
        return $_REQUEST[$pName];
    }

}

final class Request 
{
    public function get(string | null $key = null): mixed 
    {
        if(!$key)
        {
            return $_REQUEST;
        }

        return $_REQUEST[$key] ?? null;
    }
}

function getResellerEmailFrom()
{
    return 'contractor@example.com';
}

function getEmailsByPermit($resellerId, $event)
{
    // fakes the method
    return ['someemeil@example.com', 'someemeil2@example.com'];
}

class NotificationEvents
{
    public const CHANGE_RETURN_STATUS = 'changeReturnStatus';
    public const NEW_RETURN_STATUS    = 'newReturnStatus';
}

class NotificationManager
{
    public static function send(
        $resellerId,
        $clientid,
        $event,
        $notificationSubEvent,
        $templateData,
        &$errorText,
        $locale = null
    ) {
        // fakes the method
        return true;
    }
}

class MessagesClient
{
    static function sendMessage(
        $sendMessages,
        $resellerId = 0,
        $customerId = 0,
        $notificationEvent = 0,
        $notificationSubEvent = ''
    ) {
        return ''; // I'm not sure if this is the right value to return from here this should return boolean
    }
}

function __($template, array $data, int $resellerId): string
{
    return ''; // fakes function
}