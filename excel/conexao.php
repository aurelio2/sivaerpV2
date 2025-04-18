<?php
    // Incluir arquivo de configuração
    require_once __DIR__ . '/../db_config.php';
    
    // Criar conexão usando as credenciais do banco tdm configuradas no arquivo de configuração
    $mysqli = new mysqli($db_tdm_host, $db_tdm_user, $db_tdm_pass, $db_tdm_name);
?>
