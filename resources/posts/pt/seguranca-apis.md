---
title: "Segurança em APIs REST: o que realmente protege sua aplicação em produção"
slug: "seguranca-em-apis-rest-nodejs-laravel"
translation_key: "seguranca-apis-rest-node-laravel"
description: "Entenda na prática como proteger APIs REST usando Node.js, NestJS e Laravel: autenticação, autorização, validação, rate limiting, logs seguros, CORS, HTTPS e segurança no CI/CD."
date: "2026-05-03"
draft: false
tags:
    - seguranca
    - api
    - rest
    - backend
    - nodejs
    - nestjs
    - php
    - laravel
    - jwt
    - oauth
    - ci-cd
---

# Segurança em APIs REST: o que realmente protege sua aplicação em produção

Muita gente acha que segurança de API significa apenas colocar JWT e pronto.

Na prática, segurança em APIs REST é construída em camadas.

Uma API segura precisa:

- validar quem está acessando
- controlar o que cada usuário pode fazer
- impedir abuso
- evitar vazamento de dados
- validar entradas
- esconder detalhes internos
- automatizar verificações no CI/CD

E normalmente os problemas aparecem justamente quando isso é ignorado e o sistema já está em produção.

Neste artigo vamos aprofundar os principais pilares de segurança em APIs REST usando exemplos com:

- Node.js
- NestJS
- PHP
- Laravel

---

# O que é segurança em APIs?

Pense na API como a porta de entrada do sistema.

Tudo passa por ela:

- login
- pagamentos
- upload de arquivos
- integrações externas
- dados financeiros
- dados pessoais

Se a API estiver vulnerável, o sistema inteiro está vulnerável.

Por isso segurança precisa ser pensada desde o desenvolvimento.

---

# 1. Autenticação: quem é o usuário?

Autenticação é o processo de validar identidade.

É aqui que entram:

- JWT
- OAuth2
- OpenID Connect
- sessões
- access tokens
- refresh tokens

O objetivo é garantir que a API saiba exatamente quem está fazendo a requisição.

## Fluxo comum usando JWT

1. usuário faz login
2. backend valida credenciais
3. backend gera token JWT
4. frontend envia o token nas próximas requisições

Exemplo:

```http
Authorization: Bearer eyJhbGciOi...
```

---

# Exemplo no NestJS

```ts
@Injectable()
export class JwtStrategy extends PassportStrategy(Strategy) {
    constructor() {
        super({
            jwtFromRequest: ExtractJwt.fromAuthHeaderAsBearerToken(),
            secretOrKey: process.env.JWT_SECRET,
        });
    }

    async validate(payload: any) {
        return {
            userId: payload.sub,
            email: payload.email,
        };
    }
}
```

Protegendo rota:

```ts
@UseGuards(JwtAuthGuard)
@Get('profile')
getProfile(@Req() req) {
  return req.user;
}
```

---

# Exemplo no Laravel

Usando Sanctum:

```php
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', function (Request $request) {
        return $request->user();
    });
});
```

---

# Boas práticas importantes

## Tokens curtos

Evite tokens eternos.

Ideal:

- access token curto
- refresh token separado

---

## Nunca armazenar segredo no código

Errado:

```ts
secretOrKey: "123456";
```

Certo:

```env
JWT_SECRET=minha_chave_super_secreta
```

---

# 2. Autorização: o usuário pode fazer isso?

Aqui está um dos maiores erros em APIs.

O usuário está autenticado.

Mas ele realmente pode acessar aquele recurso?

---

# Problema clássico

Imagine:

```http
GET /expenses/10
```

O usuário altera manualmente:

```http
GET /expenses/11
```

Se sua API não validar ownership, ele acessa dados de outra pessoa.

---

# Exemplo inseguro

```php
$expense = Expense::find($id);

return $expense;
```

---

# Exemplo correto

```php
$expense = Expense::where('id', $id)
    ->where('user_id', auth()->id())
    ->firstOrFail();
```

---

# Exemplo no NestJS

```ts
const company = await this.companyRepository.findOne({
    where: {
        id: companyId,
        userId: currentUser.id,
    },
});

if (!company) {
    throw new NotFoundException();
}
```

---

# Regra importante

Nunca confie:

- no ID vindo da URL
- no frontend
- no client

Toda autorização deve ser validada no backend.

---

# 3. Validação de entrada

Toda entrada do usuário é potencialmente perigosa.

A API deve validar:

- tipo
- tamanho
- formato
- campos obrigatórios
- payload inesperado

---

# Exemplo perigoso

```ts
createUser(body) {
  return this.userRepository.save(body);
}
```

Isso permite salvar qualquer campo enviado.

---

# Exemplo correto no NestJS

```ts
export class CreateUserDto {
    @IsString()
    @MinLength(3)
    name: string;

    @IsEmail()
    email: string;

    @IsString()
    @MinLength(8)
    password: string;
}
```

Ativando validação global:

```ts
app.useGlobalPipes(
    new ValidationPipe({
        whitelist: true,
        forbidNonWhitelisted: true,
    }),
);
```

---

# Exemplo no Laravel

```php
$request->validate([
    'name' => ['required', 'string', 'max:255'],
    'email' => ['required', 'email'],
    'password' => ['required', 'min:8'],
]);
```

Ou usando FormRequest:

```php
class StoreExpenseRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:120'],
            'amount' => ['required', 'numeric'],
        ];
    }
}
```

