<?php

try{
    $conn = new pdo('mysql:host=localhost;dbname=test', 'root', '');
}
catch(PDOException $e){
    echo "Erro ao se conectar: Erro ". $e->getMessage();
}
