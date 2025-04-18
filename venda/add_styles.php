<!-- Estilos para os cards de produtos e layout -->
<style>
    /* Estilos para os cards de produtos */
    .product-card {
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 5px;
        margin-bottom: 15px;
        background-color: #fff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        transition: transform 0.2s;
        cursor: pointer;
        height: 120px;
    }
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        border-color: #3498db;
    }
    .product-name {
        font-weight: bold;
        margin-bottom: 5px;
        height: 19px;
        overflow: hidden;
        font-size: 14px;
    }
    .product-price {
        color: #e74c3c;
        font-size: 16px;
        margin-bottom: 5px;
        font-weight: bold;
    }
    .product-stock {
        color: #7f8c8d;
        font-size: 12px;
        margin-bottom: 10px;
    }
    .btn-add-product {
        width: 100%;
        background-color: #27ae60;
        border-color: #27ae60;
    }
    .btn-add-product:hover {
        background-color: #2ecc71;
        border-color: #2ecc71;
    }
    .product-cards-container {
        margin-bottom: 10px;
    }
    /* Destacar produtos na tabela */
    #producttable tbody tr:hover {
        background-color: #f5f5f5;
    }
    /* Estilo para os painéis */
    .panel-primary > .panel-heading {
        background-color: #3498db;
        color: white;
    }
    .panel-success > .panel-heading {
        background-color: #27ae60;
        color: white;
    }
    .panel-body {
        padding: 10px;
    }
    .panel {
        border-radius: 4px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.2);
        margin-bottom: 20px;
    }
</style>

<!-- Script para o sistema de cards de produtos -->
<script>
$(document).ready(function(){
    // Função para adicionar produto à tabela quando clicar no card
    $(document).on('click', '.btn-add-product', function(){
        try {
            var productCard = $(this).closest('.product-card');
            var productId = productCard.data('id');
            var productName = productCard.data('name');
            var productPrice = productCard.data('price');
            var productStock = productCard.data('stock');
            var productIva = productCard.data('iva');
            
            console.log('Clicou no produto:', productName, 'ID:', productId, 'Preço:', productPrice);
            
            if (!productId || !productName) {
                alert('Dados do produto incompletos. Por favor, tente novamente.');
                return;
            }
            
            // Verificar se a tabela tem tbody, caso não tenha, adicionar
            if ($('#producttable tbody').length === 0) {
                $('#producttable').append('<tbody></tbody>');
            }
            
            // Verificar se o produto já está na tabela
            var exists = false;
            $('#producttable tbody tr').each(function() {
                var existingId = $(this).find('.productid').val();
                if (existingId == productId) {
                    // Incrementar quantidade
                    var qtyInput = $(this).find('.qty');
                    var currentQty = parseInt(qtyInput.val());
                    if (currentQty < productStock) {
                        qtyInput.val(currentQty + 1);
                        qtyInput.trigger('change');
                    } else {
                        alert("A quantidade máxima disponível em estoque foi atingida!");
                    }
                    exists = true;
                    return false;
                }
            });
            
            if (!exists) {
                // Adicionar nova linha na tabela
                var html = '';
                html += '<tr>';
                html += '<td><center><button type="button" name="remove" class="btn btn-danger btn-sm btnremove"><span class="glyphicon glyphicon-remove"></span></button></center></td>';
                html += '<td>' + productName + '<input type="hidden" class="form-control pname" name="productname[]" value="' + productName + '" readonly><input type="hidden" class="productid" name="productid[]" value="' + productId + '"></td>';
                html += '<td><input type="number" class="form-control stock" name="stock[]" value="' + productStock + '" readonly></td>';
                html += '<td><input type="text" class="form-control price" name="price[]" value="' + productPrice + '" readonly id="txt_price"></td>';
                html += '<td><input type="number" min="1" max="' + productStock + '" class="form-control qty" name="qty[]" value="1"></td>';
                html += '<td><input type="text" class="form-control total" name="total[]" value="' + productPrice + '" readonly></td>';
                html += '<td class="hidden"><input type="number" class="form-control totaliva" name="totaliva[]" value="' + (productPrice * productIva / 100) + '" readonly></td>';
                
                $('#producttable tbody').append(html);
                console.log('Produto adicionado à tabela');
                calculate(0,0);
            }
        } catch(e) {
            console.error('Erro ao adicionar produto:', e);
            alert('Erro ao adicionar produto: ' + e.message);
        }
    });
});
</script>
