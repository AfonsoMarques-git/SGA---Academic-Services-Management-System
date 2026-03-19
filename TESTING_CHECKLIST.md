# Lista de Testes - SGA

Use esta checklist para testar todas as funcionalidades do sistema.

## 🔐 Autenticação

- [ ] Login com credenciais válidas (ana_gestor / password123)
- [ ] Login com credenciais inválidas (mostra erro)
- [ ] Logout funciona e redireciona para login
- [ ] Utilizadores não autenticados não conseguem aceder a páginas protegidas
- [ ] Cada perfil vai para seu dashboard correto

## 👤 Perfil de Aluno

- [ ] Login como aluno (joao_silva / password123)
- [ ] Dashboard de aluno mostra informações básicas
- [ ] Acesso a "Minha Ficha" funciona
- [ ] Editar dados pessoais funciona
- [ ] Seleção de curso desejado funciona
- [ ] Guardar ficha mostra mensagem de sucesso
- [ ] Não consegue aceder a páginas de gestor (403 error)

## 👷 Perfil de Funcionário

- [ ] Login como funcionário (carlos_funcionario / password123)
- [ ] Dashboard de staff mostra estatísticas
- [ ] Acesso a pautas funciona
- [ ] Listar pautas (se existirem)
- [ ] Editar notas em pauta funciona
- [ ] Não consegue aceder a páginas de gestão de cursos

## 👨‍💼 Perfil de Gestor

### Dashboard
- [ ] Login como gestor (ana_gestor / password123)
- [ ] Dashboard de gestor carrega sem erros
- [ ] Mostram estatísticas corretas (se houver dados)
- [ ] Link para "Gerenciar Cursos" funciona
- [ ] Link para "Gerenciar UCs" funciona
- [ ] Link para "Gerenciar Utilizadores" funciona
- [ ] Link para "Relatórios" funciona

### Gestão de Cursos
- [ ] Aceder a `/manager/courses.php` funciona
- [ ] Lista de cursos exibe (vazia ou com dados)
- [ ] Botão "Novo Curso" abre formulário
- [ ] Formulário de criação de curso:
  - [ ] Campos obrigatórios têm asterisco
  - [ ] Código obrigatório
  - [ ] Nome obrigatório
  - [ ] Descrição opcional
- [ ] Criar curso com dados válidos funciona
- [ ] Mensagem "Curso criado com sucesso" aparece
- [ ] Novo curso aparece na lista
- [ ] Botão "Editar" funciona
- [ ] Formulário de edição pré-carrega dados
- [ ] Editar curso funciona
- [ ] Botão "Eliminar" funciona
- [ ] Confirmação de eliminação aparece
- [ ] Curso é removido da lista

### Gestão de UCs
- [ ] Aceder a `/manager/units.php` funciona
- [ ] Lista de UCs exibe (vazia ou com dados)
- [ ] Botão "Nova UC" abre formulário
- [ ] Formulário de criação de UC:
  - [ ] Código obrigatório
  - [ ] Nome obrigatório
  - [ ] ECTS opcional (número)
  - [ ] Descrição opcional
- [ ] Criar UC com dados válidos funciona
- [ ] Nova UC aparece na lista
- [ ] Valor ECTS aparece em badge
- [ ] Editar UC funciona
- [ ] Eliminar UC funciona

### Gestão de Utilizadores
- [ ] Aceder a `/manager/users.php` funciona
- [ ] Lista de utilizadores exibe com informações corretas
- [ ] Mostra: Utilizador, Nome, Email, Perfil, Estado
- [ ] Botão "Novo Utilizador" funciona
- [ ] Formulário de criação:
  - [ ] Utilizador (único, obrigatório)
  - [ ] Email (obrigatório)
  - [ ] Nome Completo (obrigatório)
  - [ ] Perfil selector (obrigatório)
  - [ ] Palavra-passe (obrigatória)
  - [ ] Confirmar Palavra-passe (obrigatória)
- [ ] Validação:
  - [ ] Campos vazios mostram erro
  - [ ] Passwords não correspondem mostram erro
  - [ ] Email inválido mostra erro
  - [ ] Utilizador duplicado mostra erro
- [ ] Criar utilizador com dados válidos funciona
- [ ] Novo utilizador aparece na lista
- [ ] Pode fazer login com novo utilizador
- [ ] Editar utilizador:
  - [ ] Abre formulário com dados pré-carregados
  - [ ] Pode alterar email e nome
  - [ ] Pode alterar perfil
  - [ ] Password é opcional em edição (deixar em branco mantém atual)
  - [ ] Alterações são guardadas
- [ ] Desativar utilizador:
  - [ ] Botão desativar aparece
  - [ ] Utilizador desativado não consegue fazer login
  - [ ] Utilizador aparece com status "Inativo"

### Relatórios
- [ ] Aceder a `/manager/reports.php` funciona
- [ ] Dashboard de relatórios carrega
- [ ] Mostra cards com estatísticas:
  - [ ] Utilizadores Totais (número correto)
  - [ ] Cursos (número correto)
  - [ ] Unidades Curriculares (número correto)
  - [ ] Validações Pendentes (número correto)
