<?php

namespace App\Utilities;


class CookieMan
{

	public  static function SetCookie( $name, $value, $inSecond ){
		setcookie($name, $value, time() + $inSecond);
	}

	public static function getCookie($name,$decrypt){

		if(!isset($_COOKIE[$name])) {
		    return;
		} else {

			if ($decrypt === true){
				// get the encrypter service
	            $encrypter = app(\Illuminate\Contracts\Encryption\Encrypter::class);

	            // decrypt
	            $decryptedString = $encrypter->decrypt($_COOKIE[$name]);
			    return $decryptedString;
			}
			else
			{
				return $_COOKIE[$name];
			}
		    
		}

	}

	public static function clearCookie($name){

		if (isset($name)){
			unset($_COOKIE[$name]);
			setcookie($name, null, -1, '/');
		}
	}

}