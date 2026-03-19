# Sistema de Gestão de Serviços Académicos (SGA)

## Visão Geral
Sistema completo de gestão académica desenvolvido em PHP com arquitetura MVC, desenvolvido para XAMPP.

## Estrutura de Diretórios

```
├── config/                    # Ficheiros de configuração
│   ├── app.php               # Configurações da aplicação
│   └── database.php          # Configurações da base de dados
├── core/                      # Funcionalidades principais
│   ├── bootstrap.php         # Inicialização da aplicação
│   ├── helpers.php           # Funções auxiliares globais
│   ├── session.php           # Gestão de sessões
│   └── Router.php            # Sistema de encaminhamento (routing)
├── controllers/              # Lógica da aplicação
│   ├── ManagerCoursesController.php       # Gestão de cursos
│   ├── ManagerUnitsController.php         # Gestão de UCs
│   ├── ManagerUsersController.php         # Gestão de utilizadores
│   ├── ManagerReportsController.php       # Relatórios
│   ├── StudentRecordController.php        # Fichas de alunos
│   └── StaffGradesController.php          # Pautas de pessoal
├── models/                   # Camada de dados
│   ├── User.php              # Modelo de utilizadores
│   ├── Curso.php             # Modelo de cursos
│   ├── CourseUnit.php        # Modelo de UCs
│   ├── Aluno.php             # Modelo de alunos/fichas
│   ├── GradeSheet.php        # Modelo de pautas
│   ├── StudentRecord.php     # Modelo de fichas de alunos
│   ├── EnrollmentRequest.php # Modelo de inscrições
│   └── Course.php            # Modelo de cursos (alias)
├── views/                    # Camada de apresentação
│   ├── auth/                 # Vistas de autenticação
│   ├── layouts/              # Layouts comuns (navbar, footer)
│   ├── manager/              # Vistas de gestor
│   │   ├── dashboard.php
│   │   ├── courses.php
│   │   ├── courses-form.php
│   │   ├── units.php
│   │   ├── units-form.php
│   │   ├── users.php
│   │   ├── users-form.php
│   │   └── reports.php
│   ├── student/              # Vistas de alunos
│   ├── staff/                # Vistas de pessoal
│   └── shared/               # Vistas partilhadas
├── public/                   # Raiz pública (servidor web)
│   ├── index.php             # Ponto de entrada
│   ├── login.php             # Login
│   ├── logout.php            # Logout
│   ├── dashboard.php         # Dashboard principal
│   ├── manager/              # Páginas do gestor
│   │   ├── courses.php
│   │   ├── units.php
│   │   ├── users.php
│   │   └── reports.php
│   ├── assets/               # CSS, JS, imagens
│   └── uploads/              # Ficheiros enviados
├── routes/                   # Definições de rotas
│   └── web.php              # Rotas da aplicação
├── sql/                      # Scripts de base de dados
│   ├── schema.sql           # Estrutura da BD
│   ├── seeds.sql            # Dados iniciais
│   ├── criar-grupos.sql     # Criação de grupos
│   └── reset-passwords.sql  # Reset de palavras-passe
└── index.php                # Ponto de entrada raiz (XAMPP)
```

## Perfis de Utilizador

### 1. **Aluno (Estudante)**
- Visualizar e editar própria ficha de inscrição
- Ver cursos disponíveis
- Submeter fichas para validação
- Consultar notas e pautas

### 2. **Funcionário (Staff)**
- Gerir pautas de avaliação
- Registar notas dos alunos
- Consultar fichas de alunos
- Gerar relatórios simples

### 3. **Gestor Pedagógico (Manager)**
- Gerir cursos e UCs
- Gerir utilizadores do sistema
- Validar fichas de alunos
- Gerir planos de estudo
- Gerar relatórios completos
- Exportar dados em CSV

## Funcionalidades Implementadas

### Dashboard
- **Aluno**: Visualização da própria ficha e cursos
- **Funcionário**: Acesso a pautas e gestão de notas
- **Gestor**: Visão geral do sistema com estatísticas

### Gestão de Cursos
- Listar cursos ativos
- Criar novo curso
- Editar informações do curso
- Desativar/eliminar curso

### Gestão de UCs (Unidades Curriculares)
- Listar UCs disponíveis
- Criar nova UC com valor ECTS
- Editar UC
- Desativar/eliminar UC

