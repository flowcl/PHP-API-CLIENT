# PHP-API-CLIENT
Cliente PHP para consumir el API de Flow.

**Flow.cl** es una pasarela de pagos para comercio electrónico. Este cliente le permite integrar su ecommerce para recibir pagos online.

## Requerimientos
* PHP 5.2 o superior
* php_curl activado
* php_openssl activado

## Instalación
Baje la última versión y copie los archivos en su servidor.

## Documentación
La documentación completa del API REST de Flow la encuentrá aquí: https://www.flow.cl/docs/api.html

## Comenzando
### Configurando el cliente
Configure correctamente el cliente en el archivo **lib/Config.class.php**.
Lo primero que debe configurar es su apiKey y secretKey del comercio registrado en Flow. Esto lo obtiene en la sección **Mis datos** acceda a https://www.flow.cl, una vez autenticado con su cuenta Flow, seleccione Mis datos y recupere su apiKey y secretKey desde la sección Seguridad.
- **APIKEY** el apiKey obtenida desde su cuenta Flow
- **SECRETKEY** el secretKey obtenida desde su cuenta Flow
- **APIURL** la URL del endpoint del API de Flow, Aquí podrá configurar el endpoint de producción o del sandbox. Esta información se obtiene en la documentación del API https://www.flow.cl/docs/api.html
- **BASEURL** La URL base donde instaló el cliente PHP en su servidor


```php
$COMMERCE_CONFIG = array(
 	"APIKEY" => "1F90971E-8276-4713-97FF-2BLF5091EE3B", // Registre aquí su apiKey
 	"SECRETKEY" => "f8b45f9b8bcdb5702dc86a1b894492303741c405", // Registre aquí su secretKey
 	"APIURL" => "https://www.flow.cl/api", // Producción EndPoint o Sandbox EndPoint
 	"BASEURL" => "https://www.misitio/apiFlow" //Registre aquí la URL base en su página donde instaló el cliente
 );
```
### Llamando a un servicio
En este ejemplo crearemos una Orden de Cobro y redireccionaremos el browser del pagador para efectuar el pago
```php
<?php
/**
 * Ejemplo de creación de una orden de cobro, iniciando una transacción de pago
 * Utiliza el método payment/create
 */
require(__DIR__ . "/../../lib/FlowApi.class.php");

//Para datos opcionales campo "optional" prepara un arreglo JSON
$optional = array(
	"rut" => "9999999-9",
	"otroDato" => "otroDato"
);
$optional = json_encode($optional);

//Prepara el arreglo de datos
$params = array(
	"commerceOrder" => rand(1100,2000),
	"subject" => "Pago de prueba",
	"currency" => "CLP",
	"amount" => 5000,
	"email" => "cliente@gmail.com",
	"paymentMethod" => 9,
	"urlConfirmation" => Config::get("BASEURL") . "/examples/payments/confirm.php",
	"urlReturn" => Config::get("BASEURL") ."/examples/payments/result.php",
	"optional" => $optional
);
//Define el metodo a usar
$serviceName = "payment/create";

try {
	// Instancia la clase FlowApi
	$flowApi = new FlowApi;
	// Ejecuta el servicio
	$response = $flowApi->send($serviceName, $params,"POST");
	//Prepara url para redireccionar el browser del pagador
	$redirect = $response["url"] . "?token=" . $response["token"];
	header("location:$redirect");
} catch (Exception $e) {
	echo $e->getCode() . " - " . $e->getMessage();
}
?>
```
Otros ejemplos los podrá ver en la carpeta **examples** de este cliente.
