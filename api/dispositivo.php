<?php
// Defina um cookie exclusivo para o dispositivo, se não estiver definido
if (!isset($_COOKIE['device_id'])) {
    $device_id = uniqid(); // Cria um ID exclusivo para o dispositivo
    setcookie('device_id', $device_id, time() + 31536000, '/'); // Define um cookie com validade de 1 ano
} else {
    $device_id = $_COOKIE['device_id'];
}
?>
