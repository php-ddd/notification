Notification [![Build Status](https://travis-ci.org/php-ddd/notification.svg)](https://travis-ci.org/php-ddd/command)[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/php-ddd/notification/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/php-ddd/notification/?branch=master)[![Code Coverage](https://scrutinizer-ci.com/g/php-ddd/notification/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/php-ddd/notification/?branch=master)
============

PHP 5.3+ library to handle to replace throwing exceptions with notification in validations.

> "If you're validating some data, you usually shouldn't be using exceptions to signal validation failures." -- [Martin Fowler](http://martinfowler.com/articles/replaceThrowWithNotification.html)

Installation
------------

This is installable via [Composer](https://getcomposer.org/) as [php-ddd/notification](https://packagist.org/packages/php-ddd/notification):

    $ composer require php-ddd/notification

Usage
-----

Suppose we have a code like this:
```php

use Exception;

class PurchaseOrder
{
    /**
     * @var Items[]
     */
    private $items = array();
    
    /**
     * @var ShippingInformation
     */
    private $shippingInformation;
    
    /**
     * Check if we can validate the purchase order
     *
     * @throw Exception an Exception containing the reason why we can't validate the Order
     */
    public function canValidate()
    {
        if (empty($this->items)) {
            throw new Exception('There is nothing to purchase.');
        }
        
        foreach($this->items as $item) {
            if ($item->isProductExpires()) {
                throw new Exception(sprintf('The item %s is no longer available.', (string)$item));
            }
        }
        
        if (!$this->shippingInformation->isComplete()) {
            throw new Exception('You should first complete your shipping information');
        }
    }
}
```

We want to avoid using Exception because the goal of this method is to know if we can validate the Purchase order, not
to validate it.  
This kind of method should return a boolean value saying whether we can validate or not.  
But returning a boolean without throwing exception means we loose information about why we can't purchase this order.  
We can having both information by introducing the Notification pattern.

```php

use PhpDDD\Notification;

class PurchaseOrder
{
    /**
     * @var Items[]
     */
    private $items = array();
    
    /**
     * @var ShippingInformation
     */
    private $shippingInformation;
    
    /**
     * Check if we can validate the purchase order
     *
     * @param Notification $notification
     *
     * @return bool whether we can validate this order or not
     */
    public function canValidate(Notification $notification)
    {
        if (empty($this->items)) {
            $notification->addError('There is nothing to purchase.');
        }
        
        foreach($this->items as $item) {
            if ($item->isProductExpires()) {
                $notification->addError(sprintf('The item %s is no longer available.', (string)$item));
            }
        }
        
        if (!$this->shippingInformation->isComplete()) {
            $notification->addError('You should first complete your shipping information');
        }
        
        return $notification->hasError();
    }
}

// Elsewhere
use Exception;

$order = new PurchaseOrder();
// ...
$notification = new Notification();
if (!$order->canValidate($notification)) {
    throw new Exception($notification->firstMessage());
}

```

Bear in mind that this changes the behavior of your code. You should read [Martin Fowler's blog article](http://martinfowler.com/articles/replaceThrowWithNotification.html) to see how you can do this without breaking your code.

Sometimes, you don't want to know why we cannot validate. So, creating the notification object is irrelevant for your context.  
In this case, simply update your code like so:


```php
use PhpDDD\Notification;

class PurchaseOrder
{
    /**
     * @var Items[]
     */
    private $items = array();
    
    /**
     * @var ShippingInformation
     */
    private $shippingInformation;
    
    /**
     * Check if we can validate the purchase order
     *
     * @param Notification|null $notification
     *
     * @return bool whether we can validate this order or not
     */
    public function canValidate(Notification $notification = null)
    {
        if (null === $notification) {
            $notification = new Notification();
        }
        
        if (empty($this->items)) {
            $notification->addError('There is nothing to purchase.');
        }
        
        foreach($this->items as $item) {
            if ($item->isProductExpires()) {
                $notification->addError(sprintf('The item %s is no longer available.', (string)$item));
            }
        }
        
        if (!$this->shippingInformation->isComplete()) {
            $notification->addError('You should first complete your shipping information');
        }
        
        return $notification->hasError();
    }
}

// Elsewhere
$order = new PurchaseOrder();
// ...
if (!$order->canValidate()) {
    return;
}

```
