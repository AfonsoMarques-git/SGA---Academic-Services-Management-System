-- =====================================================
-- SISTEMA DE GESTÃO DE SERVIÇOS ACADÉMICOS
-- Schema SQL Completo
-- =====================================================

-- Drop existing tables (careful in production!)
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS audit_logs;
DROP TABLE IF EXISTS grade_sheet_students;
DROP TABLE IF EXISTS grade_sheets;
DROP TABLE IF EXISTS enrollment_requests;
DROP TABLE IF EXISTS student_records;
DROP TABLE IF EXISTS study_plans;
DROP TABLE IF EXISTS course_units;
DROP TABLE IF EXISTS courses;
DROP TABLE IF EXISTS academic_years;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS roles;
SET FOREIGN_KEY_CHECKS = 1;

-- =====================================================
-- 1. ROLES (Perfis)
-- =====================================================
CREATE TABLE roles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL UNIQUE,
    description VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO roles (name, description) VALUES
('aluno', 'Estudante'),
('funcionario', 'Funcionário dos Serviços Académicos'),
('gestor', 'Gestor Pedagógico');

-- =====================================================
-- 2. USERS (Utilizadores)
-- =====================================================
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    role_id INT NOT NULL,
    full_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    username VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255),
    is_active BOOLEAN DEFAULT TRUE,
    last_login DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES roles(id),
    INDEX idx_role (role_id),
    INDEX idx_email (email),
    INDEX idx_username (username)
);

-- =====================================================
-- 3. ACADEMIC YEARS (Anos Letivos)
-- =====================================================
CREATE TABLE academic_years (
    id INT PRIMARY KEY AUTO_INCREMENT,
    label VARCHAR(20) NOT NULL UNIQUE,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    comment VARCHAR(255)
);

INSERT INTO academic_years (label, is_active) VALUES
('2024/2025', TRUE),
('2025/2026', TRUE);

-- =====================================================
-- 4. COURSES (Cursos)
-- =====================================================
CREATE TABLE courses (
    id INT PRIMARY KEY AUTO_INCREMENT,
    code VARCHAR(20) NOT NULL UNIQUE,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_code (code),
    INDEX idx_active (is_active)
);

-- =====================================================
-- 5. COURSE UNITS (Unidades Curriculares)
-- =====================================================
CREATE TABLE course_units (
    id INT PRIMARY KEY AUTO_INCREMENT,
    code VARCHAR(20) NOT NULL UNIQUE,
    name VARCHAR(255) NOT NULL,
    ects INT,
    description TEXT,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_code (code),
    INDEX idx_active (is_active)
);

-- =====================================================
-- 6. STUDY PLANS (Plano de Estudos)
-- Associação entre Cursos e UCs
-- =====================================================
CREATE TABLE study_plans (
    id INT PRIMARY KEY AUTO_INCREMENT,
    course_id INT NOT NULL,
    unit_id INT NOT NULL,
    academic_year_number INT NOT NULL,
    semester INT NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(id),
    FOREIGN KEY (unit_id) REFERENCES course_units(id),
    UNIQUE KEY unique_study_plan (course_id, unit_id, academic_year_number, semester),
    INDEX idx_course (course_id),
    INDEX idx_unit (unit_id)
);

-- =====================================================
-- 7. STUDENT RECORDS (Ficha de Aluno)
-- =====================================================
CREATE TABLE student_records (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    course_id INT NOT NULL,
    full_name VARCHAR(255) NOT NULL,
    birth_date DATE,
    national_id VARCHAR(20),
    tax_number VARCHAR(20),
    phone VARCHAR(20),
    email_contact VARCHAR(255),
    address TEXT,
    city VARCHAR(100),
    postal_code VARCHAR(10),
    photo_path VARCHAR(500),
    status VARCHAR(20) DEFAULT 'rascunho',
    submitted_at DATETIME,
    reviewed_by INT,
    reviewed_at DATETIME,
    review_notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (course_id) REFERENCES courses(id),
    FOREIGN KEY (reviewed_by) REFERENCES users(id),
    UNIQUE KEY unique_active_record (user_id, status),
    INDEX idx_user (user_id),
    INDEX idx_course (course_id),
    INDEX idx_status (status)
);

