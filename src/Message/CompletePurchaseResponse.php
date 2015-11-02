<?php

namespace Omnipay\PayUZa\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

/**
 * PayFast Complete Purchase PDT Response
 */
class CompletePurchaseResponse extends AbstractResponse
{
    public function isSuccessful()
    {
        return 'SUCCESSFUL' === $this->data->return->transactionState;
    }

    public function getMessage()
    {
        return $this->data->return->displayMessage;
    }

    public function getData()
    {
        return $this->data->return;
    }
}
