<?php
session_start();
include_once 'conexao.php';

echo "<h2>Debug - Informações do Usuário</h2>";

// Verificar sessões ativas
echo "<h3>Sessões Ativas:</h3>";
echo "txt_email: " . (isset($_SESSION['txt_email']) ? $_SESSION['txt_email'] : 'NÃO DEFINIDO') . "<br>";
echo "txt_senha: " . (isset($_SESSION['txt_senha']) ? 'DEFINIDO' : 'NÃO DEFINIDO') . "<br>";

if(isset($_SESSION['txt_email'])) {
    $email = $_SESSION['txt_email'];
    
    // Buscar informações do usuário
    echo "<h3>Dados do Usuário no Banco:</h3>";
    $sql = mysqli_query($mysqli, "SELECT * FROM tbl_user WHERE useremail = '$email'");
    
    if($sql && mysqli_num_rows($sql) > 0) {
        $user = mysqli_fetch_array($sql);
        echo "UserID: " . $user['userid'] . "<br>";
        echo "Username: " . $user['username'] . "<br>";
        echo "Email: " . $user['useremail'] . "<br>";
        echo "Role: " . $user['role'] . "<br>";
        
        // Verificar se pode acessar dev panel
        echo "<h3>Verificação de Acesso:</h3>";
        if($user['role'] == 'admin' || $user['role'] == 'dev') {
            echo "<span style='color: green;'>✓ USUÁRIO TEM PERMISSÃO PARA ACESSAR PAINEL DEV</span><br>";
        } else {
            echo "<span style='color: red;'>✗ USUÁRIO NÃO TEM PERMISSÃO (role atual: " . $user['role'] . ")</span><br>";
            echo "<br><strong>Para dar permissão, execute:</strong><br>";
            echo "<code>UPDATE tbl_user SET role = 'dev' WHERE useremail = '$email';</code>";
        }
    } else {
        echo "<span style='color: red;'>Usuário não encontrado no banco!</span>";
    }
    
    // Verificar estrutura da tabela
    echo "<h3>Estrutura da Tabela tbl_user:</h3>";
    $desc = mysqli_query($mysqli, "DESCRIBE tbl_user");
    echo "<table border='1'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    while($row = mysqli_fetch_array($desc)) {
        echo "<tr>";
        echo "<td>" . $row['Field'] . "</td>";
        echo "<td>" . $row['Type'] . "</td>";
        echo "<td>" . $row['Null'] . "</td>";
        echo "<td>" . $row['Key'] . "</td>";
        echo "<td>" . $row['Default'] . "</td>";
        echo "<td>" . $row['Extra'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
} else {
    echo "<span style='color: red;'>Nenhuma sessão ativa encontrada!</span>";
}

echo "<br><br><a href='gestor/darshboard.php'>Voltar ao Dashboard</a>";
?>
