---
title: "Git Flow explicado de forma simples"
slug: "git-flow-explicado-de-forma-simples"
translation_key: "git-flow-explicado-de-forma-simples"
description: "Entenda Git Flow, branches, feature, develop, release, hotfix, PRs e como esse fluxo funciona na prática em empresas."
date: "2026-05-08"
draft: false
tags:
    - Git
    - Git Flow
    - CI/CD
    - Backend
    - DevOps
    - Versionamento
---

# Git Flow explicado de forma simples

Git Flow é uma estratégia de organização de branches usada para manter o desenvolvimento de software mais seguro, organizado e previsível.

A ideia principal é:

- manter a produção estável
- permitir desenvolvimento paralelo
- organizar releases
- separar correções urgentes
- facilitar trabalho em equipe

---

# O problema que o Git Flow resolve

Imagine 10 desenvolvedores trabalhando no mesmo sistema:

- um altera login
- outro mexe em pagamento
- outro altera notificações
- outro faz integração com API

Sem organização:
🔥 caos total

Com Git Flow:
✅ cada desenvolvedor trabalha isoladamente

---

# Estrutura principal do Git Flow

## Branch `main` (ou master)

A branch `main` representa o código em produção.

Tudo que está nela:

- já foi testado
- está estável
- pode ser entregue ao cliente

### Regras comuns

- ninguém desenvolve direto nela
- geralmente possui deploy automatizado
- normalmente protegida contra push direto

---

## Branch `develop`

A branch `develop` é a base de desenvolvimento.

Ela funciona como um ambiente onde:

- novas funcionalidades são integradas
- testes acontecem
- QA valida features

Pense assim:

```text
main     = produção
develop  = construção do sistema
```

---

# Tipos de branches

## `feature/*`

Usada para novas funcionalidades.

Exemplo:

```bash
git checkout develop
git checkout -b feature/login
```

Agora o desenvolvedor pode trabalhar isoladamente sem impactar outras pessoas do time.

### Exemplos

```text
feature/login
feature/payment
feature/dashboard
feature/notifications
```

---

## `release/*`

Usada para preparar uma nova versão para produção.

Exemplo:

```bash
git checkout -b release/1.0 develop
```

Essa branch serve para:

- validações finais
- testes
- QA
- pequenos ajustes
- preparação do deploy

---

## `hotfix/*`

Usada para correções urgentes em produção.

Exemplo:

```bash
git checkout -b hotfix/corrigir-pix main
```

Imagine:

- cliente não consegue pagar
- API caiu
- login quebrou

Você não espera toda develop ficar pronta.

O hotfix sai direto da produção atual.

---

# Fluxo visual simples

```text
main
 ├── develop
 │     ├── feature/login
 │     ├── feature/payment
 │     └── feature/dashboard
 │
 ├── release/1.0
 │
 └── hotfix/corrigir-pix
```

---

# Fluxo completo na prática

# 1. Criar feature

O desenvolvedor cria uma branch:

```bash
git checkout develop
git checkout -b feature/login
```

---

# 2. Desenvolver funcionalidade

O código é feito isoladamente.

Depois:

```bash
git add .
git commit -m "feat: adiciona login"
git push origin feature/login
```

---

# 3. Abrir Pull Request

Agora o desenvolvedor abre um PR.

O Pull Request serve para:

- revisão de código
- validação técnica
- testes
- discussão
- garantir qualidade

---

# 4. Merge na develop

Após aprovação:

```text
feature/login -> develop
```

Agora a funcionalidade entra no ambiente de homologação/desenvolvimento.

---

# 5. Criar release

Quando várias features estão prontas:

```bash
git checkout -b release/1.0 develop
```

---

# 6. Merge na produção

Depois dos testes:

```text
release/1.0 -> main
```

E normalmente também:

```text
release/1.0 -> develop
```

Para manter tudo sincronizado.

---

# Hotfix na prática

Imagine:

🔥 Bug crítico em produção

Cliente não consegue finalizar compra.

Fluxo:

```bash
git checkout main
git checkout -b hotfix/fix-payment
```

Depois:

```text
hotfix -> main
hotfix -> develop
```

Assim:

- produção é corrigida rapidamente
- develop também recebe a correção

---

# Como isso funciona em empresas reais

Muitas empresas usam uma adaptação do Git Flow.

Exemplo comum:

```text
main/master -> produção
develop     -> homologação
feature/*   -> desenvolvimento individual
```

Fluxo comum:

```text
feature -> PR -> code review -> merge -> deploy automatizado
```

---

# Como CI/CD entra nisso

Toda vez que:

- você abre PR
- faz merge
- envia código

uma pipeline pode rodar automaticamente.

---

# O que a pipeline faz

Exemplos:

- rodar testes
- validar lint
- buildar aplicação
- criar imagem Docker
- publicar container
- executar deploy
- rodar migrations

---

# Exemplo real com Docker

Imagine uma aplicação Laravel ou NestJS.

A pipeline pode executar:

```bash
docker build -t minha-api .
```

Depois:

- envia imagem para registry
- atualiza ECS/Kubernetes/EC2
- sobe nova versão automaticamente

---

# Explicando Git Flow como um restaurante 🍔

## `main`

É o prato servido ao cliente.

Precisa estar perfeito.

---

## `develop`

É a cozinha principal.

---

## `feature/*`

É um cozinheiro preparando algo novo.

---

## `release/*`

É o prato indo para inspeção final.

---

## `hotfix/*`

Cliente reclamou urgente:

> "O pedido veio errado"

Você corrige imediatamente sem parar toda a cozinha.

---

# Problemas do Git Flow

Embora muito usado, ele possui alguns desafios:

- branches longas
- conflitos frequentes
- merges complexos
- deploys mais lentos

Por isso algumas empresas usam:

- GitHub Flow
- GitLab Flow
- Trunk Based Development

Mesmo assim, Git Flow ainda é muito comum em:

- sistemas corporativos
- SaaS
- times grandes
- empresas com QA

---

# Termos importantes que recrutadores gostam de ouvir

- Pull Request
- Code Review
- CI/CD
- Pipeline
- Deploy automatizado
- Homologação
- Ambiente de staging
- Rollback
- Feature branch
- Versionamento

---

# Comandos mais usados

## Criar branch

```bash
git checkout -b feature/login
```

---

## Trocar branch

```bash
git checkout develop
```

---

## Atualizar branch

```bash
git pull origin develop
```

---

## Enviar código

```bash
git push origin feature/login
```

---

## Fazer merge

```bash
git merge feature/login
```

---

# Próximos assuntos para estudar

Depois de entender Git Flow, os próximos níveis importantes são:

- Pull Requests na prática
- Resolução de conflitos
- Rebase vs Merge
- GitHub Actions
- GitLab CI
- Docker + CI/CD
- Deploy automatizado
- Estratégias de rollback
- Feature flags
- Versionamento semântico

---

# Conclusão

Git Flow é uma estratégia que ajuda times a:

- organizar desenvolvimento
- evitar conflitos
- proteger produção
- entregar software com mais segurança

Mesmo com novas estratégias surgindo, ele continua extremamente relevante no mercado e muito presente em empresas reais.
