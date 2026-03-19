# Sistema de Gestão de Serviços Académicos (SGA)

Um sistema web completo para gerenciar processos de ensino superior, incluindo autenticação, gestão de cursos, fichas de aluno, pedidos de matrícula e pautas.

## 🚀 Quick Start

### 1. Configurar Base de Dados

Abra phpMyAdmin e:

```sql
1. Crie uma nova base de dados: aulasphp
2. Selecione a base de dados
3. Vá para a aba "SQL"
4. Cole o conteúdo do ficheiro: /sql/schema.sql
5. Execute
6. Depois cole o conteúdo de: /sql/seeds.sql e execute
```

Ou via terminal MySQL:
```bash
mysql -u root < /sql/schema.sql
mysql -u root aulasphp < /sql/seeds.sql
```

### 2. Aceder à Aplicação

Abra o navegador:
```
http://localhost/Aulas%20PHP/Aula1/public/login.php
```

### 3. Utilizadores de Teste

| Utilizador | Password | Perfil |
|------------|----------|--------|
| joao_silva | password123 | Aluno |
| maria_santos | password123 | Aluno |
| carlos_funcionario | password123 | Funcionário |
| ana_gestor | password123 | Gestor Pedagógico |

## 📁 Estrutura do Projeto

```
/project
│
├── /config              # Configurações
│   ├── database.php    # Conexão PDO
│   └── app.php         # Constantes da app
│
├── /core               # Núcleo
│   ├── bootstrap.php   # Inicialização
│   ├── helpers.php     # Funções úteis
│   ├── session.php     # Sessões
│   └── middleware.php  # Middlewares
│
├── /models             # Modelos (Dados)
│   ├── User.php
│   ├── StudentRecord.php
│   ├── Course.php
│   ├── CourseUnit.php
│   ├── EnrollmentRequest.php
│   └── GradeSheet.php
│
├── /controllers        # Controllers (Lógica) - A implementar
│
├── /views              # Views (HTML)
│   ├── /layouts        # Layouts base
│   ├── /auth           # Autenticação
│   ├── /student        # Vistas do Aluno
│   ├── /staff          # Vistas do Funcionário
│   ├── /manager        # Vistas do Gestor
│   └── /shared         # Vistas partilhadas
│
├── /public             # Arquivos públicos
│   ├── index.php       # Redireção para dashboard
│   ├── login.php       # Login
│   ├── logout.php      # Logout
│   ├── dashboard.php   # Dashboard roteado
│   └── /uploads        # Upload de ficheiros (fotos)
│
├── /sql                # Scripts SQL
│   ├── schema.sql      # Esquema BD
│   └── seeds.sql       # Dados de teste
│
└── /routes             # Roteamento - A implementar
```

## 🔐 Segurança Implementada

- ✅ Autenticação com password_hash (bcrypt)
- ✅ PDO com prepared statements (prevenção SQL injection)
- ✅ Sessões com timeout
- ✅ Middleware de controlo de acesso por perfil
- ✅ CSRF token (a adicionar em formulários)
- ✅ Validação de entrada e output escaping

## 📋 Funcionalidades Implementadas

### Fase 1: Arquitetura ✅
- ✅ Estrutura de pastas profissional
- ✅ Configuração centralizadada
- ✅ Bootstrap de inicialização
- ✅ Helpers e funções úteis
- ✅ Session management

### Fase 2: Base de Dados ✅
- ✅ Schema completo com 11 tabelas
- ✅ Relacionamentos e constraints
- ✅ Views úteis
- ✅ Dados de teste (seeds)

### Fase 3: Autenticação ✅
- ✅ Login seguro com password_hash
- ✅ Sessões
- ✅ Logout
- ✅ Middleware por perfil

### Fase 4: Dashboards ✅
- ✅ Dashboard do Aluno
- ✅ Dashboard do Funcionário
- ✅ Dashboard do Gestor Pedagógico
- ✅ Roteamento por perfil

## 📝 Próximas Fases a Implementar

