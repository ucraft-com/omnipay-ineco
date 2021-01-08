<?php
namespace Omnipay\Inecobank\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;
/**
 * Class RefundResponse
 * @package Omnipay\Inecobank\Message
 */
class RefundResponse extends AbstractResponse implements RedirectResponseInterface
{

    /**
     * Indicates whether transaction was successful
     * @return bool
     */
    public function isSuccessful()
    {
        return $this->data->ReversePaymentResult->Respcode == '00';
    }

    /**
     * Get Message
     * @return null|string
     */
    public function getMessage()
    {
        return $this->data->ReversePaymentResult->Respmessage;
    }

}