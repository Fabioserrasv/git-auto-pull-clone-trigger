# Git Clone & Pull Automatic
Esta aplicação pode ser resumida como uma ferramenta visual de git para realizar clone, pull e reset de forma simples e fácil no seu servidor.
# Configuração

## Preenchendo variáveis

A aplicação trabalha atualmente com 6 variaveis sendo apenas 3 obrigatórias.

1. REP_HOST* - Nome do usuário git.
2. REP_NAME* - Nome do repositório.
3. REP_BRANCH - Nome da branch (caso vazio -> main/master).
4. LOCALFOLDER* - Caminho da pasta na qual o trigger irá atuar. *Ex: ./meu_repositorio*
5. PAT (env) - Personal Access Token com permissões de repo caso o repositório seja privado. [Criar um token de acesso pessoal
](https://docs.github.com/pt/enterprise-server@3.3/authentication/keeping-your-account-and-data-secure/creating-a-personal-access-token)
6. PASS (env) - Senha para proteger ainda mais o trigger, também é necessário configurar esta senha no arquivo js/functions.js.

*Obs¹: é importante dizer que esta senha (pass) é apenas um segurança a mais, não confie a segurança de sua rota apenas a esta senha.*

## Servidor

1. Obviamente, tenha instalado git em seu servidor.
2. Tenha certeza de que seu php.ini não está bloqueando a função *exec*.
3. Tenha certeza de que seu php tem permissão de read and write de arquivos.
4. Coloque a aplicação no seu servidor.

# Como Funciona

Após ter passado por todas fases de configuração e finalmente estar com a aplicação aberta no seu navegador, basta clicar em *Pull changes* ou *Revert to Commit*, a aplicação conta com apenas estas 2 funcionalidades. Lembrando que para o botão *Revert to Commit* executar sua função é necessário informar o número do commit no campo de texto ao lado. 
A caixa de informações a esquerda e a direita são respectivamente sobre o log da aplicação e os commits feitos no repositório apontado.

*Obs¹: É possível configurar a limitação de registros que são listados no log e commits no arquivo js/functions.js nas variáveis *amount*.
# Considerações finais

Código feito com intuito de automatizar minhas alterações em projetos pessoais e facilitar cada vez mais esse processo de deploy em aplicações web HTML,JS,PHP.

Caso queira contribuir e manter sua mudança no meu repositório será bem vindo.

Utilize o código como bem entender, é livre para uso, modificação e demais.

Front-end Desenvolvido por [Antônio Pires](https://github.com/antoniovpires)

😊 ~ Fábio Serra Vasconcelos
