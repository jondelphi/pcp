<?php
class Almoco
{
    public function saida(){
        $dia=new DateTime($_SESSION['pcpAPI']['diaapont']);
        $fdia=$dia->format('Y-m-d');
        $linha=$_GET['linha'];
        $sel="SELECT horasaida FROM tbalmocolinha 
        inner join tblinha on tblinha.id=tbalmocolinha.idlinha='$linha'
        where tblinha.linha='$linha' and diasaida='$fdia'";
        $con=ConexaoMysql::getConnectionMysql();
        $sel=$con->prepare($sel);
        $sel->execute();
        if ($sel->rowCount()<>0) {
            while ($k=$sel->fetch(PDO::FETCH_ASSOC)) {
                return $k['horasaida'];
            }            
        }else{
            return false;
        }
    }

    public function entrada(){
        $dia=new DateTime($_SESSION['pcpAPI']['diaapont']);
        $fdia=$dia->format('Y-m-d');
        $linha=$_GET['linha'];
        $sel="SELECT horaentrada FROM tbalmocolinha 
          inner join tblinha on tblinha.id=tbalmocolinha.idlinha='$linha'
        where tblinha.linha='$linha' and diasaida='$fdia'";
        $con=ConexaoMysql::getConnectionMysql();
        $sel=$con->prepare($sel);
        $sel->execute();
        if ($sel->rowCount()<>0) {
            while ($k=$sel->fetch(PDO::FETCH_ASSOC)) {
                return $k['horaentrada'];
            }            
        }else{
            return false;
        }
    }
    public function saidaentrada(){
        $almoco=array();
        $saida=$this->saida();
        $entrada=$this->entrada();
        if ($saida==false&&$entrada==false) {
           
            return false;

        }elseif ($entrada==false) {
            
            return false;
        }else{
           $hs=new DateTime($saida);
           $he=new DateTime($entrada);
            $almoco['saida']=$hs->format("H:i");
            $almoco['entrada']=$he->format("H:i");;
            $almoco['intervalosaida']=$this->qualintervalosaiu($saida);
            $almoco['intervaloentrada']=$this->qualintervaloentrou($entrada);
            return $almoco;
            
        }
    }
    public function qualintervalosaiu($hora){
        switch ($hora) {
            case $hora < '07:40:00':
                return 0;
                break;
            case $hora < '08:41':
                return 1;
                break;
            case $hora < '09:41':
                return 2;
                break;
            case $hora < '10:41':
                return 3;
                break;
            case $hora < '11:41':
                return 4;
                break;
            case $hora < '12:41':
                return 5;
                break;
            case $hora < '13:41':
                return 6;
                break;
            case $hora < '14:41':
                return 7;
                break;
            case $hora < '15:41':
                return 8;
                break;
            case $hora < '16:41':
                return 9;
                break;
            case $hora < '17:41':
                return 10;
                break;
            case $hora < '18:41':
                return 11;
                break;
            case $hora >= '18:41':
                return 12;
                break;
        }
    }
    public function qualintervaloentrou($hora){
        switch ($hora) {
            case $hora < '07:40:00':
                return 0;
                break;
            case $hora < '08:41':
                return 1;
                break;
            case $hora < '09:41':
                return 2;
                break;
            case $hora < '10:41':
                return 3;
                break;
            case $hora < '11:41':
                return 4;
                break;
            case $hora < '12:41':
                return 5;
                break;
            case $hora < '13:41':
                return 6;
                break;
            case $hora < '14:41':
                return 7;
                break;
            case $hora < '15:41':
                return 8;
                break;
            case $hora < '16:41':
                return 9;
                break;
            case $hora < '17:41':
                return 10;
                break;
            case $hora < '18:41':
                return 11;
                break;
            case $hora >= '18:41':
                return 12;
                break;
        }
    }

    public function retorna($sql){
        try{           
        $con=ConexaoMysql::getConnectionMysql();
        $sql=$con->prepare($sql);
        $sql->execute();
        return $sql;       
        }catch(PDOException $e){
            echo $e->getMessage();
        }       
        
    }

