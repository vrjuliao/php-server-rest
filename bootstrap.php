<?php

require './vendor/autoload.php';

use Firebase\JWT\JWT;

/**
 * Configurações
 */
$configs = [
	'settings' => [
		'displayErrorDetails' => true,
		'addContentLengthHeader' => false,
	]   
];

/**
 * Container Resources do Slim.
 * Aqui dentro dele vamos carregar todas as dependências
 * da nossa aplicação que vão ser consumidas durante a execução
 * da nossa API
 */
$container = new \Slim\Container($configs);
$container['open_ssl_key_length'] = 512;
$container['dbhost'] = '127.0.0.1';
$container['dbname'] = 'oauth_slim';
$container['dbuser'] = 'dev_root';
$container['dbpassword'] = 'dev_root_password';
//rotas que a classe AuthValidate nao atuara
$container['passthrough'] = ['/auth'];

/**
 * Converte os Exceptions Genéricas dentro da Aplicação em respostas JSON
 */
$container['errorHandler'] = function ($container) {
	return function ($request, $response, $exception) use ($container) {
		$statusCode = $exception->getCode() ? $exception->getCode() : 500;
		return $container['response']->withStatus($statusCode)
			->withHeader('Content-Type', 'Application/json')
			->withJson(["message" => $exception->getMessage()], $statusCode);
	};
};

/**
 * Converte os Exceptions de Erros 405 - Not Allowed
 */
$container['notAllowedHandler'] = function ($container) {
	return function ($request, $response, $methods) use ($container) {
		return $container['response']
			->withStatus(405)
			->withHeader('Allow', implode(', ', $methods))
			->withHeader('Content-Type', 'Application/json')
			->withHeader("Access-Control-Allow-Methods", implode(",", $methods))
			->withJson(["message" => "Method not Allowed; Method must be one of: " . implode(', ', $methods)], 405);
	};
};

/**
 * Converte os Exceptions de Erros 404 - Not Found
 */
$container['notFoundHandler'] = function ($container) {
	return function ($request, $response) use ($container) {
		return $container['response']
			->withStatus(404)
			->withHeader('Content-Type', 'Application/json')
			->withJson(['message' => 'Page not found']);
	};
};

$isDevMode = true;

/**
 * Application Instance
 */
$app = new \Slim\App($container);

$app->add(new \App\AuthValidate($container));