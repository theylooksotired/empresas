<?php
class PayPal
{

    public static function checkoutButton($options = [])
    {
        $options = PayPal::formatOptions($options);
        $image = ($options['image'] != 'none') ? '
            <button type="submit" class="button_paypal">
                <span>Paga usando : </span>
                <i class="icon icon-paypal"></i>
            </button>' : '';
        return '
            <form action="https://www.paypal.com/cgi-bin/webscr" method="post" class="paypalForm" id="paypalForm">
                <input type="hidden" name="business" value="ventas@plasticwebs.com">
                <input type="hidden" name="cmd" value="_xclick">
                <input type="hidden" name="item_name" value="' . $options['item_name'] . '">
                <input type="hidden" name="item_number" value="' . $options['item_number'] . '">
                <input type="hidden" name="amount" value="' . $options['item_amount'] . '">
                <input type="hidden" name="currency_code" value="' . $options['currency_code'] . '">
                <input type="hidden" name="cancel_return" value="' . $options['cancel_return'] . '">
                <input type="hidden" name="return" value="' . $options['return'] . '">
                ' . $image . '
            </form>';
    }

    public static function checkoutRequestUrl($options = [])
    {
        return 'https://www.paypal.com/cgi-bin/webscr?' . PayPal::formatOptionsUrl($options);
    }

    public static function checkoutRequest($options = [])
    {
        $options['image'] = 'none';
        $options = PayPal::formatOptions($options);
        return '
            ' . PayPal::checkoutButton($options) . '
            <script>document.getElementById("paypalForm").submit();</script>';
    }

    public static function formatOptions($options = [])
    {
        $options['item_name'] = (isset($options['item_name'])) ? $options['item_name'] : Parms::param('title_page');
        $options['item_number'] = (isset($options['item_number'])) ? $options['item_number'] : '1';
        $options['item_amount'] = (isset($options['item_amount'])) ? $options['item_amount'] : '1.00';
        $options['currency_code'] = (isset($options['currency_code'])) ? $options['currency_code'] : 'USD';
        $options['cancel_return'] = (isset($options['cancel_return'])) ? $options['cancel_return'] : url('');
        $options['return'] = (isset($options['return'])) ? $options['return'] : url('');
        $options['image'] = (isset($options['image'])) ? $options['image'] : 'https://www.paypalobjects.com/en_US/i/btn/btn_buynow_LG.gif';
        return $options;
    }

    public static function formatOptionsUrl($options = [])
    {
        $values = [];
        $values['cmd'] = '_xclick';
        $values['business'] = 'ventas@plasticwebs.com';
        $values['item_name'] = (isset($options['item_name'])) ? $options['item_name'] : Parms::param('title_page');
        $values['item_number'] = (isset($options['item_number'])) ? $options['item_number'] : '1';
        $values['amount'] = (isset($options['item_amount'])) ? $options['item_amount'] : '1.00';
        $values['currency_code'] = (isset($options['currency_code'])) ? $options['currency_code'] : 'USD';
        $values['return'] = (isset($options['return'])) ? $options['return'] : url('');
        $values['cancel_return'] = (isset($options['cancel_return'])) ? $options['cancel_return'] : url('');
        return http_build_query($values);
    }

}
