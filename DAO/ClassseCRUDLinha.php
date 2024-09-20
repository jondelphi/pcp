<?php
require('config.php');
class DAOlinha extends ConexaoMysql
{
    //criando
    public function CreateTbLinha()
    {   
        $conn= ConexaoMysql::getConnectionMysql();
        $teste = array();
        $sql = "CREATE TABLE IF NOT EXISTS 'tblinha' (
                'linha' varchar(40) NOT NULL,
                PRIMARY KEY ('linha')
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        $teste['erro'] = false;
        try {
            $query = $conn->prepare($sql);
            $query->execute();
        } catch (PDOException $e) {
            $teste['mensagem'] = $e->getMessage();
            $teste['erro'] = true;
        }
        return $teste;
    }
    //Lendo
    public function LeiaLinha($sql)
    {
        $conn= ConexaoMysql::getConnectionMysql();
        $teste = array();
        $dados = array();
        $teste['erro'] = false;
        try {
            $query = $conn->prepare($sql);
            $query->execute();
        } catch (PDOException $e) {
            $teste['mensagem'] = $e->getMessage();
            $teste['erro'] = true;
        }
        if($teste==false&&$query->rowCount()>0){
           while ($l=$query->fetch(PDO::FETCH_ASSOC)) {
             array_push($dados,$l['linha']);
           }   
           $teste['dados']=$dados;          
        }else{
            $teste['erro'] = true;  
            $teste['mensagem'] = 'Não há registros';
        }
        return $teste;
    }

    //cadastrando
    public function InsereLinha($linha)
    {
        $conn= ConexaoMysql::getConnectionMysql();
        $teste = array();
        $sql = "INSERT INTO tblinha(linha) VALUES ('$linha')";
        $teste['erro'] = false;
        try {
            $query = $conn->prepare($sql);
            $query->execute();
        } catch (PDOException $e) {
            $teste['mensagem'] = $e->getMessage();
            $teste['erro'] = true;
        }
        return $teste;
    }

    //editando
    public function EditaLinha( $linha)
    {
        $conn= ConexaoMysql::getConnectionMysql();
        $teste = array();
        $sql = "UPDATE tblinha SET linha='$linha'";
        $teste['erro'] = false;
        try {
            $query = $conn->prepare($sql);
            $query->execute();
        } catch (PDOException $e) {
            $teste['mensagem'] = $e->getMessage();
            $teste['erro'] = true;
        }
        return $teste;
    }
    //editando
    public function DeletaLinha($linha)
    {
        $conn= ConexaoMysql::getConnectionMysql();
        $teste = array();
        $sql = "UPDATE tblinha SET linha='$linha'";
        $teste['erro'] = false;
        try {
            $query = $conn->prepare($sql);
            $query->execute();
        } catch (PDOException $e) {
            $teste['mensagem'] = $e->getMessage();
            $teste['erro'] = true;
        }
        return $teste;
    }
}
