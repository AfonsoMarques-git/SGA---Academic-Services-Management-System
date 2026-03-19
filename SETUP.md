# Guia de Instalação - SGA

## Pré-requisitos

- XAMPP instalado (Apache + PHP + MySQL)
- navegador web moderno
- Conhecimento básico de MySQL

## Passos de Instalação

### 1. Coloque os ficheiros no local correto

```
C:\xampp\htdocs\Aulas PHP\Aula1\
```

Certifique-se de que todos os ficheiros estão neste diretório.

### 2. Inicie o XAMPP

1. Abra o painel de controlo do XAMPP
2. Clique em "Start" para Apache
3. Clique em "Start" para MySQL

Deve ver status "Running" em ambos.

### 3. Crie a Base de Dados

#### Método A: Via phpMyAdmin (mais fácil)

1. Abra o navegador: `http://localhost/phpmyadmin`
2. Clique em "Bases de Dados" 
3. Crie uma nova base de dados chamada `aulasphp`
4. Selecione a base de dados criada
5. Clique na aba "SQL"
6. Abra o ficheiro `sql/schema.sql` com um editor de texto
7. Copie todo o conteúdo
8. Cole na janela SQL do phpMyAdmin
9. Clique "Executar"
10. Repita o processo com o ficheiro `sql/seeds.sql`

#### Método B: Via Terminal (avançado)

```bash
# Abra a pasta do projeto em PowerShell ou CMD

# Crie a base de dados e tabelas
mysql -u root < sql/schema.sql

# Insira os dados de teste
mysql -u root aulasphp < sql/seeds.sql
```

### 4. Configure o Ficheiro de Base de Dados (se necessário)

Abra `config/database.php` e verifique:

```php
$host = 'localhost';      // Host do MySQL
$db = 'aulasphp';         // Nome da BD
$user = 'root';           // Utilizador MySQL
$password = '';           // Palavra-passe (vazio por padrão em XAMPP)
```

### 5. Aceda à Aplicação

Abra o navegador e visite:

```
http://localhost/Aulas%20PHP/Aula1
```

Será redirecionado para o login automaticamente.

## Utilizadores de Teste

Após executar `sql/seeds.sql`, estes utilizadores estarão disponíveis:

### Aluno
- **Utilizador**: `joao_silva`
- **Palavra-passe**: `password123`
- **Perfil**: Aluno

### Funcionário
- **Utilizador**: `carlos_funcionario`
- **Palavra-passe**: `password123`
- **Perfil**: Staff/Funcionário

### Gestor Pedagógico
- **Utilizador**: `ana_gestor`
- **Palavra-passe**: `password123`
- **Perfil**: Gestor

## Primeiro Acesso

### Como Gestor

1. Faça login com a conta `ana_gestor`
2. Será redirecionado para o Dashboard de Gestor
3. Pode:
   - ✅ Gerir Cursos
   - ✅ Gerir UCs (Unidades Curriculares)
   - ✅ Gerir Utilizadores
   - ✅ Visualizar Relatórios

### Como Aluno

1. Faça login com a conta `joao_silva`
2. Será redirecionado para Dashboard de Aluno
3. Pode visualizar/editar sua própria ficha

### Como Funcionário

1. Faça login com a conta `carlos_funcionario`
2. Será redirecionado para Dashboard de Staff
3. Pode gerir pautas e notas

## Resolvendo Problemas Comuns

### "Erro de Conexão à Base de Dados"

**Causa**: MySQL não está rodando ou credenciais incorretas

**Solução**:
1. Verifique se MySQL está "Running" no XAMPP Control Panel
2. Confirme as credenciais em `config/database.php`
3. Verifique se a base de dados `aulasphp` existe em phpMyAdmin

### "Erro 404 - Página não encontrada"

**Causa**: Ficheiro não existe ou caminho incorreto

**Solução**:
1. Verifique o URL no navegador
2. Certifique-se de que todos os ficheiros do projeto existem
3. Verifique permissões de leitura dos ficheiros

### "Erro de Autenticação"

**Causa**: Utilizador não existe ou palavra-passe incorreta

**Solução**:
1. Verifique se `sql/seeds.sql` foi executado
2. Faça reset da senha via:
   - phpMyAdmin: Abra tabela `users` e altere registo
   - Ou execute `sql/reset-passwords.sql`

### "Erro 500 - Internal Server Error"

**Causa**: Erro de sintaxe PHP ou configuração

**Solução**:
1. Verifique o log de erros: `C:\xampp\apache\logs\error.log`
2. Certifique-se de que todos os ficheiros têm extensão `.php` correta
3. Verifique sintaxe PHP com: `php -l ficheiro.php`

## Estrutura da Base de Dados

Tabelas principais criadas pelo `schema.sql`:

```
- roles              (Perfis: aluno, funcionário, gestor)
- users              (Utilizadores do sistema)
- courses            (Cursos disponíveis)
- course_units       (UCs/Disciplinas)
- study_plans        (Relação Curso-UC)
- student_records    (Fichas de alunos)
- enrollment_requests (Pedidos de inscrição)
- grade_sheets       (Pautas)
- grades             (Notas individuais)
```

## Desenvolvendo Novas Funcionalidades

### Adicionar um Novo Controlador

1. Crie `controllers/NovoController.php`
2. Estenda a lógica de negócio
3. Retorne array com `['view' => '...', 'data' => [...]]`

### Adicionar uma Nova Vista

1. Crie `views/novo/ficheiro.php`
2. Use `h()` para escapar HTML
3. Use `url()` para gerar URLs
4. Use `alertSuccess()` / `alertError()` para mensagens

### Adicionar uma Nova Rota

1. Edite `routes/web.php`
2. Use `$router->get()`, `$router->post()`, etc.
3. Passe a lógica para o controlador

## Performance & Otimização

### Melhorias Implementadas

✅ PDO prepared statements (prevenção SQL injection)
✅ Password hashing com bcrypt
✅ HTML escaping automático
✅ Validação de entrada
✅ Controle de acesso baseado em papéis (RBAC)

### Futuras Melhorias

- [ ] Caching baseado em Redis
- [ ] Paginação para grandes datasets
- [ ] Índices de BD adicionais
- [ ] Minificação de CSS/JS
- [ ] API RESTful

## Suporte

Para questões específicas, consulte:
- `README.md` - Visão geral do projeto
- `README_PT.md` - Documentação detalhada em português
- Comentários no código-fonte

---

**Versão**: 1.0.0  
**Data**: Março 2026
