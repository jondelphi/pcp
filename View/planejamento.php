<div class="d-flex justify-content-center align-items-center">
    <div class="bg-secondary rounded p-2">
        <div class="input-group">
            <span class="input-group-text">Data Final</span>
            <input type="date" class="form-control" id="data_final" name="data_final" value="<?php echo date('Y-m-d'); ?>">
            <button class="btn btn-primary" onclick="atualizarPlanejamento(),fabricantes()">Atualizar</button>
        </div>
    </div>
</div>
<div class="container" id="necessidades"></div>
<div class="container p-2 m-2" id="fabricantes"></div>
<div class="container p-2 m-2" id="dadosPedido"></div>

<script src="planejamento.js"></script>
<script>


</script>

<!-- 

  {
        "codigo": "10010017",
        "descricao": "FOGAO 4BC ASIATICO JR BRANCO",
        "menor_data_pedido": "2025-09-09",
        "maior_data_pedido": "2025-09-25",
        "quantidade_pedida": 858,
        "quantidade_estoque": 816,
        "quantidade_programada": 0,
        "menor_data_programada": null,
        "maior_data_programada": null
    },
-->