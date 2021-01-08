<?php

namespace Omnipay\Inecobank\Message;

use Omnipay\Common\Message\AbstractRequest;

/**
 * Class PurchaseRequest
 * @package Omnipay\Inecobank\Message
 */
class PurchaseRequest extends AbstractRequest
{

    /**
     * Currency ISO codes.
     * @var array
     */
    protected static $currencyISOCodes = [
        'AMD' => '051',
        'USD' => '840',
        'EUR' => '978',
        'RUB' => '643'
    ];

    /**
     * Sets the request language.
     *
     * @param string $value
     *
     * @return $this
     */
    public function setLanguage($value)
    {
        return $this->setParameter('language', $value);
    }

    /**
     * Get the request language.
     * @return $this
     */
    public function getLanguage()
    {
        return $this->getParameter('language');
    }

    /**
     * Sets the request username.
     *
     * @param string $value
     *
     * @return $this
     */
    public function setUsername($value)
    {
        return $this->setParameter('username', $value);
    }

    /**
     * Get the request username.
     * @return $this
     */
    public function getUsername()
    {
        return $this->getParameter('username');
    }

    /**
     * Sets the request Opaque.
     *
     * @param string $value
     *
     * @return $this
     */
    public function setOpaque($value)
    {
        return $this->setParameter('opaque', $value);
    }

    /**
     * Get the request Opaque.
     * @return $this
     */
    public function getOpaque()
    {
        return $this->getParameter('opaque');
    }

    /**
     * Sets the request TransactionId.
     *
     * @param string $value
     *
     * @return $this
     */
    public function setTransactionId($value)
    {
        return $this->setParameter('orderID', $value);
    }

    /**
     * Get the request TransactionId.
     * @return $this
     */
    public function getTransactionId()
    {
        return $this->getParameter('orderID');
    }

    /**
     * Sets the request password.
     *
     * @param string $value
     *
     * @return $this
     */
    public function setPassword($value)
    {
        return $this->setParameter('password', $value);
    }

    /**
     * Get the request password.
     * @return $this
     */
    public function getPassword()
    {
        return $this->getParameter('password');
    }

    /**
     * Sets the request payment id.
     *
     * @param $value
     *
     * @return $this
     */
    public function setPaymentId($value)
    {
        return $this->setParameter('paymentid', $value);
    }

    /**
     * Get the request payment id.
     * @return $this
     */
    public function getPaymentId()
    {
        return $this->getParameter('paymentid');
    }

    /**
     * get payment Url
     * @return string
     */
    public function getPaymentUrl()
    {
        return $this->getTestMode() ? $this->paymentTestUrl : $this->paymentUrl;
    }

    /**
     * get Card Bindings Url
     * @return mixed
     */
    public function getCardBindingsUrl()
    {
        return $this->getTestMode() ? $this->cardBindingsTestUrl : $this->cardBindingsUrl;
    }

    /**
     * Prepare data to send
     * @return array|mixed
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData()
    {
        $this->validate('username', 'password');

        return [
            'Opaque'      => $this->getOpaque(),
            'returnUrl'   => $this->getReturnUrl(),
            'formUrl'     => $this->getFormUrl(),
            'orderNumber' => $this->getTransactionId(),
            'userName'    => $this->getUsername(),
            'password'    => $this->getPassword(),
            'currency'    => self::$currencyISOCodes[$this->getCurrency()],
            'amount'      => $this->getAmount(),
            'language'    => $this->getLanguage(),
            'testMode'    => $this->getTestMode(),
        ];
    }

    /**
     * Get the request Form URL.
     * @return string
     */
    public function getFormUrl()
    {
        return $this->getParameter('formUrl');
    }

    /**
     * Sets the request Form URL.
     *
     * @param string $value
     *
     * @return $this
     */
    public function setFromUrl($value)
    {
        return $this->setParameter('formUrl', $value);
    }

    /**
     * Send data and return response instance
     *
     * @param $data
     *
     * @return PurchaseResponse
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function sendData($data)
    {
        //        $payment = $this->createPaymentRequest($data);
        //
        //        if (!empty($payment->GetPaymentIDResult->PaymentID) && $payment->GetPaymentIDResult->Respmessage == 'OK') {
        //            $data['PaymentId'] = $payment->GetPaymentIDResult->PaymentID;
        //        }
        //
        //        $data['message'] = $payment->GetPaymentIDResult->Respmessage;
        //        $this->response = new PurchaseResponse($this, $data);
        //        dd($this->response);
        return $this->response = new PurchaseResponse($this, $data);
    }

    /**
     * Create Payment Request Ineco Bank
     * @return mixed
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    protected function createPaymentRequest($data)
    {
        $args['paymentfields'] = $data;

        /*$client = new \SoapClient($this->getPaymentUrl(), [
            'soap_version'    => SOAP_1_1,
            'exceptions'      => true,
            'trace'           => 1,
            'wsdl_local_copy' => true
        ]);*/

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, route('process-ineco-payment'));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,
            "postvar1=value1&postvar2=value2&postvar3=value3");

        // In real life you should use something like:
        // curl_setopt($ch, CURLOPT_POSTFIELDS,
        //          http_build_query(array('postvar1' => 'value1')));

        // Receive server response ...
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec($ch);

        curl_close($ch);

        return $webService = $client->GetPaymentID($args);
    }
}