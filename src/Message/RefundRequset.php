<?php

namespace Omnipay\Inecobank\Message;

use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Common\Message\ResponseInterface;

/**
 * Refund Request
 * @method Response send()
 */
class RefundRequest extends AbstractRequest
{

    /**
     * Gateway payment Url
     * @var string
     */
    protected $refundUrl = 'https://ipay.arca.am/payment/rest/refund.do';
    protected $refundTestUrl = 'https://ipaytest.arca.am:8445/payment/rest/refund.do';

    /**
     * Get payment Url
     * @return string
     */
    public function getRefundUrl()
    {
        return $this->getTestMode() ? $this->refundTestUrl : $this->refundUrl;
    }

    /**
     * Sets the request client ID.
     *
     * @param string $value
     *
     * @return $this
     */
    public function setClientId($value)
    {
        return $this->setParameter('clientId', $value);
    }

    /**
     * Get the request client ID.
     * @return $this
     */
    public function getClientId()
    {
        return $this->getParameter('clientId');
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
     * @return array|mixed
     */
    public function getData()
    {
        $this->validate('username', 'password');

        return [
            'orderId'       => $this->getTransactionId(),
            'userName'      => $this->getUsername(),
            'password'      => $this->getPassword(),
            'Description'   => $this->getDescription(),
            'PaymentAmount' => $this->getAmount()
        ];
    }

    /**
     * @param $data
     *
     * @return \Omnipay\Inecobank\Message\RefundResponse
     */
    public function sendData($data)
    {
        $args['paymentfields'] = $data;

        /** Guzzel */
        $client = new Client([
            'timeout' => 2.0,
        ]);

        /** GET Request For Refund */
        $resp = $client->request('GET', $this->getRefundUrl(), [
            'query' => [
                'password'    => $credentials['password'],
                'userName'    => $credentials['username'],
                'orderId'     => $request->input('orderId'),
                'description' => $request->input('amount')
            ]
        ]);

        $response = $resp->getBody()->getContents();

        return new RefundResponse($this, $response);
    }
}
