-- Script SQL para configurar o painel de desenvolvedor
-- Execute este script para preparar o banco de dados

-- 1. Criar tabela de configurações do sistema (se não existir)
CREATE TABLE IF NOT EXISTS system_config (
    id INT AUTO_INCREMENT PRIMARY KEY,
    config_key VARCHAR(100) UNIQUE NOT NULL,
    config_value TEXT,
    description TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. Inserir configurações padrão do sistema
INSERT INTO system_config (config_key, config_value, description) VALUES
('sistema_nome', 'SIVA ERP', 'Nome do sistema exibido na interface')
ON DUPLICATE KEY UPDATE config_value = VALUES(config_value);

INSERT INTO system_config (config_key, config_value, description) VALUES
('sistema_versao', '1.0.0', 'Versão atual do sistema')
ON DUPLICATE KEY UPDATE config_value = VALUES(config_value);

INSERT INTO system_config (config_key, config_value, description) VALUES
('debug_mode', '0', 'Modo debug ativo (0=desativado, 1=ativado)')
ON DUPLICATE KEY UPDATE config_value = VALUES(config_value);

INSERT INTO system_config (config_key, config_value, description) VALUES
('maintenance_mode', '0', 'Modo manutenção ativo (0=desativado, 1=ativado)')
ON DUPLICATE KEY UPDATE config_value = VALUES(config_value);

INSERT INTO system_config (config_key, config_value, description) VALUES
('max_login_attempts', '5', 'Máximo de tentativas de login antes do bloqueio')
ON DUPLICATE KEY UPDATE config_value = VALUES(config_value);

INSERT INTO system_config (config_key, config_value, description) VALUES
('session_timeout', '3600', 'Tempo limite da sessão em segundos')
ON DUPLICATE KEY UPDATE config_value = VALUES(config_value);

-- 3. Verificar se a tabela tbl_user existe e atualizar role para suportar 'dev'
-- Modificar a coluna role para incluir 'dev' se necessário
ALTER TABLE tbl_user MODIFY COLUMN role ENUM('user', 'admin', 'dev', 'Barb-men') DEFAULT 'user';

-- 4. Criar um usuário desenvolvedor padrão (opcional - descomente se necessário)
-- IMPORTANTE: Altere a senha antes de usar em produção!
/*
INSERT INTO tbl_user (username, useremail, password, role) VALUES 
('Desenvolvedor', 'dev@siva.com', MD5('dev123'), 'dev')
ON DUPLICATE KEY UPDATE role = 'dev';
*/

-- 5. Criar tabela de logs do sistema para auditoria
CREATE TABLE IF NOT EXISTS system_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    action VARCHAR(255) NOT NULL,
    description TEXT,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES tbl_user(userid) ON DELETE SET NULL
);

-- 6. Criar índices para melhor performance
CREATE INDEX IF NOT EXISTS idx_system_config_key ON system_config(config_key);
CREATE INDEX IF NOT EXISTS idx_system_logs_user ON system_logs(user_id);
CREATE INDEX IF NOT EXISTS idx_system_logs_date ON system_logs(created_at);

-- 7. Criar tabela para controle de sessões
CREATE TABLE IF NOT EXISTS user_sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    session_id VARCHAR(255) NOT NULL,
    ip_address VARCHAR(45),
    user_agent TEXT,
    last_activity TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES tbl_user(userid) ON DELETE CASCADE,
    UNIQUE KEY unique_session (session_id)
);

-- 8. Inserir configurações de segurança
INSERT INTO system_config (config_key, config_value, description) VALUES
('password_min_length', '6', 'Comprimento mínimo da senha')
ON DUPLICATE KEY UPDATE config_value = VALUES(config_value);

INSERT INTO system_config (config_key, config_value, description) VALUES
('require_password_change', '0', 'Forçar mudança de senha no primeiro login')
ON DUPLICATE KEY UPDATE config_value = VALUES(config_value);

INSERT INTO system_config (config_key, config_value, description) VALUES
('backup_retention_days', '30', 'Dias para manter backups automáticos')
ON DUPLICATE KEY UPDATE config_value = VALUES(config_value);

-- Fim do script
