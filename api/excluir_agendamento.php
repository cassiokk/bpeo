<?php
// Iniciar a sessão
session_start();








// Função para exibir agendamentos e permitir exclusão
function displayAgendamentos($agendamentos_file, $vagas_disponiveis_file, $patrulhamento) {
    // Ler os agendamentos do arquivo correspondente
    $agendamentos = file($agendamentos_file, FILE_IGNORE_NEW_LINES);

    echo "<h2>Agendamentos para $patrulhamento</h2>";

    if (!empty($agendamentos)) {
        echo "<ul>";
        foreach ($agendamentos as $agendamento) {
            if (preg_match("/\d{2}\/\d{2}\/\d{4}/", $agendamento)) {
                echo "<li>$agendamento <a href='?excluir=" . urlencode($agendamento) . "&patrulhamento=" . urlencode($patrulhamento) . "'>Excluir</a></li>";
            }
        }
        echo "</ul>";
    } else {
        echo "<p>Nenhum agendamento realizado para $patrulhamento.</p>";
    }

    echo "<p><a href='" . getAdminPageUrl($patrulhamento) . "'>Voltar para a página principal</a></p>";
}


if (isset($_GET['excluir']) && isset($_GET['patrulhamento'])) {
    $agendamento_para_excluir = $_GET['excluir'];
    $patrulhamento = $_GET['patrulhamento'];

    // Ler o arquivo de agendamentos correspondente ao tipo de patrulhamento
    $agendamentos_file = ($patrulhamento === 'MOTOPATRULHAMENTO') ? '../admin/agendamentos.txt' : '../admin/agendamentos2.txt';

    // Definir o arquivo de vagas disponíveis com base no tipo de patrulhamento
    $vagas_disponiveis_file = ($patrulhamento === 'MOTOPATRULHAMENTO') ? '../admin/vagas_disponiveis.txt' : '../admin/vagas_disponiveis2.txt';

    // Remover o agendamento do arquivo de agendamentos
    $agendamentos = file($agendamentos_file, FILE_IGNORE_NEW_LINES);
    $agendamentos = array_filter($agendamentos, function ($line) use ($agendamento_para_excluir) {
        return trim($line) !== $agendamento_para_excluir;
    });
    file_put_contents($agendamentos_file, implode(PHP_EOL, $agendamentos) . PHP_EOL);

    // Ler a data (DD/MM/AAAA) do agendamento excluído
    $agendamento_data = getAgendamentoData($agendamento_para_excluir);

    // Ler as vagas disponíveis e atualizar a quantidade
    $vagas_disponiveis_data = file_get_contents($vagas_disponiveis_file);
    $vagas_disponiveis_array = explode("\n", $vagas_disponiveis_data);

    $data_encontrada = false;

    foreach ($vagas_disponiveis_array as &$vaga) {
        list($data, $quantidadeVagas) = explode(' - ', $vaga);

        if ($data === $agendamento_data) {
            // Aumentar a quantidade de vagas em 1 unidade
            $quantidadeVagas++;
            $vaga = "$data - $quantidadeVagas";
            $data_encontrada = true;
            break; // Saia do loop, pois a data foi encontrada
        }
    }

    // Se a data não foi encontrada, adicione uma nova entrada com 1 vaga
    if (!$data_encontrada) {
        $vagas_disponiveis_array[] = "$agendamento_data - 1";
    }

    // Salvar as alterações no arquivo de vagas disponíveis
    file_put_contents($vagas_disponiveis_file, implode("\n", $vagas_disponiveis_array));
    
    // Redirecionar para a página excluir_agendamento.php
    header('Location: excluir_agendamento.php');
    exit; // Certifique-se de que o código seja encerrado após o redirecionamento
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

// Função para determinar a página de admin com base no tipo de patrulhamento
function getAdminPageUrl($patrulhamento) {
    if ($patrulhamento === 'MOTOPATRULHAMENTO') {
        return 'admin.php'; // Redirecionar para admin.php
    } elseif ($patrulhamento === 'RADIOPATRULHAMENTO') {
        return 'admin2.php'; // Redirecionar to admin2.php
    } else {
        return 'admin.php'; // Página de fallback se o tipo de patrulhamento não for reconhecido
    }
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

<p><strong><span style="color:red;">QUANDO UM AGENDAMENTO É EXCLUÍDO, A VAGA VOLTA A FICAR DISPONÍVEL PARA OS USUÁRIOS.</span></strong></p>
<p><strong>sugestão: quando solicitarem permuta, ou selecionaram a vaga errada, exclua as vagas e peça para que os policiais preencham novamente.</strong></p>

</body>
</html>
