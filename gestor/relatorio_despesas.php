<?php

include_once '../dbconnect.php';
include_once "../session.php";
include_once '../conexao.php';

include_once 'header.php';

$categories = [];
$selectCats = $pdo->prepare("select * from expense_categories order by name asc");
$selectCats->execute();
while($cat = $selectCats->fetch(PDO::FETCH_OBJ)){
    $categories[] = $cat;
}

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

?>

<div class="content-wrapper">

    <section class="content-header">
        <h1>
            Relatório de Despesas
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
            <li class="active">Here</li>
        </ol>
    </section>

    <section class="content container-fluid">

        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Filtros</h3>
            </div>

            <div class="box-body">
                <form role="form" method="get" action="">

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Categoria</label>
                            <select class="form-control" name="category_id">
                                <option value="">Todas</option>
                                <?php foreach($categories as $c){
                                    $sel = ($selectedCategory !== '' && (string)$selectedCategory === (string)$c->id) ? 'selected' : '';
                                    echo '<option value="'.$c->id.'" '.$sel.'>'.$c->name.'</option>';
                                } ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Mês Inicial</label>
                            <input type="month" class="form-control" name="month_start" value="<?php echo htmlspecialchars($monthStart, ENT_QUOTES); ?>">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Mês Final</label>
                            <input type="month" class="form-control" name="month_end" value="<?php echo htmlspecialchars($monthEnd, ENT_QUOTES); ?>">
                        </div>
                    </div>

                    <div class="col-md-12" style="margin-top: 10px;">
                        <button type="submit" class="btn btn-primary">Pesquisar</button>
                        <a href="relatorio_despesas.php" class="btn btn-default">Limpar</a>
                        <button type="submit" class="btn btn-success" formaction="relatorio_despesas_pdf.php" formtarget="_blank">Gerar PDF</button>
                    </div>

                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3><?php echo number_format((float)$totals->total_amount, 2); ?></h3>
                        <p>Total Despesas</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3><?php echo number_format((float)$totals->total_paid, 2); ?></h3>
                        <p>Total Pagas</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3><?php echo number_format((float)$totals->total_unpaid, 2); ?></h3>
                        <p>Total Não Pagas</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="box box-warning">
            <div class="box-header with-border">
                <h3 class="box-title">Resumo por Categoria</h3>
            </div>
            <div class="box-body">
                <table id="tablesummaryexpenses" class="table table-striped">
                    <thead>
                        <tr>
                            <th>Categoria</th>
                            <th>Quantidade</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($summary as $s){
                            echo '<tr>';
                            echo '<td>'.htmlspecialchars($s->category_name ?? 'Sem categoria', ENT_QUOTES).'</td>';
                            echo '<td>'.$s->total_count.'</td>';
                            echo '<td>'.number_format((float)$s->total_amount,2).'</td>';
                            echo '</tr>';
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Lista de Despesas</h3>
            </div>
            <div class="box-body">
                <table id="tabledetailexpenses" class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Titulo</th>
                            <th>Categoria</th>
                            <th>Mês</th>
                            <th>Valor</th>
                            <th>Paga</th>
                            <th>Pagamento</th>
                            <th>Criado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($details as $d){
                            $paidLabel = $d->paid ? '<span class="label label-success">Sim</span>' : '<span class="label label-default">Não</span>';
                            echo '<tr>';
                            echo '<td>'.$d->id.'</td>';
                            echo '<td>'.htmlspecialchars($d->title, ENT_QUOTES).'</td>';
                            echo '<td>'.htmlspecialchars($d->category_name ?? 'Sem categoria', ENT_QUOTES).'</td>';
                            echo '<td>'.($d->reference_month ?? '').'</td>';
                            echo '<td>'.number_format((float)$d->amount,2).'</td>';
                            echo '<td>'.$paidLabel.'</td>';
                            echo '<td>'.($d->payment_date ?? '').'</td>';
                            echo '<td>'.($d->created_at ?? '').'</td>';
                            echo '</tr>';
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>

    </section>

</div>

<script>
  $(document).ready( function () {
    $('#tablesummaryexpenses').DataTable({
        paging: false,
        searching: false,
        info: false
    });

    $('#tabledetailexpenses').DataTable();
  });
</script>

<?php

include_once 'footer.php';

?>
