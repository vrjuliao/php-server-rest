# php-server-rest
Simple way to do a **REST AUTH API**

## Introduction
Through the use of three composer libraries, this service implements an access control for the server routes.
The composer libraries and their respective functions are:
#### 1. [Slim Framework][1]

    - Provide the REST API.
#### 2. [PHP-JWT][2]

    - Responsible for key tokenizer.
#### 3. [Authentication Middleware][3]

    - Implements an intermediate function before executing the expected route.

## Instalation Guide





[1]: http://www.slimframework.com
[2]: https://github.com/firebase/php-jwt
[3]: https://github.com/tuupola/slim-jwt-auth