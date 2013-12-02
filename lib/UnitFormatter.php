<?php

/**
 * Class for various unit formatting.
 * @author Justin Duplessis <drfoliberg@gmail.com>
 * @license http://www.gnu.org/licenses/gpl-2.0.txt
 */
class UnitFormatter {

    /**
     * A function to format a certain amount of bytes.
     * 
     * @param long $bytes The raw amount of bytes to format
     * @param int $precision The number of decimals to keep
     * @param string $lang "fr" for french "en" for english
     * @param boolean $si The unit multiple see http://en.wikipedia.org/wiki/Octet_%28computing%29#Unit_multiples
     * @return string The formatted string with the unit
     */
    public static function formatBytes($bytes, $precision = 2, $lang = "en", $si = FALSE) {

        $multiples = array('', 'K', 'M', 'G', 'T', 'P', 'E', 'Z', 'Y');
        $prefixes = array();

        if ($lang == "fr") {
            $unit = "o";
        } elseif ($lang == "en") {
            $unit = "B";
        }

        if ($si === FALSE) {
            //Binary prefix
            foreach ($multiples as $multiple) {
                $prefixes[] = $multiple . "i" . $unit;
            }
        } else {
            //SI prefix
            foreach ($multiples as $multiple) {
                $prefixes[] = $multiple . $unit;
            }
        }

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($prefixes) - 1);
        if ($si === FALSE) {
            $bytes /= pow(1024, $pow);
        } else {
            $bytes /= pow(1000, $pow);
        }

        return round($bytes, $precision) . ' ' . $prefixes[$pow];
    }

}
