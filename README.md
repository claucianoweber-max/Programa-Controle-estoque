<<<<<<< HEAD
# Sistema eEstoque

## Descrição

Sistema web desenvolvido para gerenciamento e controle de estoque, permitindo cadastro de produtos, categorias, fornecedores e movimentações de entrada e saída.

## Tecnologias utilizadas

- PHP
- MySQL
- HTML5
- CSS3
- JavaScript
- Bootstrap

## Requisitos

- PHP 8+
- MySQL
- Apache
- XAMPP/WAMP/Linux

## Diagrama de Caso de Uso


PROJETO TECNOLOGICO EM DESENVOLVIMENTO DE SISTEMAS 
Etapa - Levantamento de Requisitos

Sistema Web de Controle de Estoque
Clauciano Dias Dos Santos Weber
Clauciano.weber@rede.ulbra.com.br

 Levantamento de Requisitos
 1. Requisitos Funcionais (RF)
São as funcionalidades que o sistema deve oferecer:

     Gestão de Produtos
-	RF01: Cadastrar produtos (nome, código, preço, quantidade, categoria, fornecedor) 
-	RF02: Editar dados de produtos 
-	RF03: Excluir produtos 
-	RF04: Listar e pesquisar produtos 

Categorias e Fornecedores
-	RF05: Cadastrar categorias 
-	RF06: Cadastrar fornecedores 
-	RF07: Editar e excluir categorias e fornecedores 

 Movimentação de Estoque
-	RF08: Registrar entrada de produtos 
-	RF09: Registrar saída de produtos (venda/perda) 
-	RF10: Atualizar automaticamente o estoque 
-	RF11: Consultar histórico de movimentações 

Controle de Estoque
-	RF12: Definir estoque mínimo por produto 

-	RF13: Emitir alertas visuais para estoque baixo 
-	RF14: Exibir status do estoque (normal/crítico) 

Usuários e Acesso
-	RF15: Realizar login no sistema 
-	RF16: Controlar níveis de acesso (admin/usuário) 
-	RF17: Registrar ações realizadas (log de atividades) 

Interface e Acesso
-	RF18: Interface responsiva (desktop, tablet e mobile) 
-	RF19: Navegação intuitiva e amigável 

Sistema e Testes
-	RF20: Disponibilizar versão demo 
-	RF21: Permitir ajustes e revisões durante o desenvolvimento 

2. Requisitos Não Funcionais (RNF)
Relacionados à qualidade do sistema:
Desempenho
-	RNF01: O sistema deve atualizar o estoque em tempo real 
-	RNF02: Tempo de resposta inferior a 3 segundos 
 Segurança
-	RNF03: Autenticação com login e senha 
-	RNF04: Proteção contra acessos não autorizados 
-	RNF05: Criptografia de dados sensíveis (senhas) 
 Usabilidade
-	RNF06: Interface simples e fácil de usar 
-	RNF07: Compatível com diferentes dispositivos 
Disponibilidade
-	RNF08: Sistema disponível 24/7 (dependendo da hospedagem) 
 Manutenibilidade
-	RNF09: Código organizado e documentado 
-	RNF10: Facilidade para manutenção e futuras melhorias 

 3. Requisitos de Negócio (RN)
Regras que definem o funcionamento do sistema:
-	RN01: Não permitir saída de produto com estoque zerado 
-	RN02: Alertar automaticamente quando o estoque atingir o mínimo 
-	RN03: Toda movimentação deve ser registrada 
-	RN04: Apenas administradores podem excluir registros 

-	RN05: O sistema deve refletir o estoque real da empresa 

 4. Requisitos de Sistema (Tecnológicos)
Baseados:
-	Back-end: PHP 
-	Front-end: HTML5, CSS3, JavaScript, Bootstrap 
-	Banco de Dados: MySQL (ou similar) 
-	Ambiente: Navegador web moderno 
5. Atores do Sistema
-	Administrador: Gerencia produtos, usuários e relatórios 
-	Usuário comum: Realiza movimentações de estoque 

6. Casos de Uso Principais
-	Cadastrar produto 
-	Registrar entrada de estoque 
-	Registrar saída de estoque 
-	Consultar estoque 
-	Receber alerta de estoque baixo 
-	Fazer login no sistema 

7. Critérios de Aceitação (Exemplos)
-	Produto cadastrado deve aparecer imediatamente na lista 
-	Estoque deve ser atualizado após cada movimentação 
-	Sistema deve impedir vendas sem estoque disponível 
-	Alertas devem aparecer quando atingir estoque mínimo 


8. Restrições do Projeto
-	Uso de tecnologias específicas (PHP e MySQL) 
-	Prazo de entrega (conforme acertado em contrato)
-	Melhorias visuais 
-	Somente testado em navegador Google Chrome e redimensionado no desktop para ver como fica a tela responsiva.
-	OBS - Pode conter algumas funcionalidades ainda não ajustadas.



