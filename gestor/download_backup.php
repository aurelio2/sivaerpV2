<?php
session_start();
include_once '../session.php';
include_once '../conexao.php';
include_once '../db_config.php';
include_once 'backup_utils.php';

// Verificar permissões
$sql_user = mysqli_query($mysqli, "SELECT role FROM tbl_user WHERE useremail = '".$_SESSION['txt_email']."'");
$user_data = mysqli_fetch_array($sql_user);

if(!$user_data || ($user_data['role'] != 'admin' && $user_data['role'] != 'dev')) {
    header('HTTP/1.1 403 Forbidden');
    echo 'Acesso negado.';
    exit();
}

$temp_dir = sys_get_temp_dir();
$temp_file = tempnam($temp_dir, 'siva_backup_');
$backup_file = $temp_file . '.sql';
rename($temp_file, $backup_file);

$error_msg = null;
$method_used = null;

if(createDatabaseBackup($mysqli, $db_host, $db_user, $db_pass, $db_name, $backup_file, $error_msg, $method_used)) {
    $download_name = 'siva_backup_' . date('Y-m-d_H-i-s') . '.sql';
    header('Content-Type: application/sql');
    header('Content-Disposition: attachment; filename="' . $download_name . '"');
    header('Content-Length: ' . filesize($backup_file));
    header('Pragma: no-cache');
    header('Expires: 0');

    readfile($backup_file);
    unlink($backup_file);
    exit();
}

if(file_exists($backup_file)) {
    unlink($backup_file);
}

echo '<h3>Falha ao gerar o backup.</h3>';
echo '<p>' . htmlspecialchars($error_msg ?: 'Erro desconhecido.') . '</p>';
echo '<p><a href="dev_panel.php">Voltar ao Painel</a></p>';
?>
