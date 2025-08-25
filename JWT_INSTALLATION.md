# Instalação do Pacote JWT

Para que o endpoint de autenticação funcione corretamente, é necessário instalar o pacote JWT do Firebase.

## Comando de Instalação

Execute o seguinte comando no diretório `src/`:

```bash
composer require firebase/php-jwt
```

## Configuração do Ambiente

Adicione a seguinte variável de ambiente no arquivo `.env`:

```env
JWT_SECRET=sua_chave_secreta_muito_segura_aqui
```

## Alternativa sem Variável de Ambiente

Se não configurar a variável `JWT_SECRET`, o sistema usará uma chave padrão (`default_secret_key`), mas isso não é recomendado para produção.

## Estrutura do Token JWT

O token gerado contém:
- **iss**: Emissor (ecommerce-api)
- **aud**: Audiência (ecommerce-users)
- **iat**: Data de criação
- **exp**: Data de expiração (24 horas)
- **user_id**: ID do usuário
- **login**: Login do usuário

## Segurança

- O token expira em 24 horas
- Use uma chave secreta forte em produção
- Considere implementar refresh tokens para melhor segurança
