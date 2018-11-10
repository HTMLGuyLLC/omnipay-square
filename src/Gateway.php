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
