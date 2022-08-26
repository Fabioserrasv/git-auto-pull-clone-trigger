# Git Clone & Pull Automatic
Trigger/script em PHP feito com o intuito de automatizar clone e pull atráves de webhooks do github, ou seja, quando um push é efetuado no seu repositório, o trigger será disparado e realizará o pull onde você configurar.

Seu funcionamento atualmente é basico, consiste em configurar o repositório e apontar o webhook nas configurações do mesmo no github.

# Configuração

## Preenchendo variáveis

O trigger trabalha atualmente com 6 variaveis sendo apenas 3 obrigatórias.

1. rep_host* - Nome do usuário git.
2. rep_name* - Nome do repositório.
3. rep_branch - Nome da branch (caso vazio -> main/master).
4. localfolder* - Caminho da pasta na qual o trigger irá atuar. *Ex: ./meu_repositorio*
5. pat (env) - Personal Access Token com permissões de repo caso o repositório seja privado. [Criar um token de acesso pessoal
](https://docs.github.com/pt/enterprise-server@3.3/authentication/keeping-your-account-and-data-secure/creating-a-personal-access-token)
6. pass (env) - Senha para proteger ainda mais o trigger.

*Obs¹: é importante dizer que esta senha (pass) é apenas um segurança a mais, não confie a segurança de sua rota apenas a esta senha.*

## Servidor

1. Obviamente, tenha instalado git em seu servidor.
2. Tenha certeza de que seu php.ini não está bloqueando a função *exec*.
3. Tenha certeza de que seu php tem permissão de read and write de arquivos.
4. Coloque o arquivo trigger.php no seu servidor.

## Como criar um webhook

Este processo será de como criar o webhook para acionar nosso trigger.

1. Acesse https://github.com/{seu_user}/{seu_repositorio}/settings/hooks
2. Clique em ***Add webhook***.
3. Em payload coloque a URL na qual será enviado o POST, ou seja, direcione para o trigger.php. *Ex: https://www.meusite.com/trigger.php?p=minha_senha*
4. Caso queria adicionar mais segurança configure uma secret key no campo Secret.
5. Clique no botão verde ***Add webhook***.


*Obs²: O parâmetro ?p= não é necessário caso você não utilize uma senha (pass) no primeiro passo de configuração de variáveis.*

Com isso a configuração estará pronta.

Agora quando você realizar um push no seu repositório, ele irá automaticamente clonar. Nas demais execuções irá executar o pull. Ao final de cada execução do trigger ele gera 2 arquivos de log, um com a extensão .log apresentável para humanos, com a resposta da linha de comando executada, e um log com a extensão JSON, nele contém as mesmas informações com o adendo do comando executado

# Considerações finais

Código feito com intuito de automatizar minhas alterações em projetos pessoais e facilitar cada vez mais esse processo de deploy em aplicações web HTML,JS,PHP.

Caso queira contribuir e manter sua mudança no meu repositório será bem vindo.

Utilize o código como bem entender, é livre para uso, modificação e demais.


😊 ~ Fábio Serra Vasconcelos
