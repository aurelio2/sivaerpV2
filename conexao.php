<?php
    // Incluir arquivo de configuração
    require_once __DIR__ . '/db_config.php';
    
    // Criar conexão usando as credenciais do arquivo de configuração
    $mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
?>
