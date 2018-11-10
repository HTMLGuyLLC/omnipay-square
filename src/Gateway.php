<?php

namespace Omnipay\Square;

use Omnipay\Common\AbstractGateway;

/**
 * Square Gateway
 *
 */

class Gateway extends AbstractGateway
{
    /**
     * @var
     */
    public $square;

    //whether or not to include the shipping address fields in the checkout form
    protected $include_shipping_address = false;

    /**
     * @param null|bool $include_it
     * @return bool
     */
    public function includeShippingAddress(?bool $include_it = null)
    {
        //get it
        if( is_null($include_it) )
        {
            return $this->include_shipping_address;
        }

        //set it
        $this->include_shipping_address = $include_it;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Square';
    }

    /**
     * @return array
     */
    public function getDefaultParameters()
    {
        return [
            'accessToken' => '',
            'locationId'  => '',
        ];
    }

    /**
     * @return mixed
     */
    public function getAccessToken()
    {
        return $this->getParameter('accessToken');
    }

    /**
     * @param $value
     * @return mixed
     */
    public function setAccessToken($value)
    {
        return $this->setParameter('accessToken', $value);
    }

    /**
     * @return mixed
     */
    public function getLocationId()
    {
        return $this->getParameter('locationId');
    }

    /**
     * @param $value
     * @return mixed
     */
    public function setLocationId($value)
    {
        return $this->setParameter('locationId', $value);
    }

    /**
     * @param array $parameters
     * @return mixed
     */
    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Square\Message\WebPaymentRequest', $parameters);
    }

    /**
     * @param array $parameters
     * @return mixed
     */
    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Square\Message\TransactionRequest', $parameters);
    }
}
