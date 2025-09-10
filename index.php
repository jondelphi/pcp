
<?php
session_start();
set_time_limit(0);
require('DAO/config.php');
require('DAO/configIgor.php');
//
require('Model/ClasseAcesso.php');
require('Model/ClasseApontamento.php');
require('Model/ClasseAlmoco.php');
require('Model/ClasseODF.php');
require('Model/ClasseLinha.php');
require('Model/ClasseProduto.php');
require('Model/ClasseProgramacao.php');
require('Model/ClasseCarga.php');
require('Model/ClasseRequest.php');
require('Model/ClasseRelatorio.php');

if(!isset($_SESSION['pcpAPI']['diaapont'])){
    $_SESSION['pcpAPI']['pagina']='View/login.php';
}else{
$_SESSION['pcpAPI']['filtro']=false;
if(isset($_SESSION['pcpAPI']['busca'])){
    if ($_SESSION['pcpAPI']['busca']===false) {
        $_SESSION['pcpAPI']['sqlODF']="SELECT tbpcp.*,tbproduto.codigo as 'codigo', tbproduto.descricao as 'descri',tblinha.linha as 'linhamontagem'
        FROM tbpcp 
        INNER JOIN tbproduto ON tbproduto.id = tbpcp.idproduto
        INNER JOIN tblinha ON tblinha.id = tbpcp.idlinha
        WHERE tbpcp.dataentregue='" . $_SESSION['pcpAPI']['diaapont'] . "'
        ORDER BY tblinha.linha ASC, tbproduto.codigo ASC ";
    }else{
        $_SESSION['pcpAPI']['busca']=false;
        $_SESSION['pcpAPI']['filtro']=true;
    }
}else{
    $_SESSION['pcpAPI']['busca']=false;
    $_SESSION['pcpAPI']['filtro']=true;
}
}
$conn = ConexaoMysql::getConnectionMysql();
$acesso = new Acesso;
$acesso->carrega();
$script='';
if (isset($_SESSION['pcpAPI']['logado'])) {
    if ($_SESSION['pcpAPI']['logado'] !== true) {
        $_SESSION['pcpAPI']['pagina'] = 'View/login.php';
    }
}else{
    $_SESSION['pcpAPI']['pagina'] = 'View/login.php';  
}
if (isset($_SESSION['pcpAPI']['erro'])){
   if ($_SESSION['pcpAPI']['erro']==true) {
    $script="<script>";
    $script.="alert('";
    $script.=$_SESSION['pcpAPI']['mensagem'];
    $script.="')";
    $script.="</script>";
   }
   unset($_SESSION['pcpAPI']['erro']);
   
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="content/icone.png">
    <title><?php echo ($_SESSION['pcpAPI']['titulo']) ?></title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="bootstrap/css/bootstrap-icons.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <link href="https://cdn.datatables.net/v/dt/jq-3.7.0/jszip-3.10.1/dt-2.3.4/af-2.7.1/b-3.2.5/b-colvis-3.2.5/b-html5-3.2.5/b-print-3.2.5/cr-2.1.1/cc-1.1.0/date-1.6.0/fc-5.0.5/fh-4.0.3/kt-2.12.1/r-3.0.6/rg-1.6.0/rr-1.5.0/sc-2.4.3/sb-1.8.4/sp-2.3.5/sl-3.1.0/sr-1.4.2/datatables.min.css" rel="stylesheet" integrity="sha384-beSDlq/IbhBcPS9mRi0wUVGg+eZDOGXLak+czHUF9Ml4kXTZMb82dsaSo94YlvJ9" crossorigin="anonymous">
 
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js" integrity="sha384-VFQrHzqBh5qiJIU0uGU5CIW3+OWpdGGJM9LBnGbuIH2mkICcFZ7lPd/AAtI7SNf7" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js" integrity="sha384-/RlQG9uf0M2vcTw3CX7fbqgbj/h8wKxw7C3zu9/GxcBPRKOEcESxaxufwRXqzq6n" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/v/dt/jq-3.7.0/jszip-3.10.1/dt-2.3.4/af-2.7.1/b-3.2.5/b-colvis-3.2.5/b-html5-3.2.5/b-print-3.2.5/cr-2.1.1/cc-1.1.0/date-1.6.0/fc-5.0.5/fh-4.0.3/kt-2.12.1/r-3.0.6/rg-1.6.0/rr-1.5.0/sc-2.4.3/sb-1.8.4/sp-2.3.5/sl-3.1.0/sr-1.4.2/datatables.min.js" integrity="sha384-W5zJ9gbD4ORLFw1X9uTFIUC9QXzqNfQZkrcR5QJqKNkWgUTs4Cxss39nU95MVL6Q" crossorigin="anonymous"></script>
    <style>
    i {
            padding: 2px;
            margin: 2px;
        }
    .textovertical{
    writing-mode: vertical-rl;
    text-align: center;
    padding: 2px 3px;
    margin: 2px 3px;
}
table.tabelaCarga{
  
    padding: 2px;
    width: fit-content;
    overflow-x: auto; /* Adiciona barra de rolagem horizontal quando necessário */
    border-collapse: collapse;
}
table.tabelaCarga th {
    border-collapse: collapse;
    border: 1px solid #000;
    padding: 2px 3px;
}
table.tabelaCarga td{
    border: 1px solid #000;
    padding: 2px 3px;

}




    @media print{
        body * {
            display: none;
        }
        #exporta{
            visibility: visible;
        }
        #imprime{
            visibility: visible;
        }
  
    }

    </style>
    <script>
        document.addEventListener("keydown", function(e) {
            if (e.keyCode === 13) {
               // e.preventDefault();
            }
        });
        function mensagem(link){
            var str="Deseja editar a etiqueta?";
            var con=confirm(str);
            if (con==true) {
                window.location.href=link;
            }   
        }
        function conEdita(link,odf){
         var str="Deseja editar a ODF: \n"+odf+" ?";
            var con=confirm(str);
            if (con==true) {
                window.location.href=link;
            }    
        }

        function conEditaProgramacao(link){
         var str="Deseja editar a programação";
            var con=confirm(str);
            if (con==true) {
                window.location.href=link;
            }    
        }
        function conExclui(link,odf)
        {
            var str="Deseja excluir a ODF: \n"+odf+" ?";
            var con=confirm(str);
            if (con==true) {
                window.location.href=link;
            }
        }
        function conExcluiProgramacao(link)
        {
            var str="Deseja excluir a Programação?";
            var con=confirm(str);
            if (con==true) {
                window.location.href=link;
            }
        }
        function conDetalhes(link,odf)
        {
            var str="Deseja ver detalhes da ODF: \n"+odf+" ?";
            var con=confirm(str);
            if (con==true) {
                window.open(link);
            }
        }
        function newPopup(pagina){
varWindow = window.open (
pagina,
'pagina',
"width=550, height=320, top=100, left=110, scrollbars=yes" );
}


    </script>
   
