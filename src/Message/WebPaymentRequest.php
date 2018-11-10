<?php

namespace Omnipay\Square\Message;

use Omnipay\Common\Message\AbstractRequest;
use SquareConnect;

/**
 * Square Purchase Request
 */
class WebPaymentRequest extends AbstractRequest
{
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
     * @return SquareConnect\Model\CreateCheckoutRequest
     */
    public function getData()
    {
        $line_items = [];

        foreach ($this->getItems() as $index=>$item) {
            $line_items[$index] = new SquareConnect\Model\OrderLineItem(
                [
                    'name' => $item->getName(),
                    'quantity' => strval($item->getQuantity()),
                    'base_price_money' => new SquareConnect\Model\Money(
                        [
                            'amount' => intval($item->getPrice()*100),
                            'currency' => $this->getCurrency()
                        ]
                    )
                ]
            );
        }

        return new \SquareConnect\Model\CreateCheckoutRequest([
            'idempotency_key' => uniqid(),
            'order' => new SquareConnect\Model\Order(array(
                'reference_id' => $this->getTransactionReference(),
                'line_items' => $line_items
            )),
            'ask_for_shipping_address' => $this->includeShippingAddress(),
            'redirect_url' => $this->getReturnUrl()
        ]);
    }

    /**
     * @param $data
     * @return WebPaymentResponse
     */
    public function sendData($data)
    {
        SquareConnect\Configuration::getDefaultConfiguration()->setAccessToken($this->getAccessToken());

        $api_instance = new SquareConnect\Api\CheckoutApi();

        try {
            $result = $api_instance->createCheckout($this->getLocationId(), $data);
            $result = $result->getCheckout();
            $response = [
                'id' => $result->getId(),
                'checkout_url' => $result->getCheckoutPageUrl()
            ];
            return $this->createResponse($response);
        } catch (Exception $e) {
            echo 'Exception when calling LocationsApi->listLocations: ', $e->getMessage(), PHP_EOL;
        }
    }

    /**
     * @param $response
     * @return WebPaymentResponse
     */
    public function createResponse($response) : WebPaymentResponse
    {
        return $this->response = new WebPaymentResponse($this, $response);
    }
}
