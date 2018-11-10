<?php

namespace Omnipay\Square\Message;

use Omnipay\Common\Message\AbstractRequest;
use SquareConnect;

/**
 * Square Purchase Request
 */
class TransactionRequest extends AbstractRequest
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
     * @return mixed
     */
    public function getCheckoutId()
    {
        return $this->getParameter('checkoutId');
    }

    /**
     * @param $value
     * @return mixed
     */
    public function setCheckoutId($value)
    {
        return $this->setParameter('ReceiptId', $value);
    }

    /**
     * @return mixed
     */
    public function getTransactionId()
    {
        return $this->getParameter('transactionId');
    }

    /**
     * @param $value
     * @return mixed
     */
    public function setTransactionId($value)
    {
        return $this->setParameter('transactionId', $value);
    }

    /**
     * @return array
     */
    public function getData()
    {
        return [
            'checkoutId'=>$this->getCheckoutId(),
            'transactionId'=>$this->getTransactionId()
        ];
    }

    /**
     * @param $data
     * @return TransactionResponse
     */
    public function sendData($data)
    {
        SquareConnect\Configuration::getDefaultConfiguration()->setAccessToken($this->getAccessToken());

        $api_instance = new SquareConnect\Api\TransactionsApi();

        try {
            $result = $api_instance->retrieveTransaction($this->getLocationId(), $data['transactionId']);

            $orders = [];

            $lineItems = $result->getTransaction()->getTenders();
            if(count($lineItems)) {
                foreach ($lineItems as $key => $value) {
                    $orders[] = [
                        'quantity'=>1,
                        'amount'=>$value->getAmountMoney()->getAmount()/100,
                        'currency'=>$value->getAmountMoney()->getCurrency()
                    ];
                }
            }

            if ($error = $result->getErrors()) {
                $response = [
                    'status' => 'error',
                    'code' => $error['code'],
                    'detail' => $error['detail']
                ];
            } else {
                $response = [
                    'status' => 'success',
                    'transactionId' => $result->getTransaction()->getId(),
                    'referenceId' => $result->getTransaction()->getReferenceId(),
                    'orders' => $orders
                ];
            }
            return $this->createResponse($response);
        } catch (Exception $e) {
            echo 'Exception when calling LocationsApi->listLocations: ', $e->getMessage(), PHP_EOL;
        }
    }

    /**
     * @param $response
     * @return TransactionResponse
     */
    public function createResponse($response)
    {
        return $this->response = new TransactionResponse($this, $response);
    }
}
