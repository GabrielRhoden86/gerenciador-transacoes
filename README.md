####  Sistema de Gerenciamento de Transações

# Descrição

Sistema de gestão financeira para clientes, permitindo o controle de créditos e débitos, com regras de saldo, histórico de transações e controle de acesso por perfil de usuário.

O sistema é uma ferramenta administrativa, portanto clientes não possuem acesso direto.

__________________________________________Usuários__________________________________________________________
# Login de usuários

Dois perfis de usuário:

✔️  Admin: controle total (cadastro e transações)

✔️  User: acesso somente para consulta

_________________________________________Passo a Passo para Rodar o Projeto_________________________________

- Certifique -se 
Porta 8000 e 3000 livres

  * linux:
  cp .env.example .env

  * windows:
  cd copy .env.example .env

    
  1. Subir os containers:

  docker compose build --no-cache
  docker compose up -d

  2. Executar as migrations
  docker exec -it laravel-app php artisan migrate

  3. Popular o banco com dados de teste
  docker exec -it laravel-app php artisan db:seed

______________________________________________________Acesso ao Sistema____________________________________________

http://localhost:8000

# Usuários de Teste

* Admin (controle total)

email: admin@exemplo.com  
senha: admin123

* User (somente consulta)

email: user@exemplo.com  
senha: admin123

____________________________________________________Funcionalidade Principal_______________________________
# /clientes

Lista de clientes

Na coluna Ações é possível:

Visualizar o cliente

Executar transações de crédito e débito

Visualizar histórico e saldo atual


_____________________________________________________Teste da API (Backend)________________________________________
Autenticação  (sanctum)

# Após o login insira o token no Headers da requisição
Tipo: Bearer Token
Headers:
Accept: application/json
Authorization: Bearer {token}

# 1 - faça o login utilizando as credenciais:
http://127.0.0.1:8000/api/login

"token": "3|kIX8W...

# visualizar todos os clientes
http://127.0.0.1:8000/api/clients

# acesso cliente individual
http://127.0.0.1:8000/api/clients/9

# inserir crédito
http://127.0.0.1:8000/api/transactions/9/credit

{
  "amount": 140.24,
  "description": "Recarga manual"
}

# inserir débito
http://127.0.0.1:8000/api/transactions/9/debit
{
  "amount": 10.55,
  "description": "Debito Manual"
}

# logout
http://127.0.0.1:8000/api/logout


_____________________________________________________Padrão de Projeto adotado___________________________________________

Foi adotado o padrão Repository com Interface, visando:

1. Separação de responsabilidades

2. Melhor legibilidade

3. Facilidade de manutenção

4. Suporte a testes com mocks

Camadas

* Controller: recebe as requisições

* Service: regras de negócio

* Repository: acesso e manipulação dos dados

* Interface: abstração para desacoplamento e testes

________________________________________________________Testes Automatizados_________________________________

Os testes cobrem regras de negócio, fluxos principais e controllers.

# Testes de Feature
backend/tests/Feature/TransactionControllerTest.php
backend/tests/Feature/ClientControllerTest.php

Testam endpoints

Validação de permissões

Fluxos completos de transações

Testes Unitários
backend/tests/Unit/ClientServiceTest.php
backend/tests/Unit/TransactionServiceTest.php

# Executar Testes
docker exec -it laravel-app php artisan test --coverage




