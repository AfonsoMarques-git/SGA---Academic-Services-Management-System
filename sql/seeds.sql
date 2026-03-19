-- =====================================================
-- DADOS DE TESTE / SEED DATA
-- =====================================================

-- Limpar dados existentes (cuidado em produção!)
DELETE FROM grade_sheet_students;
DELETE FROM grade_sheets;
DELETE FROM enrollment_requests;
DELETE FROM student_records;
DELETE FROM study_plans;
DELETE FROM course_units;
DELETE FROM courses;
DELETE FROM users;

-- =====================================================
-- CURSOS
-- =====================================================
INSERT INTO courses (code, name, description, is_active) VALUES
('CSI', 'Computação e Sistemas de Informação', 'Licenciatura em Computação e Sistemas de Informação', TRUE),
('GGE', 'Gestão', 'Licenciatura em Gestão', TRUE),
('LET', 'Letras', 'Licenciatura em Letras', TRUE);

-- =====================================================
-- UNIDADES CURRICULARES
-- =====================================================
INSERT INTO course_units (code, name, ects, description, is_active) VALUES
('ALG', 'Álgebra Linear', 6, 'Matrizes, espaços vetoriais', TRUE),
('PROG', 'Programação I', 6, 'Programação em Python', TRUE),
('BD', 'Bases de Dados', 6, 'SQL e Modelação de Dados', TRUE),
('WEB', 'Desenvolvimento Web', 6, 'HTML, CSS, JavaScript, PHP', TRUE),
('ECON', 'Economia', 6, 'Microeconomia e Macroeconomia', TRUE),
('CONT', 'Contabilidade', 6, 'Contabilidade Financeira', TRUE);

-- =====================================================
-- PLANO DE ESTUDOS (Study Plans)
-- =====================================================
-- Cursos de CSI (Ano 1, Semestre 1)
INSERT INTO study_plans (course_id, unit_id, academic_year_number, semester, is_active) VALUES
(1, 1, 1, 1, TRUE),  -- ALG
(1, 2, 1, 1, TRUE),  -- PROG
(1, 3, 1, 1, TRUE);  -- BD

-- Cursos de CSI (Ano 1, Semestre 2)
INSERT INTO study_plans (course_id, unit_id, academic_year_number, semester, is_active) VALUES
(1, 4, 1, 2, TRUE);  -- WEB

-- Cursos de GGE (Ano 1)
INSERT INTO study_plans (course_id, unit_id, academic_year_number, semester, is_active) VALUES
(2, 5, 1, 1, TRUE),  -- ECON
(2, 6, 1, 2, TRUE);  -- CONT

-- =====================================================
-- UTILIZADORES
-- =====================================================

-- Aluno 1
INSERT INTO users (role_id, full_name, email, username, password_hash, is_active) VALUES
(1, 'João Silva', 'joao.silva@email.com', 'joao_silva', '$2y$10$1A0SJAhp7h/hqJfFlRQTe.NKn.W5W8mZKzQDmqZPWDSwR.iI2KLMu', TRUE);

-- Aluno 2
INSERT INTO users (role_id, full_name, email, username, password_hash, is_active) VALUES
(1, 'Maria Santos', 'maria.santos@email.com', 'maria_santos', '$2y$10$1A0SJAhp7h/hqJfFlRQTe.NKn.W5W8mZKzQDmqZPWDSwR.iI2KLMu', TRUE);

-- Funcionário
INSERT INTO users (role_id, full_name, email, username, password_hash, is_active) VALUES
(2, 'Carlos Oliveira', 'carlos.oliveira@univ.edu', 'carlos_funcionario', '$2y$10$1A0SJAhp7h/hqJfFlRQTe.NKn.W5W8mZKzQDmqZPWDSwR.iI2KLMu', TRUE);

-- Gestor Pedagógico
INSERT INTO users (role_id, full_name, email, username, password_hash, is_active) VALUES
(3, 'Ana Costa', 'ana.costa@univ.edu', 'ana_gestor', '$2y$10$1A0SJAhp7h/hqJfFlRQTe.NKn.W5W8mZKzQDmqZPWDSwR.iI2KLMu', TRUE);

-- Nota: As passwords são hash de 'password123'

-- =====================================================
-- FICHAS DE ALUNO
-- =====================================================

-- João Silva - rascunho
INSERT INTO student_records 
(user_id, course_id, full_name, birth_date, national_id, tax_number, phone, email_contact, address, city, postal_code, status) 
VALUES
(1, 1, 'João Miguel Silva', '2003-05-15', '12345678A', '123456789', '961234567', 'joao.silva@email.com', 'Rua da Liberdade, 42', 'Lisboa', '1250-100', 'rascunho');

-- Maria Santos - submetida
INSERT INTO student_records 
(user_id, course_id, full_name, birth_date, national_id, tax_number, phone, email_contact, address, city, postal_code, status, submitted_at) 
VALUES
(2, 1, 'Maria João Santos', '2002-08-22', '87654321B', '987654321', '919876543', 'maria.santos@email.com', 'Avenida Central, 123', 'Porto', '4000-100', 'submetida', NOW());

-- =====================================================
-- PEDIDOS DE MATRÍCULA
-- =====================================================

-- Pedido pendente para Maria (que tem ficha submetida)
INSERT INTO enrollment_requests 
(user_id, course_id, student_record_id, request_type, status, notes_by_student, created_at) 
VALUES
(2, 1, 2, 'inscricao', 'pendente', 'Desejo inscrever-me em CSI', NOW());

-- =====================================================
-- PAUTAS
-- =====================================================

-- Pauta de Álgebra Linear para 2024/2025, época normal
INSERT INTO grade_sheets 
(unit_id, academic_year_id, season, created_by, status) 
VALUES
(1, 1, 'normal', 3, 'em_preparacao');

-- Pauta de Programação I
INSERT INTO grade_sheets 
(unit_id, academic_year_id, season, created_by, status) 
VALUES
(2, 1, 'normal', 3, 'em_preparacao');

-- =====================================================
-- NOTAS (Grade Sheet Students)
-- =====================================================

-- Notas para Maria na pauta de Álgebra Linear
INSERT INTO grade_sheet_students 
(grade_sheet_id, user_id, final_grade, grade_status, notes, created_at) 
VALUES
(1, 2, 16.5, 'aprovado', 'Bom desempenho', NOW());

-- Notas para Maria na pauta de Programação
INSERT INTO grade_sheet_students 
(grade_sheet_id, user_id, final_grade, grade_status, notes, created_at) 
VALUES
(2, 2, 14.0, 'aprovado', 'Progressão satisfatória', NOW());

-- =====================================================
-- LOGS DE AUDITORIA
-- =====================================================

INSERT INTO audit_logs 
(user_id, entity_type, entity_id, action, description, created_at) 
VALUES
(4, 'student_record', 2, 'submitted', 'Ficha submetida por Maria Santos', NOW()),
(3, 'enrollment_request', 1, 'created', 'Pedido de matrícula criado', NOW());

-- =====================================================
-- Fim dos Dados de Teste
-- =====================================================
