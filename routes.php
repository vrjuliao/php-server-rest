<?php
/**
 * Grupo dos enpoints iniciados por v1
 */
$app->group('', function() {

	/**
	 * Dentro de v1, o recurso /book
	 */
	$this->group('/main', function() {
		$this->get('', '\App\Controllers\MainController:validate_token');
	//	$this->post('', '\App\v1\Controllers\BookController:createBook');
		
		/**
		 * Validando se tem um integer no final da URL
		 */
	//	$this->get('/{id:[0-9]+}', '\App\v1\Controllers\BookController:viewBook');
	//	$this->put('/{id:[0-9]+}', '\App\v1\Controllers\BookController:updateBook');
	//	$this->delete('/{id:[0-9]+}', '\App\v1\Controllers\BookController:deleteBook');
	});
	/**
	 * Dentro de v1, o recurso /auth
	 */
	$this->group('/auth', function() {
		$this->post('', \App\Controllers\AuthController::class);
	});
});
