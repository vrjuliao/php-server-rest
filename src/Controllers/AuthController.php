<?php
namespace App\Controllers;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Firebase\JWT\JWT;
/**
 * Controller de Autenticação
 */
class AuthController {
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
	public function __invoke(Request $request, Response $response, $args) {
		/**
	 	* JWT Key
	 	*/
		$key_length = $this->container['open_ssl_key_length'];
		$cstrong = true;
		$request_data = $request->getParsedBody();
		
		$connection = new \PDO('mysql:host='.$this->container['dbhost'].';dbname='.$this->container['dbname'],
						$this->container['dbuser'], $this->container['dbpassword']);
		$request_data = $this->get_user_data($request_data, $connection);

		$bytes = openssl_random_pseudo_bytes($key_length, $cstrong);
    	$key   = bin2hex($bytes);
		
    	$this->upload_key($request_data, $key, $connection);
		$token = array(
			"user_name" => $request_data['name'],
			"email" => $request_data['email']
		);
		$jwt = JWT::encode($token, $key);
		$response_object = array(
			'web_token' => $jwt,
			'user_id'   => $request_data['id']);
		return $response->withJson($response_object, 200)
			->withHeader('Content-type', 'application/json');
	}

	private function upload_key($request_data, $key, $connection){
		$connection->beginTransaction();

		$stmt = $connection->prepare(
			'UPDATE oauth_users SET key_word = :key_word
			WHERE id = :id;'
		);

		$params = [
			'key_word' => $key,
			'id' => $request_data['id']
		];
		$stmt->execute($params);
		$connection->commit();
	}

	private function get_user_data($data, $connection){
		//validate email
		$pattern = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i";
		if(!preg_match($pattern, $data['email'])){
		// if(!preg_match($pattern, "asdkj\\lahsjssd/")){
			throw new \Exception('Invalid email data', 400);
		}

		//validate password
		$pattern = "/^[a-f0-9]{32}$/";
		if(!preg_match($pattern, $data['password'])){
			throw new \Exception('Invalid password data', 400);
		}

		$stmt = $connection->prepare(
			'SELECT * FROM oauth_users WHERE email = :email;'
		);
		$stmt->bindValue(':email', $data['email']);
		$stmt->execute();
		$row = $stmt->fetch(\PDO::FETCH_ASSOC);

		if(empty($row)){
			throw new \Exception('Non-existent user', 400);
		} else if ($data['password'] != $row['password']){
			throw new \Exception('Incorrect password', 400);
		}
		$stmt->closeCursor();
		return $row;
	}

}