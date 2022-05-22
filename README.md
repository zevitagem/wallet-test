## Stack
- `PHP 8.0.19` ( Linguagem base )
- `MYSQL 8.0.27` ( Banco de dados )
- `Docker` ( Conteinerização )

## Build it ! :)
Na raiz do projeto, caso prefira, existe um arquivo `run.sh` que deixará sua aplicação funcionando automaticamente ( exceto a criação do banco ):
```shell
$ ./run.sh
```
--- 

Se ignorou a etapa anterior e gostaria de fazer manualmente, seguem os passos abaixo:

## Primeiro passo, crie as variáveis de ambiente
Crie um arquivo `.env` igual ao `.env.example` existente na raiz do projeto e ajuste conforme sua necessidade:
```
$ cp .env.example .env

APP_NAME=wallet
HOST=wallet-mysql
DATABASE=wallet
USER=root
PASSWORD=
PORT=3306
```

## Segundo passo, crie uma rede específica para o ambiente
```
$ docker network create wallet-network

8cf989e497dbae5ec45dc8aba97f04d1ddce8e4218a679a173850594c8e60cd7
```

## Terceiro passo, suba o serviço
Continue na raiz do projeto e suba o container com o comando:
```
docker compose up -d --build

[+] Running 4/0
 ⠿ Container wallet-mysql       Running                                                                                                      0.0s
 ⠿ Container wallet-phpmyadmin  Running                                                                                                      0.0s
 ⠿ Container wallet-php         Running                                                                                                      0.0s
 ⠿ Container wallet-ngrok       Running
```

## Quarto passo, acesse o container
```
$ docker exec -it wallet-php bash

root@93ab51d16283:/var/www/html/app# ls
composer.json  composer.lock  database	helpers  public  src  test  vendor
```

## Quinto passo, instale as dependências

Dentro do container, navegue até a pasta `/var/www/html/app` e execute:
```shell
$ composer install

Installing dependencies from lock file (including require-dev)
Verifying lock file contents can be installed on current platform.
Nothing to install, update or remove
Generating autoload files
26 packages you are using are looking for funding.
Use the `composer fund` command to find out more!
```

## Sexto passo, crie um banco de dados
Por ser um banco MYSQL, o comando para executar a criação é:
```
CREATE DATABASE IF NOT EXISTS wallet;
```

Você pode optar por acessar o `PHPMYADMIN`, serviço rodando na porta `http://localhost:3305/` e então criar pela interface web.

---

## Como testar
A aplicação possui apenas um ponto de entrada, navegue até o diretório:
`src/app/public`

Verás que existirão dois arquivos:
`database_fill_command.php` e `index.php`

Caso deseje alimentar/configurar o banco de dados com as tabelas e dados iniciais via terminal ( CLI ), será necessário:
```
$ php database_fill_command.php

{
	"status": false,
	"message": "A populated database already exists, this action will be rejected"
}
ou
{
	"status": true,
	"message": "Operation executed successfully"
}
```

Para resetar/configurar o banco de dados via REST, basta:
```
POST http://localhost:8080/app/public/reset
```

## Rotas disponíveis
Caso opte por executar as requisições via REST, será necessário:
```
http://localhost:8080/app/public
```

| ROTA | CONTROLLER | ACTION | VERBO |
| ------ | ------ | ------ | ------ |
| app/public/event | event | index |POST |
| app/public/balance | balance | index | GET |
| app/public/reset | reset | index | POST |

Se quiser especificar o método do `controller`, basta enviar na sequência:
| ROTA | CONTROLLER | ACTION | VERBO |
| ------ | ------ | ------ | ------ |
| app/public/event/store | event | store | POST |
| app/public/balance/show | balance | show | GET |
|  e assim por diante | ... | ... | ... |

## Testes unitários
```
$ vendor/bin/phpunit test/Unit/Domain/UseCases/DepositUseCaseTest.php
ou
$ vendor/bin/phpunit

PHPUnit 9.5.20 #StandWithUkraine
Warning:       No code coverage driver available

Deposit Use Case (Test\Unit\Domain\UseCases\DepositUseCase)
 ✔ Handle with exceptions in dependencies with only·account·service
 ✔ Handle with exceptions in dependencies with only·transaction·repository
 ✔ Handle with exceptions in dependencies with both·invalid·first·order
 ✔ Handle with exceptions in dependencies with both·invalid·second·order
 ✔ Get dependencie keys required
 ✔ Handle with non existent destination
 ✔ Handle with existent destination

Time: 00:00.203, Memory: 6.00 MB

OK (7 tests, 21 assertions)
```

## Estrutura das tabelas
![alt text](https://github.com/zevitagem/wallet-test/tree/main/github/images/schema_sql.png)


## O que foi implementado e o que ficou pendente?

As rotas propostas estão funcionando? SIM.
Existem as configurações do `NGROK` pelo projeto? SIM.
Conseguiu executar à tempo a suíte de testes usando o `NGROK`? NÃO.
Testou por onde para garantir o funcionamento? POSTMAN.
Existem testes unitários e estão passando? SIM e SIM.
Existem testes de integração? NÃO.
Como a aplicação controla o estado? BANCO DE DADOS.
Possui contato com testes de integração no dia-a-dia? qual? SIM, CYPRESS.
Usou algum framework conhecido no mercado? NÃO.
Iniciou o desenvolvimento do zero? SIM.
Se não usou framework, por que não usou? Porque a complexidade do exercício não exigia.
Aplicou qual conceito para estruturar a aplicação? DDD.
Usou algum design pattern? quais? SIM, repositórios, adapters, factories, etc.
As regras de domínio estão isoladas da infraestrutura e negócio? SIM.
Existe alguma dependência externa? SIM, PHPUNIT.
Existe algum analisador de qualidade de código? SIM, PHPSTAN.
Quanto tempo demora pra fazer um depósito? Média de 170ms
Quanto tempo demora pra fazer um saque? Média de 155ms
Quanto tempo demora pra fazer um saque? Média de 141ms

## Problemas
Infelizmente, por algum motivo, não foi possível fazer funcionar o `NGROK` ( *publish it on the internet* ), seguem os erros durante as tentativas:

```
$ docker run -d -p 4040 --network wallet-network --link wallet-php --name wallet-ngrok wernight/ngrok --plataform linux/amd64 

WARNING: The requested image's platform (linux/amd64) does not match the detected host platform (linux/arm64/v8) and no specific platform was requested
8f34fa4f7a49d7a95c210ed93aaba7de5b3fee7599b49768d3ca01e9148509a3
docker: Error response from daemon: failed to create shim: OCI runtime create failed: container_linux.go:380: starting container process caused: exec: "--plataform": executable file not found in $PATH: unknown
```

```
http://localhost:4040/status
reconnecting - x509: certificate signed by unknown authority
reconnecting - resolved tunnel.us.ngrok.com has no records
```

```
$ curl $(docker port wallet-ngrok 4040)/api/tunnels
{"tunnels":[],"uri":"/api/tunnels"}
```