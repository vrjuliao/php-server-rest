# php-server-rest
Simple way to do a **REST AUTH API**
---

## Introduction
Through the use of three composer libraries, this service implements an access control for the server routes.
The composer libraries and their respective functions are:
#### 1. [Slim Framework][1]
- Provide the REST API.
#### 2. [PHP-JWT][2]
- Responsible for key tokenizer.
#### 3. [Authentication Middleware][3]
- Implements an intermediate function before executing the expected route.
---

## Instalation Guide
### Pre-requisites
- PHP 5.0+
- Composer
- MySQL

### Configuring Data Base
```sql
CREATE DATABASE oauth_slim;
USE oauth_slim;

CREATE TABLE oauth_users(id INT NOT NULL AUTO_INCREMENT
                         name VARCHAR(255) NOT NULL
                         password VARCHAR(255) NOT NULL
                         email VARCHAR(255) NOT NULL UNIQUE
                         key_word VARCHAR(1024) NOT NULL DEFAULT 'NULL')

CREATE USER 'dev_root'@'127.0.0.1' IDENTIFIED BY 'dev_root_password';

GRANT SELECT, INSERT, UPDATE, DELETE ON oauth_slim.oauth_users TO 'dev_root'@'127.0.0.1' IDENTIFIED BY 'dev_root_password';
```

[1]: http://www.slimframework.com
[2]: https://github.com/firebase/php-jwt
[3]: https://github.com/tuupola/slim-jwt-auth