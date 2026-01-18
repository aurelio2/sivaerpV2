<?php
session_start();
include_once '../session.php';
include_once '../conexao.php';
include_once '../db_config.php';
include_once 'backup_utils.php';

// Verificar se o usuário tem permissões de desenvolvedor
$sql_user = mysqli_query($mysqli, "SELECT * FROM tbl_user WHERE useremail = '".$_SESSION['txt_email']."'");
$user_data = mysqli_fetch_array($sql_user);

// Só permite acesso se for admin ou desenvolvedor
if($user_data['role'] != 'admin' && $user_data['role'] != 'dev') {
    header('Location: ../index.php');
    exit();
}

// Processar ações do formulário
if(isset($_POST['action'])) {
    switch($_POST['action']) {
        case 'update_db_config':
            $new_host = $_POST['db_host'];
            $new_name = $_POST['db_name'];
            $new_user = $_POST['db_user'];
            $new_pass = $_POST['db_pass'];
            
            $config_content = "<?php\n";
            $config_content .= "// Arquivo de configuração do banco de dados\n";
            $config_content .= "// Todas as credenciais de acesso ficam centralizadas aqui\n\n";
            $config_content .= "// Credenciais para o banco de dados maphezu (principal)\n";
            $config_content .= "\$db_host = \"$new_host\";\n";
            $config_content .= "\$db_name = \"$new_name\";\n";
            $config_content .= "\$db_user = \"$new_user\";\n";
            $config_content .= "\$db_pass = \"$new_pass\";\n\n";
            $config_content .= "// Credenciais para o banco de dados tdm (usado em excel/conexao.php)\n";
            $config_content .= "\$db_tdm_host = \"$new_host\";\n";
            $config_content .= "\$db_tdm_name = \"$new_name\";\n";
            $config_content .= "\$db_tdm_user = \"$new_user\";\n";
            $config_content .= "\$db_tdm_pass = \"$new_pass\";\n\n";
            $config_content .= "// Não feche a tag PHP para evitar caracteres em branco indesejados\n";
            
            file_put_contents('../db_config.php', $config_content);
            $success_msg = "Configuração do banco de dados atualizada com sucesso!";
            break;
            
        case 'update_system_config':
            $sistema_nome = $_POST['sistema_nome'];
            $sistema_versao = $_POST['sistema_versao'];
            $debug_mode = isset($_POST['debug_mode']) ? 1 : 0;
            $maintenance_mode = isset($_POST['maintenance_mode']) ? 1 : 0;
            
            // Criar/atualizar tabela de configurações do sistema
            $sql_config = "CREATE TABLE IF NOT EXISTS system_config (
                id INT AUTO_INCREMENT PRIMARY KEY,
                config_key VARCHAR(100) UNIQUE,
                config_value TEXT,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )";
            mysqli_query($mysqli, $sql_config);
            
            // Inserir/atualizar configurações
            $configs = [
                'sistema_nome' => $sistema_nome,
                'sistema_versao' => $sistema_versao,
                'debug_mode' => $debug_mode,
                'maintenance_mode' => $maintenance_mode
            ];
            
            foreach($configs as $key => $value) {
                $sql = "INSERT INTO system_config (config_key, config_value) VALUES ('$key', '$value') 
                       ON DUPLICATE KEY UPDATE config_value = '$value'";
                mysqli_query($mysqli, $sql);
            }
            
            $success_msg = "Configurações do sistema atualizadas com sucesso!";
            break;
            
        case 'clear_cache':
            // Limpar cache e logs
            $cache_files = glob('../cache/*');
            foreach($cache_files as $file) {
                if(is_file($file)) unlink($file);
            }
            
            // Limpar error_log
            file_put_contents('error_log', '');
            
            $success_msg = "Cache e logs limpos com sucesso!";
            break;
            
        case 'backup_database':
            $backup_dir = '../backups';
            $backup_file = $backup_dir . '/backup_' . date('Y-m-d_H-i-s') . '.sql';
            
            // Criar diretório com permissões corretas
            if(!is_dir($backup_dir)) {
                if(!mkdir($backup_dir, 0755, true)) {
                    $error_msg = "Erro: Não foi possível criar o diretório de backup.";
                    break;
                }
            }
            
            // Verificar se o diretório é gravável
            if(!is_writable($backup_dir)) {
                $error_msg = "Erro: Diretório de backup não tem permissão de escrita. Execute: chmod 755 " . realpath($backup_dir);
                break;
            }
            
            // Tentar diferentes caminhos do mysqldump
            $mysqldump_paths = [
                '/Applications/XAMPP/xamppfiles/bin/mysqldump',
                '/usr/local/bin/mysqldump',
                'mysqldump'
            ];
            
            $mysqldump_cmd = null;
            foreach($mysqldump_paths as $path) {
                if(file_exists($path) || $path === 'mysqldump') {
                    $mysqldump_cmd = $path;
                    break;
                }
            }
            
            if($mysqldump_cmd) {
                $command = "$mysqldump_cmd -h $db_host -u $db_user ";
                if($db_pass) $command .= "-p$db_pass ";
                $command .= "$db_name > $backup_file 2>&1";
                
                exec($command, $output, $return_code);
                
                if($return_code === 0 && file_exists($backup_file) && filesize($backup_file) > 0) {
                    $success_msg = "Backup criado com sucesso: " . basename($backup_file) . " (" . round(filesize($backup_file)/1024, 2) . " KB)";
                } else {
                    $error_msg = "Erro ao criar backup: " . implode("\n", $output);
                    // Tentar backup via PHP como alternativa
                    if(function_exists('mysqli_query')) {
                        $php_backup = createPHPBackup($mysqli, $db_name, $backup_file);
                        if($php_backup) {
                            $success_msg = "Backup criado via PHP: " . basename($backup_file);
                            $error_msg = null;
                        }
                    }
                }
            } else {
                // Backup via PHP se mysqldump não estiver disponível
                if(function_exists('mysqli_query')) {
                    $php_backup = createPHPBackup($mysqli, $db_name, $backup_file);
                    if($php_backup) {
                        $success_msg = "Backup criado via PHP: " . basename($backup_file);
                    } else {
                        $error_msg = "Erro: mysqldump não encontrado e backup via PHP falhou.";
                    }
                } else {
                    $error_msg = "Erro: mysqldump não encontrado e mysqli não disponível.";
                }
            }
            break;
    }
}

