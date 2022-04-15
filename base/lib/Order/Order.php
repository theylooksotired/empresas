<?php
/**
 * @class Order
 *
 * This class defines the users in the administration system.
 *
 * @author Leano Martinet <info@asterion-cms.com>
 * @package Asterion
 * @version 3.0.1
 */
class Order extends Db_Object
{

    const STATUS_CREATED = 'created';
    const STATUS_PAID = 'paid';
    const STATUS_FAILED = 'failed';
    const PAYMENT_TYPE_DEPOSIT = 'deposit';
    const PAYMENT_TYPE_PAYPAL = 'paypal';

    public function encodeId()
    {
        return Order::encodeIdSimple($this->id());
    }

    public static function encodeIdSimple($id)
    {
        return md5('plasticwebs_order_' . $id);
    }

    public static function readCoded($code)
    {
        return (new Order)->readFirst(['where' => 'MD5(CONCAT("plasticwebs_order_",id))=:code'], ['code' => $code]);
    }

    public function paypalButton($options = [])
    {
        return PayPal::checkoutButton($this->formatOptionsPaypal($options));
    }

    public function paypalRequest($options = [])
    {
        header('Location: ' . PayPal::checkoutRequestUrl($this->formatOptionsPaypal($options)));
        exit();
    }

    public function formatOptionsPaypal($options = [])
    {
        $returnUrl = (isset($options['returnUrl']) && $options['returnUrl'] != '') ? $options['returnUrl'] : url('paypal/pagado/' . md5('plasticwebs_pagado' . $this->id()));
        $cancelUrl = (isset($options['cancelUrl']) && $options['cancelUrl'] != '') ? $options['cancelUrl'] : url('paypal/anulado/' . md5('plasticwebs_anulado' . $this->id()));
        return [
            'item_name' => 'Pedido - ' . $this->get('name'),
            'item_number' => strtoupper(Parameter::code('site_code')) . '_' . $this->id(),
            'item_amount' => doubleval($this->get('price')),
            'currency_code' => 'USD',
            'cancel_return' => $cancelUrl,
            'return' => $returnUrl,
        ];
    }

}
