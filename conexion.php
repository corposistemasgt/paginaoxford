<?php
class Conexion extends PDO
{
    private $hostBd = '34.203.242.2:3306';
    private $nombreBd = 'corpo_inscripciones';
    private $usuarioBd = 'corpo';
    private $passwordBd = 'CorpoS24#';

    public function __construct()
    {
        try {
            parent::__construct('mysql:host=' . $this->hostBd . ';dbname=' . $this->nombreBd . ';charset=utf8', $this->usuarioBd, $this->passwordBd, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
            exit;
        }
    }
}


$conn = new Conexion();
?>
