<?php
session_start();

// Substitua "SEUMATRICULA" pelo valor apropriado da matrícula do usuário
$matricula = "SEUMATRICULA";

if (isset($_SESSION['agendamentos'][$matricula]) && !empty($_SESSION['agendamentos'][$matricula])) {
    echo "<h1>Seus Agendamentos</h1>";
    echo "<ul>";
    foreach ($_SESSION['agendamentos'][$matricula] as $agendamento) {
        echo "<li>$agendamento <a href='excluir_agendamento.php?agendamento=$agendamento'>Excluir</a></li>";
    }
    echo "</ul>";
} else {
    echo "Nenhum agendamento encontrado.";
}
?>