- [ ] Tabela "Utilizadores por Perfil" funciona
- [ ] Tabela "Últimas Fichas Submetidas" funciona
- [ ] Botões de exportação apresentados:
  - [ ] Exportar Utilizadores (CSV) funciona
  - [ ] Exportar Cursos (CSV) funciona
  - [ ] Exportar UCs (CSV) funciona
- [ ] Ficheiros CSV descarregam corretamente
- [ ] CSV pode ser aberto em Excel/Calc

## 🔒 Segurança

- [ ] HTML especial caracteres escapados (testar com `<script>alert(1)</script>`)
- [ ] SQL injection testing (URL tampering) não retorna erros
- [ ] CSRF protection (se implementado)
- [ ] Session timeout funciona (logout automático)
- [ ] Utilizador não consegue aceder a outro utilizador recursos
- [ ] Palavra-passe não é visível em plaintext
- [ ] URLs sensíveis requerem POST (não GET)
- [ ] Mensagens de erro não revelam detalhes da BD

## 🎨 Interface

- [ ] Navbar carrega em todas as páginas
- [ ] Logo funciona e volta ao dashboard
- [ ] Nome utilizador aparece na navbar
- [ ] Perfil aparece na navbar
- [ ] Botão "Sair" funciona
- [ ] Bootstrap styling está consistente
- [ ] Icons FontAwesome aparecem corretamente
- [ ] Tabelas são responsivas
- [ ] Forms são responsivos
- [ ] Alertas de sucesso/erro têm cores corretas
- [ ] Botões têm cores apropriadas

## 📱 Responsividade

Testar em diferentes resoluções:

- [ ] Desktop (1920x1080)
- [ ] Tablet (768x1024)
- [ ] Mobile (375x667)

Para cada:
- [ ] Navbar colapsa em menu mobile
- [ ] Tabelas scrollam horizontalmente
- [ ] Forms são legíveis
- [ ] Botões são clicáveis

## ⚡ Performance

- [ ] Páginas carregam em < 2 segundos
- [ ] Navegação é smooth
- [ ] Sem erros de console (F12)
- [ ] Sem warnings de segurança
- [ ] Imagens carregam corretamente
- [ ] CSS carrega corretamente
- [ ] JavaScript funciona sem erros

## 🐛 Tratamento de Erros

- [ ] Aceder a página inexistente mostra 404
- [ ] Aceder com ID inválido mostra erro apropriado
- [ ] Eliminar com confirmação cancelado não elimina
- [ ] Erro de BD mostra mensagem útil (não código PHP)
- [ ] Formulário inválido não submete e mostra erros

## 📊 Fluxos Críticos

### Fluxo 1: Criar Curso + Usar em UC
- [ ] Criar novo curso "Informática"
- [ ] Criar nova UC "Programação" para esse curso
- [ ] Editar UC e alterar nome
- [ ] Verificar dados na lista

### Fluxo 2: Criar Utilizador + Login
- [ ] Criar novo utilizador com email e perfil
- [ ] Logout como gestor
- [ ] Login com novo utilizador
- [ ] Verificar que vai para dashboard correto
- [ ] Logout e teste terminado

### Fluxo 3: Aluno Submete Ficha
- [ ] Login como aluno
- [ ] Editar ficha com dados pessoais
- [ ] Selecionar curso
- [ ] Guardar ficha
- [ ] Logout

### Fluxo 4: Gestor Valida Ficha
- [ ] Login como gestor
- [ ] Aceder a relatórios
- [ ] Ver ficha submetida por aluno
- [ ] Exportar dados em CSV
- [ ] Verificar ficheiro CSV

## 📝 Regressão Testing

Após fazer alterações:

- [ ] Todos os logins funcionam
- [ ] Todos os links funcionam
- [ ] Nenhuma página mostra erro 500
- [ ] Nenhuma mensagem de erro SQL visível
- [ ] Todas as funcionalidades funcionam como antes

## ✅ Checklist Final

- [ ] Sistema ligado e a rodar
- [ ] Base de dados com dados de teste
- [ ] Todos os testes passaram
- [ ] Nenhum erro no console do navegador
- [ ] Nenhum aviso de segurança
- [ ] Performance é aceitável
- [ ] Pronto para demo/produção

---

## Ao Encontrar Bugs

1. Documentar o comportamento esperado
2. Documentar o comportamento atual
3. Documentar passos para reproduzir
4. Verificar em navegador diferente
5. Verificar console para erros
6. Reportar com evidência (screenshot/vídeo)

**Formato de relatório de bug**:
```
Título: [Módulo] Breve descrição

Passos para reproduzir:
1. Fazer isto
2. Fazer aquilo
3. Esperado: X
4. Obtido: Y

Ambiente: Windows 10, Chrome 100, XAMPP
```

---

**Data Testes**: _________________
**Testador**: _________________
**Status Final**: ✅ PASSOU / ❌ FALHOU

