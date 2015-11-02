<?php

namespace Omnipay\PayUZa;

use Omnipay\Common\AbstractGateway;

/**
 * PayU Gateway
 *
 * Quote: The PayFast engine is basically a "black box" which processes a purchaser's payment.
 *
 * @link https://www.payfast.co.za/s/std/integration-guide
 */
class Gateway extends AbstractGateway
{
    public function getName()
    {
        return 'PayU';
    }

    public function getDefaultParameters()
    {
        return array(
            'username' => '',
            'password' => '',
            'safekey' => '',
            'testMode' => true,
        );
    }

    public function getUsername()
    {
        return $this->getParameter('username');
    }

    public function setUsername($value)
    {
        return $this->setParameter('username', $value);
    }

    public function getPassword()
    {
        return $this->getParameter('password');
    }

    public function setPassword($value)
    {
        return $this->setParameter('password', $value);
    }

    public function getSafekey()
    {
        return $this->getParameter('safekey');
    }

    public function setSafekey($value)
    {
        return $this->setParameter('safekey', $value);
    }

    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PayUZa\Message\PurchaseRequest', $parameters);
    }

    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PayUZa\Message\CompletePurchaseRequest', $parameters);
    }
}
