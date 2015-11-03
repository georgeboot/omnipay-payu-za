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
        if ($this->httpRequest->query->has('PayUReference')) {
            $payUReference = $this->httpRequest->query->get('PayUReference');
        } else {
            $xmlObject = simplexml_load_string(file_get_contents('php://input'));
            if (property_exists($xmlObject, 'PayUReference')) {
                $payUReference = $xmlObject->PayUReference;
            }
        }

        if (!isset($payUReference)) {
            throw new InvalidRequestException('Missing PayUReference parameter in request');
        }

        $data = [];
        $data['Api'] = self::VERSION;
        $data['Safekey'] = $this->getSafekey();
        $data['AdditionalInformation']['payUReference'] = $payUReference;

        return $data;
    }

    public function sendData($data)
    {
        $response = $this->getTransaction($data);
        return $this->response = new CompletePurchaseResponse($this, $response);
    }
}
