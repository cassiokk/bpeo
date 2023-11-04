<?php
session_start();

// Função para exibir agendamentos e permitir exclusão
function displayAgendamentos($agendamentos_file, $vagas_disponiveis_file, $patrulhamento) {
    // Ler o endereço IP do dispositivo atual
    $device_ip = $_SERVER['REMOTE_ADDR'];

    // Ler os agendamentos do arquivo correspondente
    $agendamentos = file($agendamentos_file, FILE_IGNORE_NEW_LINES);

    echo "<h2>Agendamentos para $patrulhamento (Dispositivo IP: $device_ip)</h2>";

    if (!empty($agendamentos)) {
        echo "<ul>";
        foreach ($agendamentos as $agendamento) {
            list($agendamento_ip, $agendamento_info) = explode('|', $agendamento, 2);

            // Verificar se o agendamento foi feito a partir do dispositivo atual
            if ($agendamento_ip === $device_ip) {
                echo "<li>$agendamento_info <a href='?excluir=" . urlencode($agendamento) . "&patrulhamento=" . urlencode($patrulhamento) . "'>Excluir</a></li>";
            }
        }
        echo "</ul>";
    } else {
        echo "<p>Nenhum agendamento realizado para $patrulhamento.</p>";
    }

    echo "<p><a href='index.php'>Voltar para a página principal</a></p>";
}

if (isset($_GET['excluir']) && isset($_GET['patrulhamento'])) {
    $agendamento_para_excluir = $_GET['excluir'];
    $patrulhamento = $_GET['patrulhamento'];

    // Ler o arquivo de agendamentos correspondente ao tipo de patrulhamento
    $agendamentos_file = ($patrulhamento === 'MOTOPATRULHAMENTO') ? '../admin/agendamentos.txt' : '../admin/agendamentos2.txt';

    // Definir o arquivo de vagas disponíveis com base no tipo de patrulhamento
    $vagas_disponiveis_file = ($patrulhamento === 'MOTOPATRULHAMENTO') ? '../admin/vagas_disponiveis.txt' : '../admin/vagas_disponiveis2.txt';

    // Verificar se o dispositivo atual pode excluir o agendamento
    $device_ip = $_SERVER['REMOTE_ADDR'];
    $agendamento_info = getAgendamentoInfo($agendamento_para_excluir);
    if ($agendamento_info['ip'] === $device_ip) {
        // Remover o agendamento do arquivo de agendamentos
        $agendamentos = file($agendamentos_file, FILE_IGNORE_NEW_LINES);
        $agendamentos = array_filter($agendamentos, function ($line) use ($agendamento_para_excluir) {
            return trim($line) !== $agendamento_para_excluir;
        });
        file_put_contents($agendamentos_file, implode("\n", $agendamentos));

        // Ler a data (DD/MM/AAAA) do agendamento excluído
        $agendamento_data = getAgendamentoData($agendamento_info['info']);

        // Ler as vagas disponíveis e atualizar a quantidade
        $vagas_disponiveis_data = file_get_contents($vagas_disponiveis_file);
        $vagas_disponiveis_array = explode("\n", $vagas_disponiveis_data);

        // Percorrer as linhas e encontrar a linha com a mesma data
        foreach ($vagas_disponiveis_array as &$vaga) {
            list($data, $quantidadeVagas) = explode(' - ', $vaga);

            if ($data === $agendamento_data) {
                // Aumentar a quantidade de vagas
                $quantidadeVagas++;
                $vaga = "$data - $quantidadeVagas";
                break; // Saia do loop, pois a data foi encontrada
            }
        }

        // Salvar as alterações no arquivo de vagas disponíveis
        file_put_contents($vagas_disponiveis_file, implode("\n", $vagas_disponiveis_array));
    }

    // Redirecionar de volta para a mesma página para evitar problemas de URL
    header("Location: {$_SERVER['PHP_SELF']}");
    exit;
}

// Função para obter informações do agendamento a partir do arquivo
function getAgendamentoInfo($agendamento) {
    list($agendamento_ip, $agendamento_info) = explode('|', $agendamento, 2);
    return array(
        'ip' => $agendamento_ip,
        'info' => $agendamento_info
    );
}

// Função para obter a data (DD/MM/AAAA) do agendamento a partir do texto do agendamento
function getAgendamentoData($agendamento_text) {
    // Extrair a data (DD/MM/AAAA) do texto do agendamento
    preg_match("/(\d{2}\/\d{2}\/\d{4})/", $agendamento_text, $matches);
    return $matches[1];
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Agendamentos</title>
</head>
<body>
    <h1>Agendamentos</h1>

    <?php
    // Exibir agendamentos para MOTOPATRULHAMENTO
    displayAgendamentos('../admin/agendamentos.txt', '../admin/vagas_disponiveis.txt', 'MOTOPATRULHAMENTO');

    // Exibir agendamentos para RADIOPATRULHAMENTO
    displayAgendamentos('../admin/agendamentos2.txt', '../admin/vagas_disponiveis2.txt', 'RADIOPATRULHAMENTO');
    ?>

</body>
</html>
