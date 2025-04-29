<?php
  include_once 'conexao.php';
  include_once 'config_imagem.php';

  $sql1 = mysqli_query($mysqli,"SELECT * FROM empresa");
  $res1 = mysqli_fetch_array($sql1);
  $nome_db = $res1['nome'];
  $nuit_db = $res1['nuit'];
  $contacto_db = $res1['contacto'];


// Restringir acesso apenas a administradores
// Esta é uma ferramenta de administração, não deve ser acessível publicamente
$senha_admin = "siva@@2025"; // Altere para uma senha forte

$mensagem = '';
$licenca_gerada = '';

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar senha de administrador
    if (!isset($_POST['senha']) || $_POST['senha'] !== $senha_admin) {
        $mensagem = "Senha incorreta!";
    } else {
        // Obter a data de expiração do formulário
        $data_expiracao = isset($_POST['data_expiracao']) ? $_POST['data_expiracao'] : '';
        
        // Validar a data
        if (empty($data_expiracao) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $data_expiracao)) {
            $mensagem = "Formato de data inválido. Use o formato YYYY-MM-DD.";
        } else {
            // Chave secreta para gerar hash (deve ser a mesma usada na verificação)
            $chave_secreta = 'maphezu_sistema';
            
            // Gerar hash para a licença
            $hash = md5($data_expiracao . $chave_secreta);
            
            // Criar código de licença no formato data_expiracao|hash
            $licenca_gerada = $data_expiracao . '|' . $hash;
            
            // Se solicitado, criar o arquivo de licença diretamente
            if (isset($_POST['criar_arquivo']) && $_POST['criar_arquivo'] == '1') {
                try {
                    // Tenta escrever o arquivo com tratamento de erros
                    if (file_put_contents('licenca.txt', $licenca_gerada) !== false) {
                        $mensagem = "Licença gerada e salva no arquivo licenca.txt";
                        
                        // Garantir permissões de leitura para o arquivo
                        @chmod('licenca.txt', 0666);
                    } else {
                        $mensagem = "Erro: Não foi possível salvar o arquivo de licença. Por favor, verifique as permissões.";
                    }
                } catch (Exception $e) {
                    $mensagem = "Erro ao salvar licença: " . $e->getMessage() . 
                                "<br>Você pode copiar o código abaixo e criar o arquivo manualmente.";
                }
            } else {
                $mensagem = "Licença gerada com sucesso!";
            }
        }
    }
}

// Função para calcular data de expiração padrão (1 meses a partir de hoje)
function data_padrao_expiracao() {
    return date('Y-m-d', strtotime('+1 months'));
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Gerador de Licenças - MAPHEZU</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
    <style>
        body {
            padding: 20px;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }
        .alert {
            margin-top: 20px;
        }
        .license-code {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 4px;
            font-family: monospace;
            margin: 20px 0;
            word-break: break-all;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Gerador de Licenças - <?php echo $nome_db; ?></h2>
            <p class="text-muted">Use esta ferramenta para gerar novos códigos de licença</p>
        </div>
        
        <?php if (!empty($mensagem)): ?>
            <div class="alert alert-<?php echo strpos($mensagem, 'sucesso') !== false ? 'success' : 'info'; ?>">
                <?php echo $mensagem; ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($licenca_gerada)): ?>
            <h4>Licença Gerada:</h4>
            <div class="license-code">
                <?php echo htmlspecialchars($licenca_gerada); ?>
            </div>
            <p class="text-muted">Copie este código e forneça-o ao cliente para ativar o sistema.</p>
        <?php endif; ?>
        
        <form method="post" action="">
            <div class="form-group">
                <label for="senha">Senha de Administrador:</label>
                <input type="password" class="form-control" id="senha" name="senha" required>
            </div>
            
            <div class="form-group">
                <label for="data_expiracao">Data de Expiração (YYYY-MM-DD):</label>
                <input type="date" class="form-control" id="data_expiracao" name="data_expiracao" 
                       value="<?php echo data_padrao_expiracao(); ?>" required>
            </div>
            
            <div class="form-check mb-3">
                <input type="checkbox" class="form-check-input" id="criar_arquivo" name="criar_arquivo" value="1">
                <label class="form-check-label" for="criar_arquivo">
                    Criar/atualizar arquivo de licença diretamente
                </label>
            </div>
            
            <button type="submit" class="btn btn-primary btn-block">Gerar Licença</button>
        </form>
        
        <hr>
        
        <div class="text-center text-muted">
            <small>Esta ferramenta deve ser mantida em local seguro e acessível apenas a administradores.</small>
        </div>
    </div>
</body>
</html>
