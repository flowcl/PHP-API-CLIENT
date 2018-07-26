<?php
/**
 * Obtienen el resultado de una transacción en base al commerceId de la transaccion
 */
require(__DIR__ . "/../../lib/FlowApi.class.php");

try {
	$params = array(
		"commerceId" => "1306"
	);
	$serviceName = "payment/getStatusByCommerceId";
	$flowApi = new FlowApi();
	$response = $flowApi->send($serviceName, $params, "GET");
	
	print_r($response);
	
	
} catch (Exception $e) {
	echo "Error: " . $e->getCode() . " - " . $e->getMessage();
}

?>