
<div class="container m-2 p-2">    
    <div class="container">
        <div class="row" >
            <div class="col m-2 bg-black text-white text-center p-2" hx-trigger="load" hx-get="http://10.1.2.251/api2/getTotalDia/<?php echo $_SESSION['pcpAPI']['diaapont']?>">              
            </div>    
            <div class="col m-2 p-2" hx-trigger="load"  hx-get="http://10.1.2.251/api2/getResumoDiaLinhas/<?php echo $_SESSION['pcpAPI']['diaapont']?>">              
            </div>        
        </div>
        <div class="row">           
            <div class="col m-2 p-2" hx-trigger="load" hx-get="http://10.1.2.251/api2/getResumoDiaValor/<?php echo $_SESSION['pcpAPI']['diaapont']?>">
                
            </div>     
        </div>
    </div>
</div>

<?php 
function calcularMediaHora($quantidade, $dia) {
    // Verifica se o dia é igual ao dia atual
    $dataAtual = date('Y-m-d');
    if ($dia == $dataAtual) {
      // Calcula a média por hora desde as 7:40 até a hora atual
      $horaAtual = date('H:i');
      if ($horaAtual > '07:40') {
        $tempoTotal = strtotime($horaAtual) - strtotime('07:40:00');
        $horasTrabalhadas = round($tempoTotal / 3600, 2);
        $mediaHora = $quantidade / $horasTrabalhadas;
        return number_format($mediaHora, 2, '.', '');
      } else {
        return 0;
      }
    } elseif ($dia < $dataAtual) {
      // Calcula a média por hora considerando um dia de trabalho normal (7:40 às 17:25)
      $horasTrabalhadas = 9.75;
      $mediaHora = $quantidade / $horasTrabalhadas;
      return number_format($mediaHora, 2, '.', '');
    } else {
      // Retorna nada se o dia for posterior ao dia atual
      return null;
    }
  }
  
?>