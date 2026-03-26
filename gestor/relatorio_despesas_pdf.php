<?php

error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);

include_once '../dbconnect.php';
include_once "../session.php";
include_once '../conexao.php';
require_once '../dompdf/autoload.inc.php';

use Dompdf\Dompdf;

$selectedCategory = isset($_GET['category_id']) ? $_GET['category_id'] : '';
$monthStart = isset($_GET['month_start']) ? $_GET['month_start'] : '';
$monthEnd = isset($_GET['month_end']) ? $_GET['month_end'] : '';

$startDate = null;
$endDate = null;

if($monthStart !== ''){
    $startDate = $monthStart . '-01';
}

if($monthEnd !== ''){
    $endDate = $monthEnd . '-01';
}

$where = [];
$params = [];

if($selectedCategory !== ''){
    $where[] = 'e.category_id = :category_id';
    $params[':category_id'] = $selectedCategory;
}

if($startDate !== null){
    $where[] = 'e.reference_month >= :start_date';
    $params[':start_date'] = $startDate;
}

if($endDate !== null){
    $where[] = 'e.reference_month <= :end_date';
    $params[':end_date'] = $endDate;
}

$whereSql = '';
if(count($where) > 0){
    $whereSql = ' where ' . implode(' and ', $where);
}

$selectDetailsSql = "select e.*, c.name as category_name from expenses e left join expense_categories c on c.id = e.category_id" . $whereSql . " order by e.reference_month desc, e.id desc";
$selectDetails = $pdo->prepare($selectDetailsSql);
foreach($params as $k => $v){
    $selectDetails->bindValue($k, $v);
}
$selectDetails->execute();
$details = $selectDetails->fetchAll(PDO::FETCH_OBJ);

$selectTotalsSql = "select 
    COALESCE(SUM(e.amount),0) as total_amount,
    COALESCE(SUM(CASE WHEN e.paid = 1 THEN e.amount ELSE 0 END),0) as total_paid,
    COALESCE(SUM(CASE WHEN e.paid = 0 THEN e.amount ELSE 0 END),0) as total_unpaid
  from expenses e" . $whereSql;
$selectTotals = $pdo->prepare($selectTotalsSql);
foreach($params as $k => $v){
    $selectTotals->bindValue($k, $v);
}
$selectTotals->execute();
$totals = $selectTotals->fetch(PDO::FETCH_OBJ);

$selectSummarySql = "select 
    c.name as category_name,
    COALESCE(SUM(e.amount),0) as total_amount,
    COUNT(e.id) as total_count
  from expenses e
  left join expense_categories c on c.id = e.category_id" . $whereSql . "
  group by c.name
  order by total_amount desc";
$selectSummary = $pdo->prepare($selectSummarySql);
foreach($params as $k => $v){
    $selectSummary->bindValue($k, $v);
}
$selectSummary->execute();
$summary = $selectSummary->fetchAll(PDO::FETCH_OBJ);

$filterLabel = '';
if($monthStart !== '' || $monthEnd !== '' || $selectedCategory !== ''){
    $filterLabel = 'Filtros: ';
    if($selectedCategory !== ''){
        $catNameStmt = $pdo->prepare("select name from expense_categories where id=:id");
        $catNameStmt->bindValue(':id', $selectedCategory);
        $catNameStmt->execute();
        $catNameRow = $catNameStmt->fetch(PDO::FETCH_ASSOC);
        $filterLabel .= 'Categoria=' . ($catNameRow ? $catNameRow['name'] : $selectedCategory) . ' ';
    }
    if($monthStart !== ''){
        $filterLabel .= 'Mês inicial=' . $monthStart . ' ';
    }
    if($monthEnd !== ''){
        $filterLabel .= 'Mês final=' . $monthEnd . ' ';
    }
}

$html = '<div style="font-family: Courier, sans-serif; font-size: 12px;">';
$html .= '<div style="text-align:center; font-size: 14px;"><b>Relatório de Despesas</b></div>';
if($filterLabel !== ''){
    $html .= '<div style="margin-top:6px;">' . htmlspecialchars($filterLabel, ENT_QUOTES) . '</div>';
}

$html .= '<div style="margin-top:10px;">';
$html .= '<table width="100%" style="border-collapse: collapse;">';
$html .= '<tr>';
$html .= '<td style="border:1px solid #ccc; padding:6px;"><b>Total Despesas</b><br>' . number_format((float)$totals->total_amount, 2) . '</td>';
$html .= '<td style="border:1px solid #ccc; padding:6px;"><b>Total Pagas</b><br>' . number_format((float)$totals->total_paid, 2) . '</td>';
$html .= '<td style="border:1px solid #ccc; padding:6px;"><b>Total Não Pagas</b><br>' . number_format((float)$totals->total_unpaid, 2) . '</td>';
$html .= '</tr>';
$html .= '</table>';
$html .= '</div>';

