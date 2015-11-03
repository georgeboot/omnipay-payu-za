<?php

namespace Omnipay\PayUZa\Message;
use Omnipay\Common\Exception\InvalidRequestException;

/**
 * PayFast Purchase Request
 */
class PurchaseRequest extends AbstractRequest
{
    public function getData()
    {
        $this->validate('amount', 'description');

        $data['Api'] = self::VERSION;
        $data['Safekey'] = $this->getSafekey();
        $data['TransactionType'] = 'PAYMENT';
        $data['AdditionalInformation']['merchantReference'] = $this->getTransactionId();
        $data['AdditionalInformation']['cancelUrl'] = $this->getCancelUrl();
        $data['AdditionalInformation']['returnUrl'] = $this->getReturnUrl();
        $data['AdditionalInformation']['supportedPaymentMethods'] = implode(',', $this->getParameter('supportedPaymentMethods'));

        if ($this->getNotifyUrl()) {
            $data['AdditionalInformation']['notificationUrl'] = $this->getNotifyUrl();
        }

        $data['Basket']['description'] = $this->getDescription();
        $data['Basket']['amountInCents'] = $this->getAmount() * 100;
        $data['Basket']['currencyCode'] = $this->getCurrency();

        if ($this->getCard()) {
            // $data['Customer']['merchantUserId'] = "7";
            $data['Customer']['email'] = $this->getCard()->getEmail();
            $data['Customer']['firstName'] = $this->getCard()->getFirstName();
            $data['Customer']['lastName'] = $this->getCard()->getLastName();
            $data['Customer']['mobile'] = $this->getCard()->getPhone();
        }

        return $data;
    }

    public function sendData($data)
    {
        try {
            $result = $this->setTransaction($data);
        } catch (\SoapFault $e) {
            throw new InvalidRequestException('Error in API call', 0, $e);
        }

        return $this->response = new PurchaseResponse($this, $result);
    }
}
