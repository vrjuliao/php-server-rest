<?php
$app->group('', function() {

	$this->group('/example', function() {
		$this->get('', '\App\Controllers\ExampleController:example_method');
	
	$this->group('/auth', function() {
		$this->post('', \App\Controllers\AuthController::class);
	});
});
