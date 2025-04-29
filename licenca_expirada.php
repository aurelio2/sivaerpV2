<?php
  include_once 'conexao.php';
  include_once 'config_imagem.php';

  $sql1 = mysqli_query($mysqli,"SELECT * FROM empresa");
  $res1 = mysqli_fetch_array($sql1);
  $nome_db = $res1['nome'];
  $nuit_db = $res1['nuit'];
  $contacto_db = $res1['contacto'];

// Verificar se existe um arquivo de licença
$arquivo_licenca = 'licenca.txt';
$tem_licenca = file_exists($arquivo_licenca);
$mensagem_erro = '';

// Se enviou formulário para atualizar licença
if (isset($_POST['nova_licenca'])) {
    $nova_licenca = trim($_POST['nova_licenca']);
    
    // Validar formato da licença
    $partes = explode('|', $nova_licenca);
    if (count($partes) == 2) {
        $data_expiracao = $partes[0];
        $hash = $partes[1];
        
        // Verificar formato de data
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $data_expiracao)) {
            // Validar hash
            $chave_secreta = 'maphezu_sistema';
            $hash_esperado = md5($data_expiracao . $chave_secreta);
            
            if ($hash === $hash_esperado) {
                // Licença válida, salvar no arquivo
                file_put_contents($arquivo_licenca, $nova_licenca);
                
                // Redirecionar para a página inicial
                header('Location: index.php');
                exit();
            } else {
                $mensagem_erro = 'Licença inválida. O código não é válido.';
            }
        } else {
            $mensagem_erro = 'Licença inválida. Formato de data incorreto.';
        }
    } else {
        $mensagem_erro = 'Formato de licença inválido.';
    }
}

// Verificar data de expiração se tiver licença
$data_expiracao = '';
if ($tem_licenca) {
    $licenca_atual = trim(file_get_contents($arquivo_licenca));
    $partes = explode('|', $licenca_atual);
    if (count($partes) == 2) {
        $data_expiracao = $partes[0];
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $nome_db; ?> - Licença Expirada</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
    <style>
        body {
            background-color: #f5f5f5;
        }
        .license-box {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .logo {
            text-align: center;
            margin-bottom: 20px;
        }
        .error-message {
            color: #e74c3c;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="license-box">
            <div class="logo">
                <img src="<?php echo $nome_imagem_logo; ?>" alt="<?php echo $nome_db; ?> Logo" style="max-width: 150px;">
                <h3><?php echo $nome_db; ?></h3>
            </div>
            
            <div class="alert alert-warning">
                <h4><i class="icon fa fa-warning"></i> Licença Expirada!</h4>
                <?php if ($tem_licenca && !empty($data_expiracao)): ?>
                    <p>Sua licença expirou em <strong><?php echo date('d/m/Y', strtotime($data_expiracao)); ?></strong>.</p>
                <?php else: ?>
                    <p>Nenhuma licença válida foi encontrada para este sistema.</p>
                <?php endif; ?>
                <p>Por favor, entre em contato com o suporte para adquirir uma nova licença ou insira abaixo o código de licença recebido.</p>
            </div>
            
            <?php if (!empty($mensagem_erro)): ?>
                <div class="error-message"><?php echo $mensagem_erro; ?></div>
            <?php endif; ?>
            
            <form action="" method="post">
                <div class="form-group">
                    <label for="nova_licenca">Código de Licença:</label>
                    <input type="text" name="nova_licenca" id="nova_licenca" class="form-control" placeholder="Digite o código de licença" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Ativar Licença</button>
            </form>
            
            <div class="text-center" style="margin-top: 20px;">
                <p>Para adquirir uma nova licença, entre em contato:</p>
                <p><strong>Email:</strong> suporte@maphezu.com</p>
                <p><strong>Tel:</strong> +258 84 123 4567</p>
            </div>
        </div>
    </div>
</body>
</html>
