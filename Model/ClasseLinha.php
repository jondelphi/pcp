<?php
    class Linha
    {
        public function ConfereLinha($linha,$conn){
            $sql="SELECT * FROM tblinha WHERE linha='".$linha."'";
            $sel=$conn->prepare($sql);
            $sel->execute();
            $nr=$sel->rowCount();
            if ($nr>0) {
                return true;
            } else {
                return false;
            }           
        }
        public function trazidlinha($linha){
            $conn=ConexaoMysql::getConnectionMysql();
            $sql="SELECT * FROM tblinha WHERE linha='".$linha."'";
            $sel=$conn->prepare($sql);
            $sel->execute();
            $nr=$sel->rowCount();
            if ($nr>0) {
                while ($a = $sel->fetch(PDO::FETCH_ASSOC)) {
                    return $a['id'];
                }
                
            }        
        }

        public function trazlinha($id){
            $conn=ConexaoMysql::getConnectionMysql();
            $sql="SELECT * FROM tblinha WHERE id='".$id."'";
            $sel=$conn->prepare($sql);
            $sel->execute();
            $nr=$sel->rowCount();
            if ($nr>0) {
                while ($a = $sel->fetch(PDO::FETCH_ASSOC)) {
                    return $a['linha'];
                }
                
            }        
        }

        public function colaboradores($linha){
            $conn=ConexaoMysql::getConnectionMysql();
         $dia=$_SESSION['pcpAPI']['diaapont'];
         $id=$linha;
         $sql="SELECT sum(quantidade) as total from colaboradores where dia_presenca='{$dia}' and idlinha={$id}";
        
         $sql=$conn->prepare($sql);
         $sql->execute();
         $item = $sql->fetch(PDO::FETCH_ASSOC);
         if(!is_null($item['total'])){
            return $item['total'];
         }else{
            return 0;
         }
         
        }

        public function MostraLinha($conn){
            $sql="SELECT * FROM tblinha ORDER by ID asc";
            $sel=$conn->prepare($sql);
            $sel->execute();
            $nr=$sel->rowCount();
            if ($nr>0) {
              ?>
              <table class="table table-striped table-bordered table-dark table-hover" style="width: fit-content;">
                <thead>
                    <tr>
                        <th style="min-width: 200px;">Linha</th>
                        <th>Colaboradores</th>
                        <th style="min-width: 200px;">Operação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($l=$sel->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                        <tr>
                            <td><?php echo($l['linha']);?></td>
                            <td>
                                <form action="Control/colaboradores.php" method="post">
                                    <div class="input-group">
                                        <input type="hidden" name="idlinha" value="<?php echo($l['id']); ?>">
                                        <input  class= "form form-control text-end" type="number" name="colaboradores" inputmode="numeric" required value="<?php echo($this->colaboradores($l['id']));?>">
                                        <input type="submit" value="Atualiza" class="btn btn-success">
                                    </div>
                                    
                                </form>
        
                            </td>
                            <td class="text-center">
                                <?php
                                $link="onclick('";
                                $link.="Control/editalinha.php?id=";
                                $link.=$l['id'];
                                $link.="&&linha=";
                                $link.=$l['linha'];
                                $link.="','";
                                $link.=$l['linha'];
                                $link.="')";
                                ?>
                                <button <?php echo $link?> class="btn btn-warning">Edita</button>
                                
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
              </table>
              <?php
            } else {
                ?>
                <h5 class="bg-warning text-bg-warning text-center fw-bold">Não tem linhas cadastradas</h5>
                <?php
            }           
        }
        public function idLinha($linha){
            $conn=ConexaoMysql::getConnectionMysql();
            $sql="SELECT id FROM tblinha WHERE linha='".$linha."'";
            $sel=$conn->prepare($sql);
            $sel->execute();
            $nr=$sel->rowCount();
            if ($nr>0) {
                while ($l=$sel->fetch(PDO::FETCH_ASSOC)) {
                    return $l['id'];
                  }
            } else {
                return 0; 
            }           
        }

        public function montaoptionlinha($conn){
            $sql="SELECT * FROM tblinha ORDER BY id ASC";
            $sel=$conn->prepare($sql);
            $sel->execute();
            $nr=$sel->rowCount();
            $option="";
            if ($nr>0) {
                while ($l=$sel->fetch(PDO::FETCH_ASSOC)) {
                  $option=$option."<option value='".$l['id']."'>".$l['linha']."</option>";
                }
                echo($option);
            } else {
                $option="<option value='Inexistente'>Inexistente</option>";
                echo($option);
            }           

        }

        public function montaoptionlinhaEDT($conn){
            $sql="SELECT * FROM tblinha ORDER BY id ASC";
            $sel=$conn->prepare($sql);
            $sel->execute();
            $nr=$sel->rowCount();
            $option="";

            if ($nr>0) {
                while ($l=$sel->fetch(PDO::FETCH_ASSOC)) {
                    if( $_SESSION['pcpAPI']['idlinha']==$l['id']){
                        $option=$option."<option value='".$l['id']."' selected>".$l['linha']."</option>";
                    }else{
                  $option=$option."<option value='".$l['id']."'>".$l['linha']."</option>";
                    }
                }
                echo($option);
            } else {
                $option="<option value='Inexistente'>Inexistente</option>";
                echo($option);
            }           

        }
    }
