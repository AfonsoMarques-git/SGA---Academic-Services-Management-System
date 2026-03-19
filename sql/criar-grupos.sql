-- ==================================================
-- CRIAR TABELA GRUPOS SE NÃO EXISTIR
-- ==================================================
-- Executa isto em phpMyAdmin se tiver erro de "grupos não existe"

CREATE TABLE IF NOT EXISTS `grupos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome_grupo` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Inserir valores padrão
INSERT IGNORE INTO `grupos` (`id`, `nome_grupo`) VALUES
(1, 'admin'),
(2, 'gestor'),
(3, 'aluno'),
(4, 'funcionario');

-- ==================================================
-- VERIFICAR E CORRIGIR FOREIGN KEY EM USERS
-- ==================================================
-- Se a tabela users não tiver FK para grupos, execute:

ALTER TABLE `users` 
ADD CONSTRAINT `users_ibfk_grupos` 
FOREIGN KEY (`group_id`) REFERENCES `grupos`(`id`) 
ON DELETE RESTRICT ON UPDATE CASCADE;

-- ==================================================
-- PRONTO!
-- Agora execute o login novamente.
-- ==================================================
