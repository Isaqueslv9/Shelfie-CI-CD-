# 📚 Shelfie — Containerização com Docker

Projeto pessoal de estudo com foco em containerização de uma aplicação PHP/MySQL utilizando Docker puro.  
A aplicação é um gerenciador de estante de livros com autenticação, CRUD completo e estatísticas de leitura.

---

## 🎯 Objetivo

Consolidar conhecimentos de Docker aplicados a uma aplicação real, cobrindo:
- Dockerfile com boas práticas
- Docker Compose com múltiplos serviços
- Networking entre containers
- Volumes para persistência de dados
- Variáveis de ambiente
- Healthcheck e ordem de inicialização

---

## 🏗️ Arquitetura

```
┌─────────────┐       ┌─────────────┐       ┌─────────────┐
│    Nginx     │──────▶│  PHP-FPM    │──────▶│    MySQL    │
│  (proxy)    │       │   (app)     │       │    (db)     │
│   porta 80  │       │  porta 9000 │       │  porta 3306 │
└─────────────┘       └─────────────┘       └─────────────┘
       │                     │                     │
       └─────────────────────┴─────────────────────┘
                        shelfie-network
```

---

## 🛠️ Stack

| Tecnologia | Versão | Função |
|---|---|---|
| PHP-FPM | 8.2 Alpine | Aplicação |
| MySQL | 9.7.0 | Banco de dados |
| Nginx | Alpine | Proxy reverso |
| Docker Compose | 3.8 | Orquestração |

---

## 📁 Estrutura do Projeto

```
shelfie-mysql/
├── .env                        # Variáveis de ambiente (não versionado)
├── .gitignore
├── docker-compose.yml
├── mysql-init/
│   └── init.sql                # Schema e seed do banco
├── nginx/
│   └── default.conf            # Configuração do Nginx
└── app/
    ├── Dockerfile
    ├── php.ini                 # Configuração customizada do PHP
    ├── conexao.php             # Conexão PDO via variáveis de ambiente
    ├── index.php               # Dashboard
    ├── login.php               # Autenticação
    ├── logout.php
    ├── meus_livros.php         # Listagem com busca, filtro e paginação
    ├── adicionar_livro.php
    ├── editar_livro.php
    ├── estatisticas.php        # Gráficos com Chart.js
    ├── perfil.php
    ├── processa_exclusao.php
    ├── processa_favorito.php
    ├── css/
    ├── js/
    └── templates/
        ├── header.php
        └── footer.php
```

---

## 🚀 Como rodar

### Pré-requisitos
- Docker
- Docker Compose

### 1. Clone o repositório
```bash
git clone <url-do-repo>
cd shelfie-mysql
```

### 2. Configure o `.env`
```bash
cp .env.example .env
```

Edite o `.env` com suas credenciais:
```env
DB_HOST=db
DB_NAME=shelfie
DB_USER=shelfie_user
DB_PASS=shelfie123
MYSQL_ROOT_PASSWORD=root123
MYSQL_DATABASE=shelfie
MYSQL_USER=shelfie_user
MYSQL_PASSWORD=shelfie123
```

### 3. Suba os containers
```bash
docker compose up --build
```

### 4. Acesse a aplicação
```
http://localhost
```

---

## 🔒 Boas práticas aplicadas

- **Least privilege** — container `app` roda com usuário `appuser`, não root
- **Variáveis de ambiente** — credenciais fora do código via `.env`
- **Healthcheck** — MySQL verifica saúde antes do PHP tentar conectar
- **depends_on com condition** — ordem de inicialização garantida
- **Porta do banco não exposta** — MySQL acessível apenas via rede interna
- **Volume nomeado** — dados do MySQL persistem entre restarts
- **Output buffering** — `ob_start()` no header para evitar problemas com redirects

---

## 📋 Comandos úteis

```bash
# Subir os containers
docker compose up --build

# Subir em background
docker compose up -d

# Derrubar os containers
docker compose down

# Derrubar e apagar volumes (reset completo)
docker compose down -v

# Ver logs de um serviço
docker logs shelfie-app
docker logs shelfie-mysql
docker logs shelfie-nginx

# Acessar o banco via CLI
docker exec -it shelfie-mysql mysql -u shelfie_user -pshelfie123 shelfie

# Verificar containers rodando
docker ps
```

---

## 🗺️ Roadmap

- [x] **Fase 1** — Containerização com Docker
- [ ] **Fase 2** — Push da imagem para o Amazon ECR
- [ ] **Fase 3** — Deploy no Amazon ECS (Fargate)
- [ ] **Fase 4** — Banco de dados no Amazon RDS MySQL
- [ ] **Fase 5** — Pipeline CI/CD com GitHub Actions
- [ ] **Fase 6** — Validação do deploy automatizado end-to-end

Foco atual: trilha DevOps (Docker → ECR/ECS → CI/CD → IaC)

---


