<?php
/**
 * Created by PhpStorm.
 * User: p17206266
 * Date: 06/01/2020
 * Time: 13:24
 */

namespace VotingSystemsTutorial;
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
     * @param $type_to_check
     * Validates data - compares to Detail types in settings.php
     * @return null
     */
    public function validateDetailTypes($type_to_check){
        $checked_detail_type = null;
        $detail_types = DETAIL_TYPES;

        if (in_array($type_to_check, $detail_types) === true){
            $checked_detail_type = $type_to_check;
        }
        return $checked_detail_type;
    }

    /**
     * @param string $string_to_sanitise
     *
     * filter and sanitises any string data
     * @return string
     */
    public function sanitiseString(string $string_to_sanitise): string{
        $sanitised_string = false;

        if(!empty($string_to_sanitise)){
            $sanitised_string = filter_var($string_to_sanitise, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
        }

        return $sanitised_string;
    }
}