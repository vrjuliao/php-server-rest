<?php
namespace App\Controllers;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
/**
 * Controller de Autenticação
 */
class MainController {
	/**
	 * Container
	 * @var object s
	 */
	protected $container;
   
	/**
	* Undocumented function
	* @param ContainerInterface $container
	*/
	public function __construct($container) {
		$this->container = $container;
	}
   
   /**
	* Invokable Method
	* @param Request $request
	* @param Response $response
	* @param [type] $args
	* @return void
	*/
	public function validate_token(Request $request, Response $response, $args) {
		return $response->withJson(["message" => "SUCCESS"], 200)
			->withHeader('Content-type', 'application/json');
		 
		//return $response->getBody()->write('auth routes');
	}

}