### Fase 5: Ficha de Aluno
- [ ] Formulário de criação/edição
- [ ] Upload de foto (validação)
- [ ] Submissão
- [ ] Validação pelo Gestor

### Fase 6: Pedidos de Matrícula
- [ ] Criação de pedido
- [ ] Aprovação/Rejeição pelo Funcionário
- [ ] Auditoria

### Fase 7: Gestão Académica
- [ ] CRUD Cursos
- [ ] CRUD UCs
- [ ] Plano de Estudos

### Fase 8: Pautas e Notas
- [ ] Criação de pautas
- [ ] Lançamento de notas
- [ ] Publicação

### Fase 9: Auditoria e Logs
- [ ] Logs de ações
- [ ] Histórico de eventos
- [ ] Relatórios

### Fase 10: Interface Final
- [ ] Melhorias visuais
- [ ] Validações client-side
- [ ] Tratamento de erros

## 🔗 Endpoints (A Completar)

### Públicos
- POST /login.php - Autenticação
- GET /logout.php - Logout

### Aluno
- GET /student/dashboard.php - Dashboard
- GET /student/record.php - Minha ficha
- POST /student/record.php - Salvar ficha
- GET /student/enrollments.php - Meus pedidos
- POST /student/new-enrollment.php - Novo pedido

### Gestor Pedagógico
- GET /manager/dashboard.php - Dashboard
- GET /manager/courses.php - Gerir cursos
- GET /manager/units.php - Gerir UCs
- GET /manager/study-plan.php - Plano de estudos
- GET /manager/record.php?id=X - Ver ficha
- POST /manager/record.php - Aprovar/Rejeitar ficha

### Funcionário
- GET /staff/dashboard.php - Dashboard
- GET /staff/enrollment.php?id=X - Ver pedido
- POST /staff/enrollment.php - Aprovar/Rejeitar
- GET /staff/grade-sheets.php - Pautas
- GET /staff/grade.php?id=X - Editar pauta

## 🧪 Testes

Para testar a aplicação completamente:

1. **Login com diferentes perfis**
   - Aluno: joao_silva / password123
   - Funcionário: carlos_funcionario / password123
   - Gestor: ana_gestor / password123

2. **Verificar Dashboards**
   - Cada perfil tem um dashboard diferente
   - Conteúdo corresponde ao papel

3. **Testar Segurança**
   - Tentar aceder a páginas sem estar autenticado → redireciona para login
   - Aluno não pode aceder a pages de funcionário
   - Timeout de sessão após 1 hora

## 📖 Documentação Técnica

### Padrões Utilizados
- **MVC (Model-View-Controller)** - Separação clara de responsabilidades
- **Dependency Injection** - PDO injetado nos models
- **Bootstrap Pattern** - Inicialização centralizada
- **Helper Functions** - Funções reutilizáveis

### Convenções
- Tabelas em inglês (snake_case)
- Funções em português
- Variáveis em português/inglês conforme contexto
- Comentários em inglês (técnico) ou português (funcional)

## 🆘 Troubleshooting

**Erro: "conexão recusada"**
- Verificar se MySQL está rodando
- Verificar credentials em /config/database.php

**Erro: "tabelas não encontradas"**
- Executar schema.sql e seeds.sql
- Verificar nome da base de dados

**Erro: "acesso negado"**
- Fazer logout e login novamente
- Verificar cookies habilitados

## 💡 Dicas para Desenvolvimento

1. **Usar o modelo de bootstrap**
   ```php
   require_once __DIR__ . '/core/bootstrap.php';
   requireAuth(); // Se precisa estar autenticado
   ```

2. **Usar os models**
   ```php
   require_once __DIR__ . '/models/User.php';
   $user = new User($pdo);
   $data = $user->getById($id);
   ```

3. **Usar helpers**
   ```php
   echo h($variable); // Escape HTML
   requireAuth(); // Check auth
   hasRole('aluno'); // Check role
   ```

## 📞 Suporte

Para questões técnicas ou bugs, registar um issue no repositório.

---

**Versão:** 1.0.0  
**Autor:** Sistema de Gestão Académica - 2025