// Buscar configurações atuais
$current_configs = [];
$sql_configs = mysqli_query($mysqli, "SELECT * FROM system_config");
if($sql_configs) {
    while($config = mysqli_fetch_array($sql_configs)) {
        $current_configs[$config['config_key']] = $config['config_value'];
    }
}

include_once 'header.php';
?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Painel do Desenvolvedor
            <small>Configurações avançadas do sistema</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="darshboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Painel Dev</li>
        </ol>
    </section>

    <section class="content">
        <?php if(isset($success_msg)): ?>
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <i class="icon fa fa-check"></i> <?php echo $success_msg; ?>
        </div>
        <?php endif; ?>
        
        <?php if(isset($error_msg)): ?>
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <i class="icon fa fa-ban"></i> <?php echo $error_msg; ?>
        </div>
        <?php endif; ?>

        <div class="row">
            <!-- Configurações do Banco de Dados -->
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-database"></i> Configurações do Banco</h3>
                    </div>
                    <form method="POST">
                        <div class="box-body">
                            <input type="hidden" name="action" value="update_db_config">
                            
                            <div class="form-group">
                                <label>Host do Banco</label>
                                <input type="text" class="form-control" name="db_host" value="<?php echo $db_host; ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label>Nome do Banco</label>
                                <input type="text" class="form-control" name="db_name" value="<?php echo $db_name; ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label>Usuário</label>
                                <input type="text" class="form-control" name="db_user" value="<?php echo $db_user; ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label>Senha</label>
                                <input type="password" class="form-control" name="db_pass" value="<?php echo $db_pass; ?>">
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">Atualizar Configurações</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Configurações do Sistema -->
            <div class="col-md-6">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-cogs"></i> Configurações do Sistema</h3>
                    </div>
                    <form method="POST">
                        <div class="box-body">
                            <input type="hidden" name="action" value="update_system_config">
                            
                            <div class="form-group">
                                <label>Nome do Sistema</label>
                                <input type="text" class="form-control" name="sistema_nome" 
                                       value="<?php echo isset($current_configs['sistema_nome']) ? $current_configs['sistema_nome'] : 'SIVA ERP'; ?>">
                            </div>
                            
                            <div class="form-group">
                                <label>Versão</label>
                                <input type="text" class="form-control" name="sistema_versao" 
                                       value="<?php echo isset($current_configs['sistema_versao']) ? $current_configs['sistema_versao'] : '1.0.0'; ?>">
                            </div>
                            
                            <div class="form-group">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="debug_mode" 
                                               <?php echo (isset($current_configs['debug_mode']) && $current_configs['debug_mode']) ? 'checked' : ''; ?>>
                                        Modo Debug
                                    </label>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="maintenance_mode" 
                                               <?php echo (isset($current_configs['maintenance_mode']) && $current_configs['maintenance_mode']) ? 'checked' : ''; ?>>
                                        Modo Manutenção
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-info">Salvar Configurações</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Informações do Sistema -->
            <div class="col-md-4">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-info-circle"></i> Informações do Sistema</h3>
                    </div>
                    <div class="box-body">
                        <table class="table table-bordered">
                            <tr>
                                <td><strong>PHP Version:</strong></td>
                                <td><?php echo phpversion(); ?></td>
                            </tr>
                            <tr>
                                <td><strong>MySQL Version:</strong></td>
                                <td><?php echo mysqli_get_server_info($mysqli); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Server:</strong></td>
                                <td><?php echo $_SERVER['SERVER_SOFTWARE']; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Espaço em Disco:</strong></td>
                                <td><?php echo round(disk_free_space('.') / 1024 / 1024 / 1024, 2); ?> GB livres</td>
                            </tr>
                            <tr>
                                <td><strong>Memória PHP:</strong></td>
                                <td><?php echo ini_get('memory_limit'); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Ferramentas de Manutenção -->
            <div class="col-md-4">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-wrench"></i> Ferramentas de Manutenção</h3>
                    </div>
                    <div class="box-body">
                        <form method="POST" style="margin-bottom: 10px;">
                            <input type="hidden" name="action" value="clear_cache">
                            <button type="submit" class="btn btn-warning btn-block" onclick="return confirm('Limpar cache e logs?')">
                                <i class="fa fa-trash"></i> Limpar Cache e Logs
                            </button>
                        </form>
                        
                        <form method="POST">
                            <input type="hidden" name="action" value="backup_database">
                            <button type="submit" class="btn btn-success btn-block" onclick="return confirm('Criar backup do banco de dados?')">
                                <i class="fa fa-download"></i> Backup do Banco
                            </button>
                        </form>
                        
                        <hr>
                        
                        <a href="error_log" target="_blank" class="btn btn-info btn-block">
                            <i class="fa fa-file-text"></i> Ver Error Log
                        </a>
                        
                        <a href="../phpinfo.php" target="_blank" class="btn btn-default btn-block">
                            <i class="fa fa-info"></i> PHP Info
                        </a>
                    </div>
                </div>
            </div>

            <!-- Status dos Serviços -->
            <div class="col-md-4">
                <div class="box box-danger">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-heartbeat"></i> Status dos Serviços</h3>
                    </div>
                    <div class="box-body">
                        <table class="table table-bordered">
                            <tr>
                                <td><strong>Banco de Dados:</strong></td>
                                <td>
                                    <?php if($mysqli->ping()): ?>
                                        <span class="label label-success">Conectado</span>
                                    <?php else: ?>
                                        <span class="label label-danger">Desconectado</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Servidor Web:</strong></td>
                                <td><span class="label label-success">Online</span></td>
                            </tr>
                            <tr>
                                <td><strong>Licença:</strong></td>
                                <td>
                                    <?php 
                                    $licenca_file = '../licenca.txt';
                                    if(file_exists($licenca_file)) {
                                        $licenca_content = file_get_contents($licenca_file);
                                        $partes = explode('|', $licenca_content);
                                        if(count($partes) == 2) {
                                            $data_exp = $partes[0];
                                            $hoje = date('Y-m-d');
                                            if($hoje <= $data_exp) {
                                                echo '<span class="label label-success">Válida até ' . date('d/m/Y', strtotime($data_exp)) . '</span>';
                                            } else {
                                                echo '<span class="label label-danger">Expirada</span>';
                                            }
                                        } else {
                                            echo '<span class="label label-warning">Inválida</span>';
                                        }
                                    } else {
                                        echo '<span class="label label-danger">Não encontrada</span>';
                                    }
                                    ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Logs Recentes -->
        <div class="row">
            <div class="col-md-12">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-file-text-o"></i> Logs Recentes</h3>
                    </div>
                    <div class="box-body">
                        <div style="background: #000; color: #0f0; padding: 15px; font-family: monospace; max-height: 300px; overflow-y: auto;">
                            <?php 
                            $log_file = 'error_log';
                            if(file_exists($log_file)) {
                                $logs = file($log_file);
                                $recent_logs = array_slice($logs, -20); // Últimas 20 linhas
                                foreach($recent_logs as $log) {
                                    echo htmlspecialchars($log) . "<br>";
                                }
                            } else {
                                echo "Nenhum log encontrado.";
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include_once 'footer.php'; ?>
