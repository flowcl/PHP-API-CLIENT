<?php

/**
 * Clase cliente del Api2 de Flow
 * @Filename: FlowApi.class.php
 * @version: 2.0
 * @Author: flow.cl
 * @Email: csepulveda@tuxpan.com
 * @Date: 28-04-2017 11:32
 * @Last Modified by: Carlos Sepulveda
 * @Last Modified time: 28-04-2017 11:32
 */

require(__DIR__ . "/Config.class.php");

class FlowApi {
	
	protected $apiKey;
	protected $secretKey;
	
	
	public function __construct() {
		$this->apiKey = Config::get("APIKEY");
		$this->secretKey = Config::get("SECRETKEY");
	}
	
	
	/**
	 * Funcion que invoca un servicio del Api de Flow
	 * @param string $service Nombre del servicio a ser invocado
	 * @param array $params datos a ser enviados
	 * @param string $method metodo http a utilizar
	 * @return string en formato JSON
	 */
	public function send( $service, $params, $method = "GET") {
		$method = strtoupper($method);
		$url = Config::get("APIURL") . "/" . $service;
		$params = array("apiKey" => $this->apiKey) + $params;
		$data = $this->getPack($params, $method);
		$sign = $this->sign($params);
		if($method == "GET") {
			$response = $this->httpGet($url, $data, $sign);
		} else {
			$response = $this->httpPost($url, $data, $sign);
		}
		
		if(isset($response["info"])) {
			$code = $response["info"]["http_code"];
			$body = json_decode($response["output"], true);
			if($code == "200") {
				return $body;
			} elseif(in_array($code, array("400", "401"))) {
				throw new Exception($body["message"], $body["code"]);
			} else {
				throw new Exception("Unexpected error occurred. HTTP_CODE: " .$code , $code);
			}
		} else {
			throw new Exception("Unexpected error occurred.");
		}
	}
	
	/**
	 * Funcion para setear el apiKey y secretKey y no usar los de la configuracion
	 */
	public function setKeys($apiKey, $secretKey) {
		$this->apiKey = $apiKey;
		$this->secretKey = $secretKey;
	}
	
	/**
	 * Funcion que empaqueta los datos de parametros para ser enviados
	 * @param array $params datos a ser empaquetados
	 * @param string $method metodo http a utilizar
	 */
	private function getPack($params, $method) {
		$keys = array_keys($params);
		sort($keys);
		$data = "";
		foreach ($keys as $key) {
			if($method == "GET") {
				$data .= "&" . rawurlencode($key) . "=" . rawurlencode($params[$key]);
			} else {
				$data .= "&" . $key . "=" . $params[$key];
			}
		}
		return substr($data, 1);
	}
	
	/**
	 * Funcion que firma los parametros
	 * @param string $params Parametros a firmar
	 * @return string de firma
	 */
	private function sign($params) {
		$keys = array_keys($params);
		sort($keys);
		$toSign = "";
		foreach ($keys as $key) {
			$toSign .= "&" . $key . "=" . $params[$key];
		}
		$toSign = substr($toSign, 1);
		if(!function_exists("hash_hmac")) {
			throw new Exception("function hash_hmac not exist", 1);
		}
		return hash_hmac('sha256', $toSign , $this->secretKey);
	}
	
	
	/**
	 * Funcion que hace el llamado via http GET
	 * @param string $url url a invocar
	 * @param array $data datos a enviar
	 * @param string $sign firma de los datos
	 * @return string en formato JSON 
	 */
	private function httpGet($url, $data, $sign) {
		$url = $url . "?" . $data . "&s=" . $sign;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		$output = curl_exec($ch);
		if($output === false) {
			$error = curl_error($ch);
			throw new Exception($error, 1);
		}
		$info = curl_getinfo($ch);
		curl_close($ch);
		return array("output" =>$output, "info" => $info);
	}
	
	/**
	 * Funcion que hace el llamado via http POST
	 * @param string $url url a invocar
	 * @param array $data datos a enviar
	 * @param string $sign firma de los datos
	 * @return string en formato JSON 
	 */
	private function httpPost($url, $data, $sign ) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data . "&s=" . $sign);
		$output = curl_exec($ch);
		if($output === false) {
			$error = curl_error($ch);
			throw new Exception($error, 1);
		}
		$info = curl_getinfo($ch);
		curl_close($ch);
		return array("output" =>$output, "info" => $info);
	}
	
	
}
