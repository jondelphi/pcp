
<div class="container m-2 p-2">
  <div class="container">
    <div class="container p-2 m-2"  hx-trigger="every 3s" hx-get="http://10.1.2.251/api2/getColetaDia/<?php echo $_SESSION['pcpAPI']['diaapont'] ?>">

    </div>
  </div>
  <div class="container">
    <div class="row">
      <div class="col m-2 bg-black text-white text-center p-2"  hx-trigger="every 3s" hx-get="http://10.1.2.251/api2/getTotalDia/<?php echo $_SESSION['pcpAPI']['diaapont'] ?>">
      </div>
      <div class="col m-2 p-2"   hx-trigger="every 3s" hx-get="http://10.1.2.251/api2/getResumoDiaLinhas/<?php echo $_SESSION['pcpAPI']['diaapont'] ?>">
      </div>
    </div>
    <div class="row">
      <div class="col m-2 p-2" id="tabela_placar">

      </div>
    </div>
    

  </div>
</div>


<?php
function calcularMediaHora($quantidade, $dia)
{
  // Verifica se o dia é igual ao dia atual
  $dataAtual = date('Y-m-d');
  if ($dia == $dataAtual) {
    // Calcula a média por hora desde as 7:30 até a hora atual
    $horaAtual = date('H:i');
    if ($horaAtual > '07:30') {
      $tempoTotal = strtotime($horaAtual) - strtotime('07:30:00');
      $horasTrabalhadas = round($tempoTotal / 3600, 2);
      $mediaHora = $quantidade / $horasTrabalhadas;
      return number_format($mediaHora, 2, '.', '');
    } else {
      return 0;
    }
  } elseif ($dia < $dataAtual) {
    // Calcula a média por hora considerando um dia de trabalho normal (7:30 às 17:25)
    $horasTrabalhadas = 9.75;
    $mediaHora = $quantidade / $horasTrabalhadas;
    return number_format($mediaHora, 2, '.', '');
  } else {
    // Retorna nada se o dia for posterior ao dia atual
    return null;
  }
}

?>
<script>
  function verJustificativa(dia, codigo, linha) {
    let linhaF = linha.replace(' ', '0');
    window.location.href = `View/verjustificativa.php?dia=${dia}&codigo=${codigo}&linha=${linhaF}`;
  }

  function justificar(linha, codigo,modelo) {
    let linhaF = linha.replace(' ', '0');
    
    window.location.href = `Control/comecaJustificativa.php?linha=${linhaF}&codigo=${codigo}&modelo${modelo}`;

  }


  async function dados(dia) {
    const url = `http://10.1.2.251/api/produzidoDia/${dia}`;
    const response = await fetch(url);
    const data = await response.json();

    return data;
  }
  
  async function estaprogramado(dia,codigo, linha){
    const linhaF = linha.replace(' ', '0');
    const url = `http://10.1.2.251/api/estaProgramado/${dia}/${codigo}/${linhaF}`;
    console.log(url);
    const response = await fetch(url);
    const data = await response.json();
    return data;
  }
  async function estajustificado(dia,codigo, linha){
    const linhaF = linha.replace(' ', '0');
    const url = `http://10.1.2.251/api/estaJustificado/${dia}/${codigo}/${linhaF}`;
    const response = await fetch(url);
    const data = await response.json();
    return data;
  }

  async function monta_tabela(data, dia) {

      const tabela = document.createElement('table');
      tabela.classList.add('table', 'table-sm', 'table-striped', 'table-bordered', 'table-hover', 'small');
      tabela.style.width = 'fit-content';
      const thead = document.createElement('thead');
      thead.innerHTML = ` <thead>
        <tr>
        <th>Linha</th>
          <th>Codigo</th>
          <th>Modelo</th>
          <th>Produzido</th>
          <th>Programado</th>
        </tr> 
      </thead>`
      tabela.appendChild(thead);

      const tbody = document.createElement('tbody');

      for (let i = 0; i < data.length; i++) {
        const tr = document.createElement('tr');
        let estaP= await estaprogramado(dia,data[i].codigo, data[i].linha);
        console.log(`Esta Programado: ${estaP.total}`);
        if(estaP.total == 0){
          let estaJ= await estajustificado(dia,data[i].codigo, data[i].linha);
          if(estaJ.total == 0){
            console.log(`Não está Justificado: ${estaJ.total}`);
            tr.innerHTML = `<td>${data[i].linha}</td><td>${data[i].codigo}<a href='Control/comecaJustificativa.php?linha=${data[i].linha}&codigo=${data[i].codigo}&modelo=${data[i].descricao}' class='btn btn-sm btn-danger mx-1'>Não está Justificado</a></td><td>${data[i].descricao}</td><td class='text-end'>${data[i].quantidade_produzida}</td><td class='text-end'>${data[i].quantidade_programada}</td>`;
            
          }else{
            tr.innerHTML = `<td>${data[i].linha}</td><td>${data[i].codigo}<a href='Control/verJustificativa.php?linha=${data[i].linha}&codigo=${data[i].codigo}&modelo=${data[i].descricao}' class='btn btn-sm btn-primary mx-1'>Está Justificado</a></td><td>${data[i].descricao}</td><td class='text-end'>${data[i].quantidade_produzida}</td><td class='text-end'>${data[i].quantidade_programada}</td>`;           
          }
        }else{
          tr.innerHTML = `<td>${data[i].linha}</td><td>${data[i].codigo}</td><td>${data[i].descricao}</td><td class='text-end'>${data[i].quantidade_produzida}</td><td class='text-end'>${data[i].quantidade_programada}</td>`;
        }
        tbody.appendChild(tr);
        

       // const tr = document.createElement('tr');
        //tr.innerHTML = `<td>${data[i].linha}</td><td>${data[i].codigo}</td><td>${data[i].descricao}</td><td class='text-end'>${data[i].quantidade_produzida}</td><td class='text-end'>${data[i].quantidade_programada}</td>`;
       
        
                  tbody.appendChild(tr);
        }
        tabela.appendChild(tbody);

        return tabela;
      }
      async function atualiza_tabela(dia) {
        const data = await dados(dia);
        console.log('fez');

        const tabela = await monta_tabela(data, dia);
        document.getElementById('tabela_placar').innerHTML = '';
        document.getElementById('tabela_placar').appendChild(tabela);
      }
      atualiza_tabela('<?php echo $_SESSION['pcpAPI']['diaapont'] ?>')
      setInterval(async () => {
    await atualiza_tabela('<?php echo $_SESSION['pcpAPI']['diaapont'] ?>');
}, 30000);

  
</script>