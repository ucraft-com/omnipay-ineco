<?php

namespace Omnipay\Inecobank\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * Class PurchaseResponse
 * @package Omnipay\Inecobank\Message
 */
class PurchaseResponse extends AbstractResponse implements RedirectResponseInterface
{
    /**
     * Gateway $endpoint
     * @var string
     */
    protected $endpoint = 'https://ipay.arca.am/payment/rest/register.do';
    protected $endpointTest = 'https://ipaytest.arca.am:8445/payment/rest/register.do';

    /**
     * Set successful to false, as transaction is not completed yet
     * @return bool
     */
    public function isSuccessful()
    {
        return false;
    }

    /**
     * Mark purchase as redirect type
     * @return bool
     */
    public function isRedirect()
    {
        return true;
    }

    /**
     * Get Message
     * @return null|string
     */
    public function getMessage()
    {
        return $this->data['message'];
    }

    /**
     * Get redirect URL
     * @return string
     */
    public function getRedirectUrl()
    {
        return $this->data['testMode'] ? $this->endpointTest : $this->endpoint;
    }

    /**
     * Get redirect method
     * @return string
     */
    public function getRedirectMethod()
    {
        return 'POST';
    }

    /**
     * Get redirect data
     * @return array|mixed
     */
    public function getRedirectData()
    {
        return $this->data;
    }
}
