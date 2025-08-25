# E-commerce API - Venda e Troca

API RESTful para sistema de e-commerce com funcionalidades de venda, troca, lances, mensagens e entregas.

## 🚀 Tecnologias

- **Backend**: CodeIgniter 4
- **Database**: MySQL
- **Containerização**: Docker & Docker Compose
- **Autenticação**: JWT (JSON Web Tokens)
- **Validação**: CodeIgniter Validation Service

## 📋 Pré-requisitos

- Docker
- Docker Compose
- Git

## 🏗️ Estrutura do Projeto

```
ecommerce-venda-troca/
├── src/                          # Código fonte da aplicação
│   ├── app/
│   │   ├── Controllers/Api/     # Controllers da API
│   │   ├── Models/              # Modelos de dados
│   │   ├── Database/
│   │   │   ├── Migrations/      # Migrações do banco
│   │   │   └── Seeds/           # Seeders para dados iniciais
│   │   └── Config/              # Configurações
│   └── public/                  # Arquivos públicos
├── docker-compose.yaml          # Configuração Docker
├── Dockerfile                   # Imagem Docker
├── postman_collection.json      # Collection do Postman
└── README.md                    # Este arquivo
```

## 🐳 Executando com Docker

### 1. Clone o repositório
```bash
git clone <url-do-repositorio>
cd ecommerce-venda-troca
```


### 2. Inicie os containers
```bash
docker-compose up -d
```

### 3. Verifique se os containers estão rodando
```bash
docker-compose ps
```

## 🗄️ Configuração do Banco de Dados

### 1. Execute as migrations
```bash
docker-compose exec ecommerce php spark migrate
```

### 2. Execute os seeds para criar dados iniciais
```bash
docker-compose exec ecommerce php spark db:seed MainSeeder
```

## 👤 Usuário Padrão Criado

Após executar o seeder, você terá um usuário padrão:

- **Login**: `admin`
- **Senha**: `123456`
- **Email**: `admin@exemplo.com`
- **Localização**: São Paulo, SP (Av. Paulista, 1000)

## 🔐 Autenticação

### 1. Obter Token JWT
```http
POST http://localhost:8098/authenticate
Content-Type: application/json

{
    "login": "admin",
    "password": "123456"
}
```

### 2. Usar o Token
```http
Authorization: Bearer <seu_token_jwt>
```

## 📱 Testando com Postman

### 1. Importe a Collection
- Abra o Postman
- Clique em "Import"
- Selecione o arquivo `Vibbra-ecoomerce.postman_collection` da raiz do projeto

### 2. Execute o Login
- Use a requisição "Auth" da collection
- O token será automaticamente salvo na variável `token`

### 3. Teste os Endpoints
Todos os outros endpoints da collection usarão automaticamente o token de autenticação.

## 🌐 Endpoints da API

### Autenticação (Públicos)
- `POST /authenticate` - Login tradicional
- `POST /authenticate/sso` - Login SSO

### Usuários (Protegidos)
- `POST /user` - Criar usuário
- `GET /user/{ID}` - Buscar usuário
- `PUT /user/{ID}` - Atualizar usuário

### Deals (Protegidos)
- `POST /deal` - Criar deal
- `GET /deal/{ID}` - Buscar deal
- `PUT /deal/{ID}` - Atualizar deal
- `POST /deal/search` - Buscar deals

### Bids (Protegidos)
- `POST /deal/{ID}/bid` - Criar bid
- `GET /deal/{ID}/bid` - Listar bids
- `GET /deal/{ID}/bid/{ID}` - Buscar bid
- `PUT /deal/{ID}/bid/{ID}` - Atualizar bid

### Mensagens (Protegidas)
- `POST /deal/{ID}/message` - Criar mensagem
- `GET /deal/{ID}/message` - Listar mensagens
- `GET /deal/{ID}/message/{ID}` - Buscar mensagem
- `PUT /deal/{ID}/message/{ID}` - Atualizar mensagem

### Deliveries (Protegidos)
- `POST /deal/{ID}/delivery` - Criar delivery
- `GET /deal/{ID}/delivery` - Buscar delivery

### Convites (Protegidos)
- `POST /user/{ID}/invite` - Criar convite
- `GET /user/{ID}/invite` - Listar convites
- `GET /user/{ID}/invite/{ID}` - Buscar convite
- `PUT /user/{ID}/invite/{ID}` - Atualizar convite

## 🛠️ Comandos Úteis

### Docker
```bash
# Iniciar containers
docker-compose up -d

# Parar containers
docker-compose down

# Ver logs
docker-compose logs -f ecommerce

# Acessar container da aplicação
docker-compose exec ecommerce bash
```

### CodeIgniter
```bash
# Executar migrations
docker-compose exec ecommerce php spark migrate

# Executar seeds
docker-compose exec ecommerce php spark db:seed DefaultUserSeeder

# Ver status das migrations
docker-compose exec ecommerce php spark migrate:status

# Reverter última migration
docker-compose exec ecommerce php spark migrate:rollback
```

## 🔧 Solução de Problemas

### Container não inicia
```bash
# Ver logs detalhados
docker-compose logs ecommerce

# Reconstruir containers
docker-compose down
docker-compose build --no-cache
docker-compose up -d
```

### Erro de conexão com banco
```bash
# Aguardar banco inicializar
docker-compose exec ecommerce php spark migrate:status

# Verificar se MySQL está rodando
docker-compose ps mysql
```

### Erro de permissão
```bash
# Ajustar permissões da pasta writable
docker-compose exec ecommerce chmod -R 777 writable/
```

## 📊 Estrutura do Banco

- **locations** - Localizações dos usuários e deals
- **users** - Usuários do sistema
- **deals** - Ofertas de venda/troca
- **deal_photos** - Fotos dos deals
- **bids** - Lances nos deals
- **messages** - Mensagens nos deals
- **deliveries** - Entregas dos deals
- **delivery_steps** - Passos das entregas
- **invites** - Convites entre usuários

## 📞 Suporte

Para dúvidas ou problemas:
- Verifique os logs: `docker-compose logs -f ecommerce`
- Consulte a documentação do CodeIgniter 4
- Verifique se todas as variáveis de ambiente estão configuradas

---

**Desenvolvido com ❤️ usando CodeIgniter 4 e Docker**