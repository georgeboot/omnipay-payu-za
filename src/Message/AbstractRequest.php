<?php

namespace Omnipay\PayUZa\Message;

use Guzzle\Http\ClientInterface;
use SoapClient;
use SoapHeader;
use SoapVar;
use Symfony\Component\HttpFoundation\Request as HttpRequest;
use Omnipay\Common\Message\AbstractRequest as OmniPayAbstractRequest;

/**
 * Authorize.Net Abstract Request
 */
abstract class AbstractRequest extends OmniPayAbstractRequest
{
    const LIVE_ENDPOINT = 'https://www.payu.co.za/';
    const TEST_ENDPOINT = 'https://staging.payu.co.za/';

    const VERSION = 'ONE_ZERO';

    /**
     * @var SoapClient
     */
    protected $client;

    /**
     * Get the security header
     *
     * @return SOAPHeader
     */
    private function getSecurityHeader()
    {
        $headerXml = '<wsse:Security SOAP-ENV:mustUnderstand="1" xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">';
        $headerXml .= '<wsse:UsernameToken wsu:Id="UsernameToken-9" xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd">';
        $headerXml .= '<wsse:Username>'.$this->getUsername().'</wsse:Username>';
        $headerXml .= '<wsse:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordText">'.$this->getPassword().'</wsse:Password>';
        $headerXml .= '</wsse:UsernameToken>';
        $headerXml .= '</wsse:Security>';
        $headerbody = new SoapVar($headerXml, XSD_ANYXML, null, null, null);

        $ns = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd'; //Namespace of the WS.
        $header = new SOAPHeader($ns, 'Security', $headerbody, true);

        return $header;
    }

    public function setTransaction($data)
    {
        $this->setupClient();
        return $this->client->setTransaction($data);
    }

    public function getTransaction($data)
    {
        $this->setupClient();
        return $this->client->getTransaction($data);
    }

    /**
     * @return bool
     */
    private function setupClient()
    {
        if (null === $this->client) {
            $this->client = new SoapClient($this->getEndpoint().'service/PayUAPI?wsdl', ['trace' => true]);
            $this->client->__setSoapHeaders($this->getSecurityHeader());
        }

        return true;
    }

    public function getEndpoint()
    {
        return $this->getTestMode() ? self::TEST_ENDPOINT : self::LIVE_ENDPOINT;
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
}