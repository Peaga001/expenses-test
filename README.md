# Desafio Back-end Onfly
****

### Pedro Henrique Almeida Barros
- [Github](https://github.com/Peaga001) - [Linkedin](https://www.linkedin.com/in/peagadev/) - phalmeida001@gmail.com

## CRUD de Despesas
Aplicação laravel desenvolvida para a geração de despesas.
****

### Tecnologias Utilizadas
- **Php** ________8.2.14
- **Mysql** ______8.0.35
- **Composer** ___2.6.6
- **Git**_________2.34.1
- **Laravel** ____10.42.0
****

### Instalação do Projeto
- Clonar o projeto do github.
- Dentro da pasta do projeto rodar os comandos:
  - renomear o arquivo .env.example para .env
  - composer install (necessário ter o composer instalado).
  - php artisan prepare:application (necessário ter o mysql instalado e configurar a **.env**).
    - DB_DATABASE=nomeDoSeuBancoDeDados
    - DB_USERNAME=nomeDoSeuUsuarioMysql
    - DB_PASSWORD=senhaDOSeuUsuarioMysql
****

### Utilização do Projeto
#### Envio de email:
- Para utilizar o envio de email será necessário configurar o arquivo **.env**
  - MAIL_MAILER=smtp
  - MAIL_HOST=smtp.gmail.com
  - MAIL_PORT=587
  - MAIL_USERNAME="seumail@gmail.com"
  - MAIL_PASSWORD=senha gerada a partir do [tutorial](https://support.google.com/accounts/answer/185833?hl=pt-BR) entre apas
  - MAIL_ENCRYPTION=tls

#### Comandos:
- **php artisan serve**
  - Inicia a aplicação na url **http://localhost:8000**
- **php artisan test**
  - Roda os testes da aplicação
  
#### Funcionalidades:
- Testar as funcionalidades a partir da collection **DesafioOnflyCollectionPostman** disponível no projeto.
- **BearerToken**
  - Utilizado em todas as requisições /**expenses**.
  - Poderá ser gerado nas apis:
    - api/register
    - api/login

#### Documentação
- Para acessar a documentação do projeto será necessário:
  - Manter o servidor ativo com o comando: php artisan serve.
  - Acessar a url **http://localhost:8000/api/documentation**
  - **Documentação gerada apenas para visualização, testar via postman.**
****
> 
>  This is a challenge by [Coodesh](https://coodesh.com/)