    public function mostraAlmoco(){
        $dia= $_SESSION['pcpAPI']['diaapont'];
        $sql="SELECT 
        tbalmocolinha.*, 
        tblinha.linha as 'linha' 
        FROM tbalmocolinha
        INNER JOIN tblinha on tblinha.id = tbalmocolinha.idlinha
        WHERE diasaida='$dia' ORDER BY tblinha.linha ASC
         ";
         $sql=$this->retorna($sql);
        
         if($sql->rowCount()<>0){
            ?>
            <table class="table table-bordered table-striped table-dark">
                <thead>
                    <tr class="text-center">
                        <th>Linha</th>
                        <th>Hora saída</th>
                        <th>Hora entrada</th>
                        <th>Editar</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                while ($l=$sql->fetch(PDO::FETCH_ASSOC)) {
                    if(isset($l['horaentrada'])){
                        $entrada=new DateTime($l['horaentrada']);
                        $entradaf=$entrada->format('H:i:s');
                    }else{
                        $entradaf='' ; 
                    }
                    
                    $saida=new DateTime($l['horasaida']);
                   ?>
                   <tr>
                    <td class="text-start"><?php echo($l['linha']); ?></td>
                    <td class="text-end"><?php echo($saida->format('H:i:s')); ?></td>
                    <td class="text-end"><?php echo($entradaf); ?></td>
                    <td>
                        <a href="Control/editaalmoco.php?id=<?php echo $l['id']?>&idlinha=<?php echo $l['idlinha']?>" class="btn btn-warning">Editar</a> 
                        <a href="Control/apagaalmoco.php?id=<?php echo $l['id']?>" class="btn btn-danger">Apaga Tudo</a> 
                    </td>
                   </tr>
                  <?php
                }
                ?>
                </tbody>

            </table>
            <?php
         }

        
    }

    public function criaformulario(){
        $sql="SELECT tbalmocolinha.*, tblinha.linha as 'linha' FROM tbalmocolinha
        INNER JOIN tblinha ON tblinha.id=tbalmocolinha.idlinha
        WHERE tbalmocolinha.id=".$_SESSION['pcpAPI']['idalmoco'];
  
        $sql=$this->retorna($sql);
        while ($l=$sql->fetch(PDO::FETCH_ASSOC)) {
            $linha=$l['linha'];
            $horasaida=$l['horasaida'];
            if(isset($l['horaentrada'])){
                $entrada=new DateTime($l['horaentrada']);
                $horaentrada=$entrada->format('H:i:s');
            }else{
                $horaentrada='' ; 
            }
        }
        ?>
        <form action="Control/confirmaedicaoalmoco.php" method="post">
        <h5><?php echo $linha?></h5>
            <div class="input-group" style="max-width: fit-content;">
              
                <label for="horasaida" class="input-group-text">Saída</label>
                <input type="text" name="horasaida" value="<?php echo $horasaida ?>" class="form-control input-group" required>
                <label for="horaentrada" class="input-group-text">Entrada</label>
                <input type="text" name="horaentrada" value="<?php echo $horaentrada ?>" class="form-control input-group" required>
                <input type="submit" value="Alterar" class="btn btn-success input-group-text">

            </div>
        </form>
        <?php
    }
   

    public function etiquetasdalinha(){
        $dia= $_SESSION['pcpAPI']['diaapont'];
        $linha=$_SESSION['pcpAPI']['idlinha'];
        $sql="SELECT * FROM tbapontamento where idlinha=$linha AND diaapont='$dia' ORDER BY id DESC";
        $sql=$this->retorna($sql);
        if ($sql->rowCount()<>0) {
            ?>
               <table class="table table-dark table-bordered table-striped" style="width: fit-content;">
               <thead>
                    <tr class="text-center">
                        <th colspan="2">Apontamentos</th>
                    </tr>

                    <tr>
                       
                        <th><i class="bi bi-ticket-detailed"></i>Etiqueta</th>
                        <th><i class="bi bi-alarm"></i>HORA</th>
                    </tr>
                </thead>
                <?php
                while ($l = $sql->fetch(PDO::FETCH_ASSOC)) {
                ?>
                    <tr>
                       <td><?php echo $l['etiqueta']; ?></td>
                        <td class="text-end"><?php echo $l['horaapont']; ?></td>
                        </td>
                    </tr>
                <?php
                }
                ?>
               </table>
            <?php
        }

        
    }
    
}
?>