---

# 4. Proteção contra SQL Injection

SQL Injection acontece quando entrada do usuário é concatenada diretamente na query.

---

# Exemplo inseguro

```php
$user = DB::select("SELECT * FROM users WHERE email = '$email'");
```

---

# Exemplo seguro

```php
$user = DB::table('users')
    ->where('email', $email)
    ->first();
```

---

# Exemplo no TypeORM

```ts
const user = await this.userRepository.findOne({
    where: { email },
});
```

Ou:

```ts
const user = await this.userRepository
    .createQueryBuilder("user")
    .where("user.email = :email", { email })
    .getOne();
```

---

# Regra de ouro

Nunca monte SQL concatenando string manualmente.

---

# 5. Rate Limiting

Rate limit protege contra:

- brute force
- spam
- abuso
- sobrecarga

Principalmente em:

- login
- recuperação de senha
- endpoints públicos

---

# Exemplo no NestJS

Instalação:

```bash
npm install @nestjs/throttler
```

Configuração:

```ts
ThrottlerModule.forRoot([
    {
        ttl: 60000,
        limit: 10,
    },
]);
```

---

# Exemplo no Laravel

```php
Route::middleware('throttle:10,1')
    ->post('/login', [AuthController::class, 'login']);
```

Isso limita:

- 10 requisições
- por minuto

---

# 6. Logs seguros

Logs são fundamentais.

Mas logs também podem vazar informações sensíveis.

---

# Nunca logue

- senha
- token JWT
- CPF
- cartão
- Authorization header
- refresh token

---

# Exemplo ruim

```ts
console.log(req.body);
```

---

# Melhor abordagem

```ts
this.logger.log({
    action: "login_attempt",
    email: body.email,
    ip: req.ip,
});
```

---

# Exemplo Laravel

```php
Log::info('Login attempt', [
    'email' => $request->email,
    'ip' => $request->ip(),
]);
```

---

# 7. CORS

CORS define quais frontends podem consumir sua API.

---

# Configuração insegura

```ts
app.enableCors({
    origin: "*",
});
```

---

# Configuração correta

```ts
app.enableCors({
    origin: ["https://meusite.com"],
    credentials: true,
});
```

---

# Laravel

```php
'allowed_origins' => [
    'https://meusite.com',
],
```

---

# 8. Tratamento seguro de erros

Usuário não deve ver:

- stack trace
- caminho interno
- SQL
- estrutura do backend

---

# Exemplo perigoso

```json
{
    "file": "/var/www/app/UserService.php",
    "line": 88,
    "message": "SQLSTATE..."
}
```

---

# Exemplo correto

```json
{
    "message": "Erro interno do servidor"
}
```

---

# Laravel produção

```env
APP_DEBUG=false
APP_ENV=production
```

---

# Exception Filter no NestJS

```ts
@Catch()
export class GlobalExceptionFilter implements ExceptionFilter {
    catch(exception: unknown, host: ArgumentsHost) {
        const response = host.switchToHttp().getResponse();

        response.status(500).json({
            statusCode: 500,
            message: "Erro interno do servidor",
        });
    }
}
```

---

# 9. Dependências atualizadas

Muitas vulnerabilidades vêm de bibliotecas antigas.

---

# Node.js

```bash
npm audit
npm audit fix
```

---

# PHP

```bash
composer audit
composer update
```

---

# Ferramentas comuns

- Dependabot
- Snyk
- SonarQube
- GitHub Security Alerts
- OWASP ZAP

---

# 10. Segurança no CI/CD

Segurança não deve depender apenas do desenvolvedor lembrar manualmente.

Ela precisa estar automatizada.

---

# Exemplo simples de pipeline

```yaml
- name: Node security audit
  run: npm audit --audit-level=high

- name: PHP security audit
  run: composer audit
```

---

# Testes de autorização

Exemplo Laravel:

```php
public function test_user_cannot_access_other_user_expense()
{
    $userA = User::factory()->create();
    $userB = User::factory()->create();

    $expense = Expense::factory()->create([
        'user_id' => $userB->id,
    ]);

    $this->actingAs($userA)
        ->getJson("/api/expenses/{$expense->id}")
        ->assertStatus(404);
}
```

---

# Checklist prático para APIs Node.js

## Em NestJS

- JWT Guard
- Roles Guard
- ValidationPipe
- DTOs
- Helmet
- Rate limiting
- CORS restrito
- Exception Filter
- Logs sanitizados
- npm audit no CI/CD

---

# Checklist prático para APIs Laravel

## Em Laravel

- Sanctum ou Passport
- Policies/Gates
- FormRequest
- Eloquent seguro
- Throttle middleware
- APP_DEBUG=false
- CORS restrito
- Logs sem dados sensíveis
- composer audit
- Feature tests

---

# Conclusão

Segurança em APIs REST não é uma funcionalidade isolada.

É uma combinação de:

- autenticação
- autorização
- validação
- observabilidade
- proteção contra abuso
- tratamento seguro de erros
- automação no CI/CD

Quanto antes essas práticas forem aplicadas, menor o risco de problemas em produção.

E na prática profissional, a maior parte dos incidentes acontece justamente por falhas simples:

- endpoint sem autorização
- log vazando token
- CORS aberto
- APP_DEBUG ligado
- ausência de rate limit
- validação fraca

No fim, segurança não é apenas proteger infraestrutura.

É proteger regras de negócio, dados e usuários.
