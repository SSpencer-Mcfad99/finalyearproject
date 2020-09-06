<?php

namespace votingSystemTutorial;
/**
 * Validator.php
 *
 * Class is used to validate any user input
 */
class Validator
{

    public function _construct(){}

    public function _destruct(){}

    /**
     * @param string $string_to_sanitise
     *
     * filter and sanitises any string data
     * @return string
     */
    public function sanitiseString(string $string_to_sanitise): string
    {
        $sanitised_string = false;

        if(!empty($string_to_sanitise)){
            $sanitised_string = filter_var($string_to_sanitise, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
        }

        return $sanitised_string;
    }
}