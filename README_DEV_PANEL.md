# Painel do Desenvolvedor - SIVA ERP

## Visão Geral

O Painel do Desenvolvedor é uma interface administrativa avançada para configuração e manutenção do sistema SIVA ERP. Permite aos desenvolvedores e administradores gerenciar configurações críticas do sistema de forma segura e centralizada.

## Funcionalidades

### 🔧 Configurações do Banco de Dados
- Atualização das credenciais de conexão
- Teste de conectividade
- Configuração de múltiplos bancos

### ⚙️ Configurações do Sistema
- Nome e versão do sistema
- Modo debug on/off
- Modo manutenção
- Configurações de segurança

### 📊 Informações do Sistema
- Versão do PHP e MySQL
- Informações do servidor
- Espaço em disco disponível
- Uso de memória

### 🛠️ Ferramentas de Manutenção
- Limpeza de cache e logs
- Backup automático do banco
- Visualização de logs de erro
- Acesso ao PHP Info

### 📈 Status dos Serviços
- Status da conexão com banco
- Status do servidor web
- Verificação de licença
- Monitoramento em tempo real

## Instalação

### 1. Executar Script SQL
```sql
-- Execute o arquivo db/setup_dev_panel.sql no seu banco de dados
mysql -u root -p maphezu < db/setup_dev_panel.sql
```

### 2. Configurar Permissões de Usuário
Para acessar o painel, o usuário deve ter nível 'admin' ou 'dev':

```sql
-- Atualizar usuário existente para desenvolvedor
UPDATE usuarios SET nivel = 'dev' WHERE email = 'seu_email@exemplo.com';

-- Ou criar novo usuário desenvolvedor
INSERT INTO usuarios (nome, email, senha, nivel) VALUES 
('Dev User', 'dev@siva.com', MD5('sua_senha_segura'), 'dev');
```

### 3. Verificar Estrutura de Diretórios
Certifique-se de que existem os diretórios:
- `/backups/` - Para armazenar backups automáticos
- `/cache/` - Para arquivos de cache do sistema

## Acesso

1. Faça login no sistema SIVA
2. No menu lateral, procure por "Desenvolvedor" (visível apenas para admins/devs)
3. Clique em "Painel Dev"

## Segurança

### Controle de Acesso
- Apenas usuários com nível 'admin' ou 'dev' podem acessar
- Verificação de sessão ativa
- Log de todas as ações realizadas

### Recomendações
- **NUNCA** deixe credenciais padrão em produção
- Altere senhas regularmente
- Monitore logs de acesso
- Mantenha backups atualizados

## Configurações Disponíveis

### Banco de Dados
| Campo | Descrição |
|-------|-----------|
| Host | Endereço do servidor MySQL |
| Nome do Banco | Nome da base de dados |
| Usuário | Usuário de conexão |
| Senha | Senha de conexão |

### Sistema
| Campo | Descrição |
|-------|-----------|
| Nome do Sistema | Nome exibido na interface |
| Versão | Versão atual do sistema |
| Modo Debug | Ativa/desativa logs detalhados |
| Modo Manutenção | Bloqueia acesso de usuários |

## Ferramentas

### Backup do Banco
- Cria backup completo em formato SQL
- Armazenado em `/backups/`
- Nome com timestamp automático

### Limpeza de Cache
- Remove arquivos temporários
- Limpa logs de erro
- Otimiza performance

### Monitoramento
- Status em tempo real dos serviços
- Informações de sistema atualizadas
- Alertas de problemas

## Logs e Auditoria

O sistema registra automaticamente:
- Alterações de configuração
- Acessos ao painel
- Ações de manutenção
- Erros e exceções

## Troubleshooting

### Problemas Comuns

**Erro de Conexão com Banco:**
1. Verifique credenciais no painel
2. Confirme se o MySQL está rodando
3. Teste conectividade manualmente

**Painel Não Aparece no Menu:**
1. Verifique nível do usuário (deve ser 'admin' ou 'dev')
2. Confirme se executou o script SQL
3. Faça logout/login novamente

**Erro de Permissões:**
1. Verifique permissões dos diretórios `/backups/` e `/cache/`
2. Confirme que o usuário web tem acesso de escrita

## Estrutura de Arquivos

```
SIVA/
├── gestor/
│   ├── dev_panel.php          # Interface principal
│   └── header.php             # Menu atualizado
├── db/
│   └── setup_dev_panel.sql    # Script de instalação
├── backups/                   # Backups automáticos
├── cache/                     # Cache do sistema
├── phpinfo.php               # Informações do PHP
└── README_DEV_PANEL.md       # Esta documentação
```

## Suporte

Para suporte técnico ou dúvidas sobre o painel:
1. Consulte os logs de erro
2. Verifique a documentação do SIVA
3. Entre em contato com a equipe de desenvolvimento

---

**⚠️ IMPORTANTE:** Este painel contém funcionalidades críticas do sistema. Use com cuidado e sempre faça backup antes de alterações importantes.
