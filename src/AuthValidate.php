<?php
namespace App;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use \Slim\Middleware\JwtAuthentication;
/**
 * Controller de Autenticação
 */
class AuthValidate extends JwtAuthentication {
	public function __construct() {
		$options =	[
			"regexp" => "/(.*)/",
			"header" => "X-Token",
			"path" => "/",
			"passthrough" => ["/auth", "/admin/ping"],
			// "passthrough" => ["/admin/ping"],
			"realm" => "Protected",
			"secret" => 'NULL'
		];

		// var_dump("chegou aqui asagdhadjhgakjsdhfalksjdfhçaoskdhflkajhdlk");
		parent::__construct($options);
	}


	public function __invoke(RequestInterface $request, ResponseInterface $response, callable $next){
		
		$user_id = $request->getHeader("user_id");
		$uri = "/" . $request->getUri()->getPath();
        $uri = preg_replace("#/+#", "/", $uri);

		if(!isset($user_id[0]) && !in_array($uri, $this->getPassthrough(), false)){
			return $this->error($request, $response->withStatus(401), [
                "message" => $this->message
            ]);
		} else if(isset($user_id[0])){
			$user_id = $user_id[0];
			if(!preg_match('/^\d+$/', $user_id)){
				return $this->error($request, $response->withStatus(401), [
                	// "message" => $this->message
                	"message" => 'Hack failled'
            	]);
			}
			$key_word = $this->get_key_word_from_id($user_id);
			$this->setSecret($key_word);
		}
		return parent::__invoke($request, $response, $next);
	}

	private function get_key_word_from_id($user_id){
		$conn = new \PDO('mysql:host=localhost;dbname=oauth_slim', 'dev_root', 'dev_root_password');
		$stmt = $conn->prepare(
			'SELECT * FROM oauth_users WHERE id = :id;'
		);
		$stmt->bindValue(':id', $user_id);
		$stmt->execute();
		$row = $stmt->fetch(\PDO::FETCH_ASSOC);
		$stmt->closeCursor();
		return $row['key_word'];
	}
}