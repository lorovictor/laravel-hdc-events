## LARAVEL PROJECTS

**Passo 01 - Clonar projeto**   
`git clone [https://github.com/lorovictor/laravel-projects.git]`

### Comandos para serem feitos após clonar o projeto, executar os comando abaixo dentro do diretorio do projeto

**Passo 02 - Instala dependencias e pacotes do projeto**  
`composer install`

**Passo 03 - Configurar o arquivo de conexão com o banco de dados**  
  `.env`

**Passo 04 - Gerar uma chave de desenvolvimento para o projeto Laravel**  
`php artisan key:generate`

**Realizer a migração criando as tabelas do banco de dados**  
`php artisan migrate`

**Iniciar o servidor**  
`php artisan serve`

## Mais Comandos Básicos Úteis  
Esses comandos devem ser usados no terminal / cmd do projeto

**Listar os comandos**  
`php artisan list`

**Criar um arquivo de migração referenciando uma tabela**  
`php artisan make:migration create_table_nome_tabela --create=nome_tabela`

**Criar um Model e o -m já cria um arquivo de migração**  
`php artisan make:model NomeModel -m`

**Criar um Controller e o --model associa com o Model da tabela**  
`php artisan make:controller NomeTabelaController --resource --model=NomeTabelaModel`

**Executa a insersão dos registros no banco de dados de tudo que esta dentro do database/seeds/DatabaseSeeder.php**  
`php artisan db:seed`

**Executa a insersão dos registros no banco de dado, de forma separada por Classe**  
`php artisan db:seed --class=NomeTabelaSeeder`
