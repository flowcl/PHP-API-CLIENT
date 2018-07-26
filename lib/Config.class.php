<?php
/**
 * Clase para Configurar el cliente
 * @Filename: Config.class.php
 * @version: 2.0
 * @Author: flow.cl
 * @Email: csepulveda@tuxpan.com
 * @Date: 28-04-2017 11:32
 * @Last Modified by: Carlos Sepulveda
 * @Last Modified time: 28-04-2017 11:32
 */
 
 $COMMERCE_CONFIG = array(
 	"APIKEY" => "1F90971E-8276-4713-97FF-2BLF5091EE3B", // Registre aquí su apiKey
 	"SECRETKEY" => "f8b45f9b8bcdb5702dc86a1b894492303741c405", // Registre aquí su secretKey
 	"APIURL" => "https://www.flow.cl/api", // Producción EndPoint o Sandbox EndPoint
 	"BASEURL" => "http://localhost/apiFlow" //Registre aquí la URL base en su página donde instalará el cliente
 );
 
 class Config {
 	
	static function get($name) {
		global $COMMERCE_CONFIG;
		if(!isset($COMMERCE_CONFIG[$name])) {
			throw new Exception("The configuration element thas not exist", 1);
		}
		return $COMMERCE_CONFIG[$name];
	}
 }
