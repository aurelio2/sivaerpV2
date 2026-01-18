-- Script para adicionar permissões de desenvolvedor
-- Execute este comando no seu banco de dados

-- 1. Primeiro, modificar a coluna role para aceitar 'dev'
ALTER TABLE tbl_user MODIFY COLUMN role ENUM('user', 'admin', 'dev', 'Barb-men') DEFAULT 'user';

-- 2. Atualizar um usuário existente para desenvolvedor
-- SUBSTITUA 'seu_email@exemplo.com' pelo email do seu usuário
UPDATE tbl_user SET role = 'dev' WHERE useremail = 'seu_email@exemplo.com';

-- 3. OU criar um novo usuário desenvolvedor (descomente as linhas abaixo se preferir)
/*
INSERT INTO tbl_user (username, useremail, password, role) VALUES 
('Dev Admin', 'dev@siva.com', MD5('dev123456'), 'dev');
*/

-- 4. Verificar se funcionou - este comando mostrará todos os usuários dev/admin
SELECT userid, username, useremail, role FROM tbl_user WHERE role IN ('admin', 'dev');
