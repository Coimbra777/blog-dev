---
title: "Fundamentos da Tecnologia: o que realmente acontece por baixo dos panos"
slug: "fundamentos-da-tecnologia-como-a-internet-funciona"
translation_key: "fundamentos-tecnologia-internet"
description: "Uma explicação simples e intuitiva sobre internet, DNS, HTTP, TCP, bancos de dados, APIs, cache, filas e tudo que acontece por baixo dos panos quando acessamos um site."
date: "2026-05-01"
draft: false
tags:
    - fundamentos
    - internet
    - redes
    - backend
    - http
    - tcp
    - dns
    - banco-de-dados
    - arquitetura
    - nodejs
    - laravel
    - nestjs
---

# Fundamentos da Tecnologia: o que realmente acontece por baixo dos panos

Se você entender esses fundamentos, frameworks como Laravel, NestJS, Vue.js e até microsserviços começam a fazer muito mais sentido.

Porque no fundo:

> Frameworks são só organizadores.
> A base de tudo continua sendo:
>
> - redes
> - protocolos
> - sistemas operacionais
> - bancos de dados
> - comunicação entre máquinas

Então vamos construir isso do zero, de forma simples e intuitiva.

---

## O que é a internet de verdade?

Imagine o mundo inteiro conectado por:

- fios
- cabos submarinos
- antenas
- roteadores
- servidores
- satélites

A internet é literalmente:

> milhões de computadores conversando entre si.

---

## O que acontece quando você abre um site?

Exemplo:

```txt
google.com
```

Você digita isso no navegador e aperta ENTER.

Parece simples.

Mas por baixo dos panos acontece MUITA coisa.

---

## Visão geral do processo

O fluxo é mais ou menos assim:

```txt
Navegador
↓
DNS
↓
Internet
↓
Roteadores
↓
Servidor
↓
Banco de dados
↓
Servidor responde
↓
Navegador renderiza
```

Agora vamos entender etapa por etapa.

---

## O navegador: “eu quero acessar google.com”

Seu navegador (Chrome, Firefox etc.) fala:

> “Preciso descobrir onde está esse site.”

Mas computadores não entendem:

```txt
google.com
```

Eles entendem algo assim:

```txt
142.250.218.14
```

Isso é um IP.

---

## DNS — o tradutor da internet

O DNS funciona como uma agenda telefônica.

Você sabe o nome:

```txt
google.com
```

Mas precisa descobrir o número:

```txt
IP
```

Então o navegador pergunta:

```txt
Ei DNS, qual o IP do google.com?
```

O DNS responde:

```txt
142.x.x.x
```

Agora o navegador já sabe para onde enviar a requisição.

---

## O que é IP?

IP é o endereço de uma máquina na internet.

Igual endereço de casa:

```txt
Rua X, número Y
```

Na internet seria:

```txt
192.168.0.1
```

Sem IP:

- computadores não se encontram
- não existe comunicação

---

## A viagem da requisição

Agora o computador sabe o IP.

Então ele envia algo como:

```txt
"Olá servidor, me manda o site."
```

Essa mensagem viaja pela internet.

---

## A internet funciona com pacotes

A mensagem não vai inteira.

Ela é quebrada em pequenos pedaços chamados:

# PACOTES

Imagine enviar um livro página por página em vários envelopes.

Internet funciona assim.

---

## Quem leva os pacotes?

Os roteadores.

Eles são como guardas de trânsito da internet.

Eles recebem um pacote e decidem:

```txt
"Pra chegar nesse IP, manda por esse caminho."
```

Então os pacotes passam:

```txt
roteador → roteador → roteador
```

até chegar no servidor.

---

## O que são protocolos?

Protocolos são regras de comunicação.

Igual humanos têm idiomas:

- português
- inglês
- espanhol

Computadores também precisam de regras.

Exemplo:

- como iniciar conversa
- como enviar dados
- como confirmar recebimento

---

## TCP — o protocolo confiável

O TCP garante que:

- os pacotes chegam
- chegam na ordem correta
- nada se perde

Ele funciona tipo:

```txt
Servidor:
"Recebi o pacote 1"

Cliente:
"Ok, enviando o 2"
```

Se algo se perder:

```txt
"Não chegou aqui, envia de novo."
```

---

## HTTP — o protocolo da web

HTTP é o idioma da internet moderna.

Quando você abre um site, o navegador envia algo parecido com:

```http
GET / HTTP/1.1
Host: google.com
```

Isso significa:

```txt
"Oi servidor, quero a página principal."
```

---

## Métodos HTTP

Os principais métodos são:

| Método | Significado  |
| ------ | ------------ |
| GET    | buscar dados |
| POST   | criar        |
| PUT    | atualizar    |
| DELETE | deletar      |

Exemplos:

```http
GET /usuarios
```

→ buscar usuários

```http
POST /usuarios
```

→ criar usuário

---

## O servidor recebe a requisição

Agora entram tecnologias como:

- Node.js
- Laravel
- NestJS
- Nginx
- Apache

O servidor lê algo como:

```txt
GET /usuarios
```

e decide:

```txt
"Qual código deve executar?"
```

---

## O backend é um garçom inteligente

Imagine um restaurante.

Cliente:

```txt
"Quero uma pizza."
```

Garçom:

- anota pedido
- vai na cozinha
- pega resultado
- entrega

Backend é exatamente isso.

---

## E o banco de dados?

O backend normalmente precisa buscar dados.

