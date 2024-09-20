<?php
class ConexaoIgor
{
    private static $connection;
    private static $host = "10.1.2.251";
    private static $user = "root";
    private static $senha = "fgbraslar";
    private static $banco = "produtos_api";

    private function __construct()
    {
    }

    public static function getConnectionIgor()
    {
        try {
            if (!isset(self::$connection)) {
                self::$connection = new PDO("mysql:host=" . self::$host . ";dbname=" . self::$banco, self::$user, self::$senha);
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
            return self::$connection;
        } catch (PDOException $e) {
            $mensagem = "Drivers disponíveis: " . implode(",", PDO::getAvailableDrivers());
            $mensagem .= "\nErro: " . $e->getMessage();
            throw new Exception($mensagem);
        }
    }
}



