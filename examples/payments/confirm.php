<?php
/**
 * Pagina del comercio para recibir la confirmación del pago
 * Flow notifica al comercio del pago efectuado
 */
require(__DIR__ . "/../../lib/FlowApi.class.php");

try {
	if(!isset($_POST["token"])) {
		throw new Exception("No se recibio el token", 1);
	}
	$token = filter_input(INPUT_POST, 'token');
	$params = array(
		"token" => $token
	);
	$serviceName = "payment/getStatus";
	$flowApi = new FlowApi();
	$response = $flowApi->send($serviceName, $params, "GET");
	
	//Actualiza los datos en su sistema
	
	print_r($response);
	
	
} catch (Exception $e) {
	echo "Error: " . $e->getCode() . " - " . $e->getMessage();
}
?>