<?php
	// error_reporting(0);
	header("Access-Control-Allow-Origin: *");
	header('Access-Control-Allow-Credentials: true');
	header('Access-Control-Allow-Methods: GET, POST');
	header("Access-Control-Allow-Headers: X-Requested-With");
	header('Content-Type: application/json; charset=utf-8');
	header('P3P: CP="IDC DSP COR CURa ADMa OUR IND PHY ONL COM STA"');

	use \Psr\Http\Message\ServerRequestInterface as Request;
	use \Psr\Http\Message\ResponseInterface as Response;

	require '../include/config.php';
	require '../include/autoload.php';
	require '../libs/Slim/vendor/autoload.php';
	require '../libs/PHPMailer/PHPMailerAutoload.php';

	$app = new \Slim\App();

	$app->options('/{Class_Name}/{Class_Method}/', function (Request $request, Response $response) {
		return true;
	});

	$app->post('/{Class_Name}/{Class_Method}/', function (Request $request, Response $response) {
		$headers = apache_request_headers();
		// print_r($headers);
		// if ($headers['authorization'] == API_KEY || $headers['Authorization'] == API_KEY) {
			$Input_Filter = new inputFilter();
			$_SERVER['PHP_SELF'] = htmlspecialchars($Input_Filter->process($_SERVER['PHP_SELF']));

			$Class_Name	   = ucfirst( $request->getAttribute('Class_Name') );
			$Class_Method  = $request->getAttribute('Class_Method');
			$Arguments 	   = $_GET;

			$Class_Manager = new Class_Manager($Class_Name, $Class_Method, $Arguments);
		// }
		// else{
			// $newResponse = $response->withStatus(401);
			// print( $newResponse );
		// }
	});

	$app->run();
?>