## Funcionalidades

- Login de usuários
![alt text](<01 login-1.png>)
- Dashboard administrativo
![alt text](<02 dashboard.png>)
- Cadastro de produtos
![alt text](<03 produtos.png>)
- Cadastro de categorias
![alt text](<04 categorias.png>)
- Cadastro de fornecedores
![alt text](<05 fornecedor.png>)
- Controle de estoque
![alt text](<06 movimentarestoque.png>)
- Movimentação de entrada e saída
![alt text](<07 entradasaida.png>)

# Sistema de Estoque (`eestoque`)

Este é um sistema de gerenciamento de estoque desenvolvido em PHP. O projeto foi estruturado para separar a lógica de configuração, banco de dados e as telas públicas acessíveis ao usuário.

---

## Estrutura de Pastas e Arquivos

Abaixo está a representação da estrutura de diretórios do projeto para ajudar na navegação:

```text
eestoque/
├── config/
│   └── init.php
├── database/
│   └── database.php
├── public/
│   ├── categorias.php
│   ├── categoriaeditar.php
│   ├── categorianovo.php
│   ├── dashboard.php
│   ├── fornecedor.php
│   ├── fornecedoreditar.php
│   ├── fornecedornovo.php
│   ├── index.php
│   ├── login.php
│   ├── logout.php
│   ├── movimentacao.php
│   ├── movimentacaocontrole.php
│   ├── produto.php
│   ├── produtoeditar.php
│   ├── produtonovo.php
│   ├── relatorio.php
│   ├── usuario.php
│   ├── usuarioeditar.php
│   └── usuarionovo.php
└── index.php

## Explica da estrutura acima

- Index.php (raiz): Ponto de entrada principal do projeto (geralmente faz o redirecionamento para a pasta pública).

- Config/init.php: Arquivo de inicialização do sistema (definição de constantes, caminhos locais e configurações globais).

- Database/database.php: Arquivo responsável pela conexão com o Banco de Dados (PDO/MySQLi).

### Pasta public, interface do usuário

Esta pasta contém todas as páginas visíveis e interativas do sistema, divididas por módulos:

- Autenticação: login.php e logout.php.

- Painel Principal: dashboard.php (visão geral dos dados) e index.php (página inicial pública).

- Produtos: produto.php (listagem), produtonovo.php (cadastro) e produtoeditar.php (edição).

- Categorias: categorias.php, categorianovo.php e categoriaeditar.php.

- Fornecedores: fornecedor.php, fornecedornovo.php e fornecedoreditar.php.

- Usuários: usuario.php, usuarionovo.php e usuarioeditar.php.

- Movimentações (Entradas/Saídas): movimentacao.php e movimentacaocontrole.php.

- Relatórios: relatorio.php (geração de relatórios estatísticos ou impressões).

## Instalação

### 1. Clonar projeto
- https://github.com/claucianoweber-max/Programa-Controle-estoque

### 2. Criar banco dados (de um nome e senha)
- Criar 1 tabela categorias
- sql
- CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

- Criar 1 tabela fornecedores
- sql
- CREATE TABLE fornecedores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    contato VARCHAR(100)
);

- Criar 1 tabela movimentacoes
- sql
- CREATE TABLE movimentacoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    produto_id INT NOT NULL,
    tipo ENUM('entrada', 'saida') NOT NULL,
    quantidade INT NOT NULL,
    data TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    usuario_id INT NOT NULL,
    FOREIGN KEY (produto_id) REFERENCES itens(id)
);

- Criar 1 tabela produtos
- sql
- CREATE TABLE produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    categoria_id INT,
    fornecedor_id INT,
    quantidade INT DEFAULT 0,
    preco DECIMAL(10, 2) NOT NULL,
    descricao TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id),
    FOREIGN KEY (fornecedor_id) REFERENCES fornecedores(id)
);

- Criar 1 tabela usuarios
- sql
- CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nomeusuario VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL
);


### 3. Importar arquivo .sql
- Após criar o banco e as tabelas com as informações acima, importar o nome.sql

### 4. Configurar conexão
- Database/database.php: Arquivo responsável pela conexão com o Banco de Dados (PDO/MySQLi).
- Exemplo
- Substitua estes valores nos códigos abaixo com os dados do seu ambiente:
 - Host: localhost (ou o IP do seu servidor)
 - User: root (seu usuário do banco)
 - Password: sua_senhaDatabase:
 - nome_do_seu_banco

### 5. Executar sistema

    - acessar o sistema com as seguintes credenciais
    Login
    -usuario@exemplo.com
    Senha
    -1223456

## Link público
    Para teste em tempo real
    - http://179.48.102.130:8382/eestoque/public/login.php

## Autor

Clauciano Dias dos Santos Weber
=======
# Site-Estoque
Site para controle de Estoque
>>>>>>> ab6d4d9 (Initial commit)