Então ele conversa com bancos como:

- MySQL
- PostgreSQL
- Redis

---

## Como o banco funciona por baixo dos panos?

Pense em um banco de dados como:

# UMA BIBLIOTECA GIGANTE

Ele precisa:

- guardar dados
- encontrar rápido
- não perder informação
- permitir várias pessoas acessando ao mesmo tempo

---

## O que acontece quando salva um usuário?

Exemplo:

```sql
INSERT INTO users
```

O banco:

1. recebe comando
2. valida
3. organiza na memória
4. escreve no disco
5. atualiza índices
6. confirma sucesso

---

## Índices — o índice do livro

Sem índice:

O banco precisaria olhar:

```txt
linha por linha
```

Com índice:

Ele já sabe onde procurar.

Igual índice de livro:

```txt
Capítulo X → página 92
```

---

## Por que banco de dados é difícil?

Porque milhares de pessoas acessam ao mesmo tempo.

Imagine:

- 10 mil usuários
- atualizando saldo
- comprando
- alterando dados

Sem controle:

> tudo quebraria.

---

## Consistência

Exemplo bancário:

Você transfere:

```txt
R$100
```

O sistema precisa garantir:

- saiu de uma conta
- entrou na outra

Se só metade acontecer:

> caos.

---

## Transações

O banco resolve isso usando:

# TRANSAÇÕES

Ou tudo acontece:

```txt
BEGIN
↓
retira dinheiro
↓
adiciona dinheiro
↓
COMMIT
```

ou nada acontece:

```txt
ROLLBACK
```

---

## ACID — a base dos bancos relacionais

Os bancos famosos seguem:

# ACID

| Letra | Significado  |
| ----- | ------------ |
| A     | Atomicidade  |
| C     | Consistência |
| I     | Isolamento   |
| D     | Durabilidade |

---

## Explicando ACID de forma simples

### Atomicidade

Ou faz tudo, ou não faz nada.

---

### Consistência

Os dados nunca ficam inválidos.

---

### Isolamento

Duas pessoas mexendo ao mesmo tempo não se atrapalham.

---

### Durabilidade

Salvou?

Mesmo sem energia continua salvo.

---

## Problemas de concorrência

Quando várias pessoas acessam dados ao mesmo tempo, surgem problemas.

### Dirty Read

Você vê algo que ainda nem foi confirmado.

---

### Non-repeatable Read

Você lê uma coisa.
Lê de novo.
Mudou.

---

### Phantom Read

Você faz uma busca.
Depois aparecem linhas “fantasmas”.

---

## Níveis de isolamento

O banco escolhe quanto quer proteger os dados.

| Nível            | Performance | Segurança |
| ---------------- | ----------- | --------- |
| Read Uncommitted | alta        | baixa     |
| Read Committed   | média       | boa       |
| Repeatable Read  | menor       | maior     |
| Serializable     | mais lenta  | máxima    |

---

## E os frameworks?

Agora vem a parte interessante.

Frameworks escondem toda essa complexidade.

Quando você faz:

```php
User::create()
```

ou:

```ts
await repository.save();
```

por baixo dos panos acontece:

- HTTP
- TCP
- DNS
- SQL
- memória
- disco
- índices
- transações
- locks

---

## O que é uma API?

API é:

# UM CONTRATO DE COMUNICAÇÃO

Exemplo:

```http
GET /users
```

O frontend sabe:

```txt
"Se eu chamar isso, recebo usuários."
```

---

## Frontend e backend conversando

Fluxo real:

```txt
Vue.js
↓
HTTP
↓
Nginx
↓
Node/Nest/Laravel
↓
Banco
↓
Resposta JSON
↓
Frontend renderiza
```

---

## E WebSocket?

HTTP funciona assim:

```txt
pergunta → resposta → fecha conexão
```

WebSocket funciona assim:

```txt
conexão aberta o tempo todo
```

Por isso serve para:

- chat
- jogos
- notificações
- tempo real

---

## E cache?

Buscar no banco é caro.

Então usamos cache com Redis.

O Redis guarda dados na RAM, que é extremamente rápida.

---

## E filas?

Algumas tarefas demoram:

- enviar email
- gerar PDF
- processar imagens
- IA

Então usamos filas:

- RabbitMQ
- Redis

A aplicação fala:

```txt
"faz isso depois"
```

---

## E cloud?

Cloud é basicamente:

# COMPUTADOR DE OUTRA PESSOA

Exemplos:

- AWS
- Google Cloud
- Azure

Você aluga:

- servidor
- banco
- armazenamento
- cache
- filas

---

## O mais importante

Grandes desenvolvedores entendem:

> frameworks mudam
> fundamentos permanecem

Quem entende:

- rede
- HTTP
- banco de dados
- concorrência
- memória
- arquitetura

consegue aprender qualquer stack.

---

## Como estudar isso de verdade

Uma boa ordem de estudo seria:

1. HTTP profundamente
2. DNS/IP/TCP
3. Linux básico/intermediário
4. SQL + índices + transações
5. Redis
6. Nginx
7. Docker
8. Mensageria
9. Cloud
10. Escalabilidade

---

## O momento em que “a ficha cai”

Quando você conseguir enxergar mentalmente isso:

```txt
Frontend → API → Banco → Cache → Fila
```

e entender:

- cada protocolo
- cada conexão
- cada camada
- cada processo

você começa a pensar como engenheiro de software,
e não apenas como alguém que usa frameworks.
