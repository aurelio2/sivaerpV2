<?php
if (!function_exists('findMysqlDumpCommand')) {
    function findMysqlDumpCommand() {
        $paths = [
            '/Applications/XAMPP/xamppfiles/bin/mysqldump',
            '/usr/local/mysql/bin/mysqldump',
            '/usr/local/bin/mysqldump',
            'mysqldump'
        ];

        foreach ($paths as $path) {
            if ($path === 'mysqldump') {
                return $path; // Confia no PATH do sistema
            }

            if (file_exists($path) && is_executable($path)) {
                return $path;
            }
        }

        return null;
    }
}

if (!function_exists('createPHPBackup')) {
    function createPHPBackup($mysqli, $database, $backup_file) {
        try {
            $backup_content = "-- Backup do banco de dados $database\n";
            $backup_content .= "-- Criado em: " . date('Y-m-d H:i:s') . "\n\n";
            $backup_content .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

            $tables_result = mysqli_query($mysqli, "SHOW TABLES");
            if (!$tables_result) {
                throw new Exception('Não foi possível listar as tabelas.');
            }

            while ($table_row = mysqli_fetch_row($tables_result)) {
                $table = $table_row[0];

                $create_result = mysqli_query($mysqli, "SHOW CREATE TABLE `$table`");
                if ($create_result) {
                    $create_row = mysqli_fetch_row($create_result);
                    $backup_content .= "DROP TABLE IF EXISTS `$table`;\n";
                    $backup_content .= $create_row[1] . ";\n\n";
                }

                $data_result = mysqli_query($mysqli, "SELECT * FROM `$table`");
                if ($data_result && mysqli_num_rows($data_result) > 0) {
                    $backup_content .= "INSERT INTO `$table` VALUES\n";
                    $first_row = true;

                    while ($row = mysqli_fetch_row($data_result)) {
                        if (!$first_row) {
                            $backup_content .= ",\n";
                        }
                        $backup_content .= "(";

                        for ($i = 0; $i < count($row); $i++) {
                            if ($i > 0) {
                                $backup_content .= ", ";
                            }

                            if ($row[$i] === null) {
                                $backup_content .= "NULL";
                            } else {
                                $backup_content .= "'" . mysqli_real_escape_string($mysqli, $row[$i]) . "'";
                            }
                        }

                        $backup_content .= ")";
                        $first_row = false;
                    }

                    $backup_content .= ";\n\n";
                }
            }

            $backup_content .= "SET FOREIGN_KEY_CHECKS=1;\n";

            $result = file_put_contents($backup_file, $backup_content);
            if ($result === false) {
                throw new Exception("Não foi possível escrever no arquivo $backup_file");
            }

            return true;
        } catch (Exception $e) {
            error_log("Erro na função createPHPBackup: " . $e->getMessage());
            return false;
        }
    }
}

if (!function_exists('createDatabaseBackup')) {
    function createDatabaseBackup($mysqli, $db_host, $db_user, $db_pass, $db_name, $backup_file, &$error_msg = null, &$method_used = null) {
        $mysqldump_cmd = findMysqlDumpCommand();

        if ($mysqldump_cmd) {
            $command = $mysqldump_cmd;
            $command .= ' --host=' . escapeshellarg($db_host);
            $command .= ' --user=' . escapeshellarg($db_user);
            if ($db_pass !== '') {
                $command .= ' --password=' . escapeshellarg($db_pass);
            }
            $command .= ' ' . escapeshellarg($db_name);
            $command .= ' > ' . escapeshellarg($backup_file) . ' 2>&1';

            $output = [];
            $return_code = 0;
            exec($command, $output, $return_code);

            if ($return_code === 0 && file_exists($backup_file) && filesize($backup_file) > 0) {
                $method_used = 'mysqldump';
                return true;
            }

            $error_msg = "mysqldump falhou: " . implode("\n", $output);
        }

        if (createPHPBackup($mysqli, $db_name, $backup_file)) {
            $method_used = 'php';
            return true;
        }

        if (!$error_msg) {
            $error_msg = 'Não foi possível criar o backup com mysqldump nem com PHP.';
        }

        return false;
    }
}