$html .= '<div style="margin-top:12px;"><b>Resumo por Categoria</b></div>';
$html .= '<table width="100%" style="border-collapse: collapse; margin-top:6px;">';
$html .= '<thead><tr style="background-color:#eee;">';
$html .= '<th style="border:1px solid #ccc; padding:6px; text-align:left;">Categoria</th>';
$html .= '<th style="border:1px solid #ccc; padding:6px; text-align:right;">Quantidade</th>';
$html .= '<th style="border:1px solid #ccc; padding:6px; text-align:right;">Total</th>';
$html .= '</tr></thead><tbody>';
foreach($summary as $s){
    $html .= '<tr>';
    $html .= '<td style="border:1px solid #ccc; padding:6px;">' . htmlspecialchars($s->category_name ?? 'Sem categoria', ENT_QUOTES) . '</td>';
    $html .= '<td style="border:1px solid #ccc; padding:6px; text-align:right;">' . $s->total_count . '</td>';
    $html .= '<td style="border:1px solid #ccc; padding:6px; text-align:right;">' . number_format((float)$s->total_amount, 2) . '</td>';
    $html .= '</tr>';
}
$html .= '</tbody></table>';

$html .= '<div style="margin-top:12px;"><b>Lista de Despesas</b></div>';
$html .= '<table width="100%" style="border-collapse: collapse; margin-top:6px;">';
$html .= '<thead><tr style="background-color:#eee;">';
$html .= '<th style="border:1px solid #ccc; padding:6px; text-align:left;">#</th>';
$html .= '<th style="border:1px solid #ccc; padding:6px; text-align:left;">Titulo</th>';
$html .= '<th style="border:1px solid #ccc; padding:6px; text-align:left;">Categoria</th>';
$html .= '<th style="border:1px solid #ccc; padding:6px; text-align:left;">Mês</th>';
$html .= '<th style="border:1px solid #ccc; padding:6px; text-align:right;">Valor</th>';
$html .= '<th style="border:1px solid #ccc; padding:6px; text-align:center;">Paga</th>';
$html .= '<th style="border:1px solid #ccc; padding:6px; text-align:left;">Pagamento</th>';
$html .= '</tr></thead><tbody>';

foreach($details as $d){
    $refMonthFormatted = '';
    if($d->reference_month){
        $refMonthDate = DateTime::createFromFormat('Y-m-d', $d->reference_month);
        $refMonthFormatted = $refMonthDate ? $refMonthDate->format('d/m/Y') : $d->reference_month;
    }
    
    $paymentDateFormatted = '';
    if($d->payment_date){
        $paymentDate = DateTime::createFromFormat('Y-m-d', $d->payment_date);
        $paymentDateFormatted = $paymentDate ? $paymentDate->format('d/m/Y') : $d->payment_date;
    }
    
    $html .= '<tr>';
    $html .= '<td style="border:1px solid #ccc; padding:6px;">' . $d->id . '</td>';
    $html .= '<td style="border:1px solid #ccc; padding:6px;">' . htmlspecialchars($d->title, ENT_QUOTES) . '</td>';
    $html .= '<td style="border:1px solid #ccc; padding:6px;">' . htmlspecialchars($d->category_name ?? 'Sem categoria', ENT_QUOTES) . '</td>';
    $html .= '<td style="border:1px solid #ccc; padding:6px;">' . $refMonthFormatted . '</td>';
    $html .= '<td style="border:1px solid #ccc; padding:6px; text-align:right;">' . number_format((float)$d->amount, 2) . '</td>';
    $html .= '<td style="border:1px solid #ccc; padding:6px; text-align:center;">' . ($d->paid ? 'Sim' : 'Não') . '</td>';
    $html .= '<td style="border:1px solid #ccc; padding:6px;">' . $paymentDateFormatted . '</td>';
    $html .= '</tr>';
}
$html .= '</tbody></table>';

$html .= '<div style="margin-top:12px; font-size: 11px;">Impresso em ' . date('d/m/Y H:i') . '</div>';
$html .= '</div>';

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4','portrait');
$dompdf->render();

$filename = 'relatorio_despesas_' . date('Y-m-d_H-i-s') . '.pdf';
$dompdf->stream($filename, array("Attachment" => false));
