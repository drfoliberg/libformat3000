<?php

/**
 * Class for various unit formatting.
 * @author Justin Duplessis <drfoliberg@gmail.com>
 * @license http://www.gnu.org/licenses/gpl-2.0.txt
 */
class UnitFormatter {

    var $precision;
    var $decimal_seperator;
    var $thousand_seperator;
    var $lang;

    /**
     * The constructor initialises parameters which will be used by all the function of this class.
     * 
     * @param int $precision The number of decimal to keep
     * @param string $lang The language (locale) for various units abreviations
     * @param string $decimal_seperator The seperator between the integer and the decimal parts
     * @param string $thousand_seperator The seperator between thousands
     */
    function __construct($precision = 2, $lang = "en", $decimal_seperator = null, $thousand_seperator = "") {

        $this->precision = $precision > 0 ? $precision : 2;
        $this->lang = $lang;
        $this->decimal_seperator = $decimal_seperator;
        $this->thousand_seperator = $thousand_seperator;

        if ($this->decimal_seperator === null) {
            if ($this->lang === "fr") {
                $this->decimal_seperator = ",";
            } else {
                $this->decimal_seperator = ".";
            }
        }
    }

    /**
     * A function to format a certain amount of bytes.
     * 
     * @param long $bytes The raw amount of bytes to format
     * @param int $precision The number of decimals to keep
     * @param boolean $si The unit multiple see http://en.wikipedia.org/wiki/Octet_%28computing%29#Unit_multiples
     * @param string $lang "fr" for french "en" for english
     * @return string The formatted string with the unit
     */
    public function formatBytes($bytes, $precision = null, $si = false, $lang = null) {

        if (!is_numeric($bytes)) {
            return "'$bytes' is not a number!";
        }
        if ($precision === null) {
            $precision = $this->precision;
        } else {
            $precision = $precision > 0 ? $precision : 2;
        }

        if ($lang === null) {
            $lang = $this->lang;
        }

        $multiples = array('', 'K', 'M', 'G', 'T', 'P', 'E', 'Z', 'Y');
        $prefixes = array();

        if ($lang == "fr") {
            $unit = "o";
        } elseif ($lang == "en") {
            $unit = "B";
        }

        if ($si === false) {
            //Binary prefix
            foreach ($multiples as $multiple) {
                if ($multiple !== "") {
                    $prefixes[] = $multiple . "i" . $unit;
                } else {
                    $prefixes[] = $multiple . $unit;
                }
            }
        } else {
            //SI prefix
            foreach ($multiples as $multiple) {
                $prefixes[] = $multiple . $unit;
            }
        }

        $divider = $si ? 1000 : 1024;

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log($divider));
        $pow = min($pow, count($prefixes) - 1);
        $bytes /= pow($divider, $pow);

        $dec = round(floatval($bytes) - intval($bytes), $precision);
        return number_format($bytes, strlen($dec) - 2, $this->decimal_seperator, $this->thousand_seperator) . ' ' . $prefixes[$pow];
    }

}