</head>
<body>
    <?php
    if (isset($_SESSION['pcpAPI']['logado'])) {
        if ($_SESSION['pcpAPI']['logado'] == true) {
            include("template/topo.php");
        }
    }
    ?>
    <div class="container p-2 m-2">
        <?php
        $acesso->mostrapagina($_SESSION['pcpAPI']['pagina']);
        ?>
    </div>
    <script src="bootstrap/js/bootstrap.js"></script>
    <script src="bootstrap/js/bootstrap.bundle.js"></script>
    <script src="bootstrap/js/popper.min.js"></script>
    <footer>
        <?php
        include("template/footer.php");

       
        ?>
        
    </footer>
    <?php
     echo $script;

    ?>
    <script>
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
  return new bootstrap.Tooltip(tooltipTriggerEl)
})
    </script>
    <script src="content/quagga.min.js"></script>
    <script>
        let manda = false
        function validaFrom(e) {
            let cp = document.querySelector('#erro')
            if (e.length < 18) {

                cp.style.display = 'block'
                cp.innerText = 'Número Inválido'
            } else {
                cp.innerText = ''
                cp.style.display = 'none'
                new Audio("beep.wav").play()
                manda = true
            }
            console.log(e.length)
        }
        function envia(){
            return manda;
        }
        function sucesso(){
            new Audio("sucesso.wav").play()
        }
      let lidas = []
      let stcon = document.querySelector('#camera');
      function comeca(){

    stcon.style.display = 'block';
    Quagga.init({
    inputStream: {
        name: "Live",
        type: "LiveStream",
        target: document.querySelector('#camera'), // Elemento onde o vídeo será exibido
        constraints: {
            width: 640,
            height: 480,
            facingMode: "environment" // Para usar a câmera traseira do dispositivo
        },
    },
    decoder: {
        readers: ["code_128_reader"] // Adicione os leitores necessários
    },
    locate: true, // Defina como true para localizar o código de barras na imagem
    locator: {
        patchSize: "medium", // small, medium, large
        halfSample: true
    },
    numOfWorkers: navigator.hardwareConcurrency, // Use o número de threads disponível no navegador
}, function (err) {
    if (err) {
        console.log(err);
        return;
    }
    console.log("Initialization finished. Ready to start");
    Quagga.start();
});

    }

    /*  */
// Definir uma função de leitura
function tryReading() {
    Quagga.onDetected(function(data){       
        let etiqueta = data.codeResult.code;
        let cp = document.querySelector('#etiqueta');         
        cp.value = etiqueta;    
        let duplo = lidas.some(item => item === etiqueta);  // Correção aqui
        
        if (duplo) {
            cp.value = etiqueta;    
            manda = true;
            envia();
            playBeep();
            para();
        } else {
            lidas.push(etiqueta);
        }
    });
}

// Função para iniciar a leitura e continuar tentando se necessário
function startReading() {
    Quagga.onDetected(function(data) {
        let etiqueta = data.codeResult.code;
        let cp = document.querySelector('#etiqueta');         
        cp.value = etiqueta;    
        let duplo = lidas.some(item => item === etiqueta);
        
        if (duplo) {
            cp.value = etiqueta;    
            manda = true;
            
            envia();
            playBeep();
            para();
            lidas.length = 0;
        } else {
            lidas.push(etiqueta);
            // Tentar ler novamente
            startReading();
        }
    });
}

// Chamar a função de leitura inicial
startReading();

    /*  */
      function lista (){

      }
      function playBeep() {
            // Emitir o beep
            document.getElementById('beepSound').play();

            // A lógica da sua função aqui
            console.log('Função executada');
        }
        function para(){
            Quagga.stop();
            stcon.style.display = 'none';
        }
    </script>
   <script src="https://unpkg.com/htmx.org@2.0.0" integrity="sha384-wS5l5IKJBvK6sPTKa2WZ1js3d947pvWXbPJ1OmWfEuxLgeHcEbjUUA5i9V5ZkpCw" crossorigin="anonymous"></script>
</body>
</html>