-- =====================================================
-- 8. ENROLLMENT REQUESTS (Pedidos de Matrícula/Inscrição)
-- =====================================================
CREATE TABLE enrollment_requests (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    course_id INT NOT NULL,
    student_record_id INT,
    request_type VARCHAR(50),
    status VARCHAR(20) DEFAULT 'pendente',
    notes_by_student TEXT,
    reviewed_by INT,
    reviewed_at DATETIME,
    review_notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (course_id) REFERENCES courses(id),
    FOREIGN KEY (student_record_id) REFERENCES student_records(id),
    FOREIGN KEY (reviewed_by) REFERENCES users(id),
    INDEX idx_user (user_id),
    INDEX idx_course (course_id),
    INDEX idx_status (status)
);

-- =====================================================
-- 9. GRADE SHEETS (Pautas de Avaliação)
-- =====================================================
CREATE TABLE grade_sheets (
    id INT PRIMARY KEY AUTO_INCREMENT,
    unit_id INT NOT NULL,
    academic_year_id INT NOT NULL,
    season VARCHAR(30) NOT NULL,
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    status VARCHAR(20) DEFAULT 'em_preparacao',
    notes TEXT,
    FOREIGN KEY (unit_id) REFERENCES course_units(id),
    FOREIGN KEY (academic_year_id) REFERENCES academic_years(id),
    FOREIGN KEY (created_by) REFERENCES users(id),
    UNIQUE KEY unique_grade_sheet (unit_id, academic_year_id, season),
    INDEX idx_unit (unit_id),
    INDEX idx_academic_year (academic_year_id),
    INDEX idx_status (status)
);

-- =====================================================
-- 10. GRADE SHEET STUDENTS (Registos de Notas)
-- =====================================================
CREATE TABLE grade_sheet_students (
    id INT PRIMARY KEY AUTO_INCREMENT,
    grade_sheet_id INT NOT NULL,
    user_id INT NOT NULL,
    final_grade DECIMAL(5,2),
    grade_status VARCHAR(50),
    notes TEXT,
    updated_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (grade_sheet_id) REFERENCES grade_sheets(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (updated_by) REFERENCES users(id),
    UNIQUE KEY unique_student_grade (grade_sheet_id, user_id),
    INDEX idx_grade_sheet (grade_sheet_id),
    INDEX idx_user (user_id)
);

-- =====================================================
-- 11. AUDIT LOGS (Auditoria)
-- =====================================================
CREATE TABLE audit_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    entity_type VARCHAR(100) NOT NULL,
    entity_id INT,
    action VARCHAR(50) NOT NULL,
    description TEXT,
    old_values JSON,
    new_values JSON,
    ip_address VARCHAR(45),
    user_agent VARCHAR(500),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    INDEX idx_user (user_id),
    INDEX idx_entity (entity_type, entity_id),
    INDEX idx_action (action),
    INDEX idx_created (created_at)
);

-- =====================================================
-- Constraints de Integridade Adicionais
-- =====================================================

-- Validação de valores de status
ALTER TABLE student_records
ADD CHECK (status IN ('rascunho', 'submetida', 'aprovada', 'rejeitada'));

ALTER TABLE enrollment_requests
ADD CHECK (status IN ('pendente', 'aprovado', 'rejeitado'));

ALTER TABLE grade_sheets
ADD CHECK (status IN ('em_preparacao', 'publicada', 'fechada'));

ALTER TABLE grade_sheets
ADD CHECK (season IN ('normal', 'recurso', 'especial'));

-- Validação de notas
ALTER TABLE grade_sheet_students
ADD CHECK (final_grade IS NULL OR (final_grade >= 0 AND final_grade <= 20));

-- =====================================================
-- Views Úteis
-- =====================================================

-- View: Students with their records
CREATE OR REPLACE VIEW view_students_records AS
SELECT 
    u.id,
    u.full_name,
    u.email,
    u.username,
    sr.id AS record_id,
    sr.course_id,
    c.name AS course_name,
    sr.status,
    sr.submitted_at,
    sr.reviewed_at,
    sr.photo_path
FROM users u
LEFT JOIN student_records sr ON u.id = sr.user_id
LEFT JOIN courses c ON sr.course_id = c.id
WHERE u.role_id = 1;

-- View: Pending enrollments with student info
CREATE OR REPLACE VIEW view_pending_enrollments AS
SELECT 
    er.id,
    er.user_id,
    u.full_name,
    u.email,
    c.name AS course_name,
    sr.id AS record_id,
    sr.status AS record_status,
    er.status,
    er.created_at,
    er.notes_by_student
FROM enrollment_requests er
JOIN users u ON er.user_id = u.id
JOIN courses c ON er.course_id = c.id
LEFT JOIN student_records sr ON er.student_record_id = sr.id
WHERE er.status = 'pendente'
ORDER BY er.created_at DESC;

-- =====================================================
-- Fim do Schema
-- =====================================================
