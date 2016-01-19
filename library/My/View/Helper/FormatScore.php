<?php

/**
 * 
 * view helper pro ziskani skóre v procentech, např. "75 %" z hodnoty "0.7500"
 * Procenta neumí datatables řadit z nějakého důvodu. Tak to bude zatím bez znaku procent.
 *
 */
class My_View_Helper_FormatScore extends Zend_View_Helper_Abstract {

    public function formatScore($score) {
        $formattedScore = "-";

        if (is_numeric($score)) {
            $formattedScore = $score * 100; // . "%"; //já už na to kašlu
            if ($formattedScore < 100 && $formattedScore >= 10) {
                $formattedScore = substr($formattedScore, 0, 2);
            } else if ($formattedScore < 10) {
                $formattedScore = substr($formattedScore, 0, 1);
            }
        }

        return $formattedScore;
    }

}
