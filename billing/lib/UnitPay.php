<?php

class UnitPay
{
    private $event;
    
    public $itemId;
    public $accountId;

    public function __construct(UnitPayEvent $event)
    {
        $this->event = $event;
    }
    
    public function setAccountItem($full_account)
    {
        $pieces = explode("-", $full_account);
        $this->accountId = $pieces[0];// id клиента в таблице users
        $this->itemId   = $pieces[1];// id товара в таблице data
    }

    public function getResult()
    {
        $request = $_GET;

        if (empty($request['method'])
            || empty($request['params'])
            || !is_array($request['params'])
        )
        {
            return $this->getResponseError('Invalid request');
        }

        $method = $request['method'];
        $params = $request['params'];

        if ($params['sign'] != $this->getMd5Sign($params, Config::SECRET_KEY))
        {
            return $this->getResponseError('Incorrect digital signature');
        }

        $unitPayModel = UnitPayModel::getInstance();

        if ($method == 'check')
        {
            if ($unitPayModel->getPaymentByUnitpayId($params['unitpayId']))
            {
                // Платеж уже существует
                return $this->getResponseSuccess('Payment already exists - '.$params['unitpayId']);
            }
            
            // здесь можно разделить $params['account'] на две составляющие: 
            // id - пользователя и id - товара, а потом найти цену этого товара и сверить с той, которая пришла извне 
            $this->setAccountItem($params['account']);
            $msqliObj = $unitPayModel->getPriceById($this->itemId);
            
            if ($msqliObj)
            {
                $price = $msqliObj->price;
                $itemsCount = floor($params['sum'] / $price);
                if ($itemsCount <= 0)
                {
                    return $this->getResponseError('Суммы ' . $params['sum'] . ' руб. не достаточно для оплаты товара ' .
                        'стоимостью ' . $price . ' руб.');
                }
//                return $this->getResponseSuccess('accountId - '.$this->accountId.'; '
//                        . 'itemId - '.$this->itemId.'; price - '.$price.'; $itemsCount - '.$itemsCount);
            }else {
                return $this->getResponseError('Товара с кодом: '.$this->itemId.' не существует!'); 
            }
            /*
            $itemsCount = 1;
            if ($params['sum'] <= 0)
            {
                return $this->getResponseError('Суммы ' . $params['sum'] . ' руб. не достаточно для оплаты товара!');    
            }*/

            if (!$unitPayModel->createPayment(
                $params['unitpayId'],
                $params['account'],
                $params['sum'],
                $itemsCount
            ))
            {
                return $this->getResponseError('Unable to create payment database');
            }
            $params['account'] = $this->accountId; // заменю в параметре account для нормального поиска
            $checkResult = $this->event->check($params);
            if ($checkResult !== true)
            {
                return $this->getResponseError($checkResult);
            }

            return $this->getResponseSuccess('CHECK is successful');
        }

        if ($method == 'pay')
        {
            $payment = $unitPayModel->getPaymentByUnitpayId(
                $params['unitpayId']
            );

            if ($payment && $payment->status == 1)
            {
                return $this->getResponseSuccess('Payment has already been paid');
            }

            if (!$unitPayModel->confirmPaymentByUnitpayId($params['unitpayId']))
            {
                return $this->getResponseError('Unable to confirm payment database');
            }

            $this->event
                ->pay($params);

            return $this->getResponseSuccess('PAY is successful');
        }

	return $this->getResponseError($method.' not supported');
    }

    private function getResponseSuccess($message)
    {
        return json_encode(array(
            "jsonrpc" => "2.0",
            "result" => array(
                "message" => mb_convert_encoding($message, "utf-8", "windows-1251")
            ),
            'id' => 1,
        ));
    }

    private function getResponseError($message)
    {
        return json_encode(array(
            "jsonrpc" => "2.0",
            "error" => array(
                "code" => -32000,
                "message" => mb_convert_encoding($message, "utf-8", "windows-1251")
            ),
            'id' => 1
        ));
    }

    private function getMd5Sign($params, $secretKey)
    {
        ksort($params);
        unset($params['sign']);
        return md5(join(null, $params).$secretKey);
    }
}
