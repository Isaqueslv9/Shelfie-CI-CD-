# 📚 Shelfie — Deploy Completo com CI/CD

Projeto pessoal de estudo com foco em containerização, deploy em nuvem e automação com CI/CD.  
A aplicação é um gerenciador de estante de livros com autenticação, CRUD completo e estatísticas de leitura.

---

## 🛠️ Stack

| Tecnologia | Função |
|---|---|
| PHP 8.2 | Aplicação |
| MySQL 9.7 | Banco de dados |
| Nginx | Proxy reverso |
| Docker & Docker Compose | Containerização e orquestração |

---

## 📁 Estrutura do Projeto

```
shelfie-mysql/
├── .env                        
├── .gitignore
├── docker-compose.yml
├── mysql-init/
│   └── init.sql                
├── nginx/
│   └── default.conf            
└── app/
    ├── Dockerfile
    ├── php.ini
    ├── conexao.php
    ├── index.php
    ├── login.php
    ├── logout.php
    ├── meus_livros.php
    ├── adicionar_livro.php
    ├── editar_livro.php
    ├── estatisticas.php
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

## 🐳 Fase 1 — Containerização com Docker

A aplicação originalmente rodava em XAMPP com phpMyAdmin. O objetivo desta fase foi containerizá-la do zero utilizando Docker puro.

### Arquitetura

```
┌─────────────┐       ┌─────────────┐       ┌─────────────┐
│    Nginx     │──────▶│  PHP-FPM    │──────▶│    MySQL    │
│  (proxy)    │       │   (app)     │       │    (db)     │
│   porta 80  │       │  porta 9000 │       │  porta 3306 │
└─────────────┘       └─────────────┘       └─────────────┘
                        shelfie-network
```

### O que foi feito

- Dockerfile com imagem `php:8.2-fpm-alpine`, instalação de `pdo_mysql`, usuário não-root (`appuser`) com least privilege e healthcheck via `php-fpm -t`
- Docker Compose orquestrando 3 serviços: `nginx`, `app` e `db`
- Rede interna `shelfie-network` isolando os containers
- Volume nomeado `db_data` para persistência do MySQL
- Healthcheck no MySQL com `mysqladmin ping` e `start_period` para evitar race condition na inicialização
- `depends_on` com `condition: service_healthy` garantindo ordem de subida: `db → app → nginx`
- Variáveis de ambiente via `.env` — credenciais fora do código
- Porta do banco não exposta externamente
- `ob_start()` no header PHP para evitar conflito de headers com redirects
- Schema e seed do banco via `init.sql` executado automaticamente na inicialização do container

### Como rodar

```bash
# Clonar e entrar na pasta
git clone <url-do-repo>
cd shelfie-mysql

# Configurar variáveis de ambiente
cp .env.example .env

# Subir os containers
docker compose up --build

# Acessar
http://localhost
```

### Comandos úteis

```bash
# Parar os containers mantendo os dados
docker compose down

# Reset completo (apaga volumes)
docker compose down -v

# Ver logs
docker logs shelfie-app
docker logs shelfie-mysql

# Acessar o banco
docker exec -it shelfie-mysql mysql -u shelfie_user -pshelfie123 shelfie
```

---

## 🗺️ Roadmap

- [x] Fase 1 — Containerização com Docker
- [ ] Fase 2 — Amazon ECR
- [ ] Fase 3 — Amazon ECS
- [ ] Fase 4 — Amazon RDS
- [ ] Fase 5 — CI/CD com GitHub Actions

---


# Shelfie-CI-CD-
