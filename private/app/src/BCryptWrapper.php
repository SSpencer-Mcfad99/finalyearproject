<?php
/**
 * Created by PhpStorm.
 * User: p17206266
 * Date: 06/01/2020
 * Time: 13:24
 */

/** Wrapper class made to make inbuilt Bcrypt functions easier
 *
 */
namespace VotingSystemsTutorial;
class BCryptWrapper
{

    public function _construct(){}

    public function _destruct(){}

    public function createHashedPassword($string_to_hash)
    {
        $password_to_hash = $string_to_hash;
        $bcrypt_hashed_password = '';

        if (!empty($password_to_hash)) {
            $options = array('cost' => '11');
            $bcrypt_hashed_password = password_hash($password_to_hash, PASSWORD_DEFAULT, $options);
        }
        return $bcrypt_hashed_password;
    }

public function authenticatePassword($string_to_check, $stored_user_password_hash){
        $user_authenticated = false;
        $current_user_password = $string_to_check;
        $stored_user_password_hash = $stored_user_password_hash;

    if (!empty($current_user_password) && !empty($stored_user_password_hash))
    {
        if (password_verify($current_user_password, $stored_user_password_hash))
        {
            $user_authenticated = true;
        }
    }
    return $user_authenticated;


}
}