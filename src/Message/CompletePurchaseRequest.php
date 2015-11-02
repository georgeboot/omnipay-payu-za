<?php

namespace Omnipay\PayUZa\Message;

use Omnipay\Common\Exception\InvalidRequestException;

/**
 * PayFast Complete Purchase Request
 *
 * We use the same return URL & class to handle both PDT (Payment Data Transfer)
 * and ITN (Instant Transaction Notification).
 */
class CompletePurchaseRequest extends PurchaseRequest
{
    public function getData()
    {
        if ($this->httpRequest->query->get('PayUReference')) {
            $data = [];
            $data['Api'] = self::VERSION;
            $data['Safekey'] = $this->getSafekey();
            $data['AdditionalInformation']['payUReference'] = $this->httpRequest->query->get('PayUReference');

            return $data;
        }

        throw new InvalidRequestException('Missing PayUReference parameter in request');
    }

    public function sendData($data)
    {
        $response = $this->getTransaction($data);
        return $this->response = new CompletePurchaseResponse($this, $response);
    }
}
