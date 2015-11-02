<?php

namespace Omnipay\PayUZa\Message;

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
        $data['AdditionalInformation']['supportedPaymentMethods'] = 'CREDITCARD';

        $data['Basket']['description'] = $this->getDescription();
        $data['Basket']['amountInCents'] = $this->getAmount() * 100;
        $data['Basket']['currencyCode'] = 'ZAR';

        if ($this->getCard()) {
            $data['Customer']['merchantUserId'] = "7";
            $data['Customer']['email'] = $this->getCard()->getEmail();
            $data['Customer']['firstName'] = $this->getCard()->getFirstName();
            $data['Customer']['lastName'] = $this->getCard()->getLastName();
            $data['Customer']['mobile'] = '0211234567';
            $data['Customer']['regionalId'] = '1234512345122';
            $data['Customer']['countryCode'] = '27';
        }

        return $data;
    }

    public function sendData($data)
    {
        try {
            $result = $this->setTransaction($data);
        } catch (\SoapFault $e) {
            dd($this->client->__getLastRequest());
        } finally {
            return $this->response = new PurchaseResponse($this, $result);
        }
    }
}
