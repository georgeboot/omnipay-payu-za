<?php

namespace Omnipay\PayUZa\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * PayFast Purchase Response
 */
class PurchaseResponse extends AbstractResponse implements RedirectResponseInterface
{
    protected $data;
    protected $request;

    public function __construct(RequestInterface $request, \stdClass $data)
    {
        parent::__construct($request, $data);

        $this->data = $data;
        $this->request = $request;

        if (!property_exists($this->data->return, 'payUReference')) {
            throw new InvalidRequestException('payUReference is missing from the API response');
        }
    }

    public function isSuccessful()
    {
        return $this->data->return->successful;
    }

    public function isPending()
    {
        return $this->data->return->successful;
    }

    public function isRedirect()
    {
        return true;
    }

    public function getRedirectUrl()
    {
        return $this->request->getEndpoint().'/rpp.do?PayUReference='.$this->data->return->payUReference;
    }

    public function getRedirectMethod()
    {
        return 'GET';
    }

    public function getRedirectData()
    {
        return [];
    }
}
