# php-server-rest
Simple way to do a **REST AUTH API** with PHP

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
### Pre-requisites
- PHP 5.3+
- Composer
- MySQL

### Install dependencies
Clone or donwloand this repository, after go at project directory and use bash console to run:
```console
foo@bar:~/<project_directory>$ composer install
```

### Configure Database
```sql
CREATE DATABASE oauth_slim;
USE oauth_slim;

CREATE TABLE oauth_users(id INT NOT NULL AUTO_INCREMENT
                         name VARCHAR(255) NOT NULL
                         password VARCHAR(255) NOT NULL
                         email VARCHAR(255) NOT NULL UNIQUE
                         key_word VARCHAR(1024) NOT NULL DEFAULT 'NULL');

CREATE USER 'dev_root'@'127.0.0.1' IDENTIFIED BY 'dev_root_password';

GRANT SELECT, INSERT, UPDATE, DELETE ON oauth_slim.oauth_users TO 'dev_root'@'127.0.0.1' IDENTIFIED BY 'dev_root_password';

FLUSH PRIVILEGES;

//Add a user to future tests
INSERT INTO oauth_users (id, name, password, email) 
VALUES (111,
        'user test',
        'c4ca4238a0b923820dcc509a6f75849b',
        'user_test@email.com');
COMMIT;

```

## Usage Guide
### Server configure (Apache or PHP-Server)

#### + Using Apache
- Copy the content of php-server-rest directory and paste in apache directory on your computer.

#### + Using PHP-Server
- Using bash console go at project directory
```console
foo@bar:~$ cd <project_directory>
```

- Start PHP-Server with host ip and port
```console
foo@bar:~/<project_directory>$ php -S 127.0.0.1:8000
```

### Send Requests
This service is configured to read and send messages in JSON format, so you need to add a content type header by specifying the data type of the message:
`Content-type: application/json`

To check the operation of the service, install a REST CLIENT extension in your browser or use CURL commands

#### 1. Auth Request
The auth route uses the POST method and runs `http:://127.0.0.1:8000/auth`. In the body of request is necessary the user email and password, using JSON format:
```
{
	"email":"user_test@email.com",
	"password":"c4ca4238a0b923820dcc509a6f75849b"
}
```
If successful, the response of this request is a JSON containing the token and the user ID that need to be added as the header of all other requests. **Whenever the authentication route succeeds, the returned token is modified and only the last token returned is valid to execute other request routes**.

#### 2. Other Requests
To execute other routes, you need to add two new request headers that were returned in the authentication request: 
`user_id: <retuned_user_id>` and `web_token: <returned_web_token>`

You can execute the test route, with GET method `http://127.0.0.1:8000/example`

#### 3. Creating new routes and methods
Use `routes.php` to create and customize your new route requests. If you do not know how to create a group of routes, [read this page] [4].
Take `src/Controllers/ExampleController.php` as an example to create new Controllers and modularize your project.

[1]: http://www.slimframework.com
[2]: https://github.com/firebase/php-jwt
[3]: https://github.com/tuupola/slim-jwt-auth
[4]: http://www.slimframework.com/docs/v3/objects/router.html