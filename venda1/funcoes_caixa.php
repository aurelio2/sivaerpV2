<?php
/**
 * Funções utilitárias para operações relacionadas ao caixa
 */

/**
 * Obtém a data do caixa aberto para o usuário especificado
 * 
 * @param int $idUser ID do usuário
 * @return string Data do caixa aberto no formato Y-m-d ou data atual se não houver caixa aberto
 */
function getDataCaixaAberto($idUser, $mysqli) {
    // Busca o caixa aberto mais recente para o usuário
    $sql = mysqli_query($mysqli, "SELECT data 
        FROM tbl_caixa 
        WHERE id_user = '$idUser' 
          AND estado = '1' 
          AND closed_by = '0' 
          AND data_final = '0000-00-00'
          AND id = (SELECT MAX(id) FROM tbl_caixa WHERE id_user = '$idUser')");
    
    if (mysqli_num_rows($sql) > 0) {
        $res = mysqli_fetch_array($sql);
        return $res['data']; // Retorna a data do caixa aberto
    } else {
        // Se não houver caixa aberto, retorna a data atual
        return date('Y-m-d');
    }
}

/**
 * Verifica se existe um caixa aberto para o usuário
 * 
 * @param int $idUser ID do usuário
 * @return bool True se existir caixa aberto, False caso contrário
 */
function existeCaixaAberto($idUser, $mysqli) {
    $sql = mysqli_query($mysqli, "SELECT id 
        FROM tbl_caixa 
        WHERE id_user = '$idUser' 
          AND estado = '1' 
          AND closed_by = '0' 
          AND data_final = '0000-00-00'");
    
    return (mysqli_num_rows($sql) > 0);
}
?>
