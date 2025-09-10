<div class="container">
<div class="container bg-secondary p-2">
    <h6>Escolha duas datas</h6>
    <div class="input-group">
        <label for="diaini" class="input-group-text" >Inicio</label>
        <input type="date" id="diainicial" class="form form-control">
        <label for="diafim" class="input-group-text">Fim</label>
        <input type="date" id="diafinal" class="form form-control">
        <button class="btn btn-success" onclick="filtra()">Busca</button>
    </div>
</div>
<div class="container">
    <button class="btn btn-primary" onclick="vaiprorelatorio()">
        Relatorio Estruturas
    </button>
</div>
<div id="programacao"></div>
<div id="etiquetas"></div>
<script src="view/relatorioProgramacao.js"></script>
<script>
   function vaiprorelatorio() {
    let diainicial = document.getElementById('diainicial').value;
    let diafinal = document.getElementById('diafinal').value;
    // Verifica se as datas não estão vazias nem são '0000-00-00'
    if (diainicial && diafinal && diainicial !== '0000-00-00' && diafinal !== '0000-00-00') {
        // Redireciona para a página com os parâmetros
        window.open(`http://10.1.2.251/pcp/view/estruturasdia.php?inicio=${diainicial}&fim=${diafinal}`, '_blank');
    } else {
        alert('Por favor, preencha datas válidas para início e fim.');
    }
}

     
    
</script>