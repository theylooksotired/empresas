<?php
/**
* @class Adsense
*
* This class is used to render adsense blocks.
*
* @author Leano Martinet <info@asterion-cms.com>
* @package Asterion
* @version 3.0.1
*/
class Adsense {

    static public function responsive($type = '') {
        $typesArgentina = ['top' => '3164319923', 'middle' => '8408907743', 'side' => '8433776711'];
        $typesChile = ['top' => '6565520647', 'middle' => '7120695044', 'side' => '5846323587'];
        $typesBolivia = ['top' => '4469662733', 'middle' => '5934965252', 'side' => '9635177741'];
        $typesColombia = ['top' => '3308801916', 'middle' => '9530417729', 'side' => '3181450036'];
        $typesEcuador = ['top' => '9682638579', 'middle' => '1261223079', 'side' => '3220160245'];
        $typesGuatemala = ['top' => '7635059733', 'middle' => '4581343377', 'side' => '3117230221'];
        $typesHonduras = ['top' => '5060867289', 'middle' => '9593996907', 'side' => '3268261708'];
        $typesMexico = ['top' => '4285829902', 'middle' => '8280915236', 'side' => '2972748236'];
        $typesPanama = ['top' => '1659666563', 'middle' => '6864903545', 'side' => '3695814729'];
        $typesParaguay = ['top' => '5683683815', 'middle' => '1536377632', 'side' => '4370602148'];
        $typesPeru = ['top' => '3057520477', 'middle' => '9247346690', 'side' => '5272620279'];
        $typesDominicana = ['top' => '1744438806', 'middle' => '4250170883', 'side' => '6805193795'];
        $typesUruguay = ['top' => '7934265023', 'middle' => '4179030455', 'side' => '2865948781'];
        $typesVenezuela = ['top' => '1333375264', 'middle' => '9239785441', 'side' => '3201631339'];
        
        $adSlotArgentina = (isset($typesArgentina[$type])) ? $typesArgentina[$type] : '2090660201';
        $adSlotChile = (isset($typesChile[$type])) ? $typesChile[$type] : '2090660201';
        $adSlotBolivia = (isset($typesBolivia[$type])) ? $typesBolivia[$type] : '2090660201';
        $adSlotColombia = (isset($typesColombia[$type])) ? $typesColombia[$type] : '2090660201';
        $adSlotEcuador = (isset($typesEcuador[$type])) ? $typesEcuador[$type] : '2090660201';
        $adSlotGuatemala = (isset($typesGuatemala[$type])) ? $typesGuatemala[$type] : '2090660201';
        $adSlotHonduras = (isset($typesHonduras[$type])) ? $typesHonduras[$type] : '2090660201';
        $adSlotMexico = (isset($typesMexico[$type])) ? $typesMexico[$type] : '2090660201';
        $adSlotPanama = (isset($typesPanama[$type])) ? $typesPanama[$type] : '2090660201';
        $adSlotParaguay = (isset($typesParaguay[$type])) ? $typesParaguay[$type] : '2090660201';
        $adSlotPeru = (isset($typesPeru[$type])) ? $typesPeru[$type] : '2090660201';
        $adSlotDominicana = (isset($typesDominicana[$type])) ? $typesDominicana[$type] : '2090660201';
        $adSlotUruguay = (isset($typesUruguay[$type])) ? $typesUruguay[$type] : '2090660201';
        $adSlotVenezuela = (isset($typesVenezuela[$type])) ? $typesVenezuela[$type] : '2090660201';

        if (Parameter::code('site_code') == 'argentina') {
            $adSlot = $adSlotArgentina;
        } elseif (Parameter::code('site_code') == 'chile') {
            $adSlot = $adSlotChile;
        } elseif (Parameter::code('site_code') == 'bolivia') {
            $adSlot = $adSlotBolivia;
        } elseif (Parameter::code('site_code') == 'colombia') {
            $adSlot = $adSlotColombia;
        } elseif (Parameter::code('site_code') == 'ecuador') {
            $adSlot = $adSlotEcuador;
        } elseif (Parameter::code('site_code') == 'guatemala') {
            $adSlot = $adSlotGuatemala;
        } elseif (Parameter::code('site_code') == 'honduras') {
            $adSlot = $adSlotHonduras;
        } elseif (Parameter::code('site_code') == 'mexico') {
            $adSlot = $adSlotMexico;
        } elseif (Parameter::code('site_code') == 'panama') {
            $adSlot = $adSlotPanama;
        } elseif (Parameter::code('site_code') == 'paraguay') {
            $adSlot = $adSlotParaguay;
        } elseif (Parameter::code('site_code') == 'peru') {
            $adSlot = $adSlotPeru;
        } elseif (Parameter::code('site_code') == 'republica-dominicana') {
            $adSlot = $adSlotDominicana;
        } elseif (Parameter::code('site_code') == 'uruguay') {
            $adSlot = $adSlotUruguay;
        } elseif (Parameter::code('site_code') == 'venezuela') {
            $adSlot = $adSlotVenezuela;
        } else {
            $adSlot = '2090660201';
        }
        if (ASTERION_DEBUG) return '<div class="adsense adsenseInline adsenseTest">Ad - ' . (($type!='') ? $type : 'default') . ' - ' . $adSlot . '</div>';
        return '
            <div class="adsense">
                <ins class="adsbygoogle"
                    style="display:block"
                    data-ad-client="ca-pub-7429223453905389"
                    data-ad-slot="' . $adSlot . '"
                    data-ad-format="auto"
                    data-full-width-responsive="true"></ins>
                <script>
                    (adsbygoogle = window.adsbygoogle || []).push({});
                </script>
            </div>';
    }

}
?>