### Gestão de Utilizadores
- Listar utilizadores com filtro por perfil
- Criar novo utilizador (aluno, staff, gestor)
- Editar informações de utilizador
- Alterar palavra-passe
- Desativar utilizador (soft delete)

### Relatórios
- Estatísticas do sistema (total users, cursos, UCs)
- Utilizadores por perfil
- Fichas recentes submetidas
- Validações pendentes
- Exportar dados em CSV (utilizadores, cursos, UCs)

## Como Usar

### 1. Instalação

```bash
# Clonar/extrair projeto na pasta XAMPP
# C:\xampp\htdocs\Aulas PHP\Aula1

# Aceder à aplicação
# http://localhost/Aulas%20PHP/Aula1
```

### 2. Configuração Base de Dados

```bash
# Executar script de criação
# sql/schema.sql - cria tabelas
# sql/seeds.sql - insere dados iniciais
# sql/criar-grupos.sql - cria grupos/permissões
```

### 3. Login

**Utilizador de teste** (definido em seeds.sql):
- Username: `admin`
- Palavra-passe: `password`
- Perfil: `gestor`

## Arquitetura

### MVC Pattern
- **Model**: Classes em `models/` que interagem com BD
- **View**: Templates HTML em `views/` com dados
- **Controller**: Lógica de negócio em `controllers/`

### Segurança
- Autenticação via sessão
- Validação de papel (role-based access)
- Escapar output HTML (XSS protection)
- Prepared statements (SQL injection protection)
- Hash de palavras-passe com bcrypt

### Helpers Globais
- `h()` - Escapar HTML
- `url()` - Gerar URLs da aplicação
- `requireAuth()` - Validar autenticação
- `requireRole()` - Validar perfil
- `setFlash()` / `getFlash()` - Mensagens temporárias
- `alertSuccess()` / `alertError()` - Alertas Bootstrap

## Fluxo de Navegação

```
index.php (raiz) 
  ↓ (redireciona)
public/login.php
  ↓ (autentica)
public/dashboard.php
  ├→ student/dashboard.php (aluno)
  ├→ staff/dashboard.php (funcionário)
  └→ manager/dashboard.php (gestor)
      ├→ manager/courses.php
      ├→ manager/units.php
      ├→ manager/users.php
      └→ manager/reports.php
```

## APIs de Controladores

### ManagerCoursesController
```php
$controller->index()        // Lista cursos
$controller->create()       // Criar novo
$controller->edit($id)      // Editar existente
$controller->delete($id)    // Eliminar
```

### ManagerUnitsController
```php
$controller->index()        // Lista UCs
$controller->create()       // Criar nova
$controller->edit($id)      // Editar existente
$controller->delete($id)    // Eliminar
```

### ManagerUsersController
```php
$controller->index()        // Lista utilizadores
$controller->create()       // Criar novo
$controller->edit($id)      // Editar existente
$controller->deactivate($id)// Desativar
```

### ManagerReportsController
```php
$controller->index()        // Dashboard de relatórios
$controller->export($type)  // Exportar (users, courses, units)
```

## Tecnologias

- **Backend**: PHP 7.4+
- **Frontend**: Bootstrap 5, FontAwesome 6
- **Database**: MySQL/MariaDB
- **Server**: XAMPP Apache

## Permissões

| Funcionalidade | Aluno | Staff | Gestor |
|---|---|---|---|
| Ver Dashboard | ✓ | ✓ | ✓ |
| Gerir Ficha | ✓ | ✓ | ✓ |
| Gerir Pautas | - | ✓ | ✓ |
| Gerir Cursos | - | - | ✓ |
| Gerir UCs | - | - | ✓ |
| Gerir Utilizadores | - | - | ✓ |
| Gerar Relatórios | - | - | ✓ |

## Extensões Futuras

- [ ] Autenticação LDAP/integração com AD
- [ ] API REST
- [ ] Dashboard mais avançado com gráficos
- [ ] Notificações por email
- [ ] Importação de dados em bulk
- [ ] Auditoria de alterações
- [ ] Backup automático
- [ ] Testes unitários

## Suporte

Para questões ou bugs, contactar o desenvolvedor.

---

**Versão**: 1.0.0  
**Última actualização**: Março 2026
