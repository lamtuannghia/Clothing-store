<?php
    $server = 'localhost';
    $user = 'root';
    $pass = '';
    $database = 'rhodi_database';

    $conn = new mysqli($server, $user, $pass, $database);
    if ($conn->connect_error) {
        echo 'Ket noi that bai';
    }
?>