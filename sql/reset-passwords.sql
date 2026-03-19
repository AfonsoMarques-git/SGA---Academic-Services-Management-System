-- ==================================================
-- RESET DE PASSWORDS PARA TESTE
-- ==================================================
-- 
-- Se não sabe as passwords dos seus utilizadores,
-- execute este script para resetar para "password123"
-- 
-- ⚠️ NÃO USE EM PRODUÇÃO! Apenas para XAMPP local!
-- ==================================================

-- Reset all passwords to "password123" (plain text)
UPDATE users SET password = 'password123';

-- Cria utilizadores de teste se não existirem
-- Primeira, verifica grupos
INSERT OR IGNORE INTO grupos (id, nome_grupo) VALUES (1, 'admin');
INSERT OR IGNORE INTO grupos (id, nome_grupo) VALUES (2, 'gestor');
INSERT OR IGNORE INTO grupos (id, nome_grupo) VALUES (3, 'aluno');

-- Adiciona utilizadores de teste se não existirem
INSERT OR IGNORE INTO users (username, password, group_id) 
VALUES ('test_admin', 'password123', 1);

INSERT OR IGNORE INTO users (username, password, group_id) 
VALUES ('test_gestor', 'password123', 2);

INSERT OR IGNORE INTO users (username, password, group_id) 
VALUES ('test_aluno', 'password123', 3);

-- ==================================================
-- Depois de executar:
-- 
-- Pode fazer login com:
--   Username: admin, Password: password123
--   Username: Filipe, Password: password123
--   Username: Gonçalo, Password: password123
--   Username: Afonso, Password: password123
--   E também:
--   Username: test_admin, Password: password123
--   Username: test_gestor, Password: password123
--   Username: test_aluno, Password: password123
-- ==================================================
