<?php
// Função para limpar agendamentos duplicados em um arquivo
function limparAgendamentosDuplicados($arquivoAgendamentos, $arquivoVagasDisponiveis) {
    // Lê o arquivo de agendamentos
    $agendamentos = file($arquivoAgendamentos, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    // Array para armazenar agendamentos únicos
    $agendamentosUnicos = [];

    // Cria um array associativo para mapear as datas com o número de vagas disponíveis
    $mapaVagas = [];

    // Lê o arquivo de vagas disponíveis e preenche o mapa
    $vagasDisponiveis = file($arquivoVagasDisponiveis, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($vagasDisponiveis as $linhaVagas) {
        preg_match('/(\d{2}\/\d{2}\/\d{4}) - (\d+)/', $linhaVagas, $matches);
        $dataVagas = $matches[1];
        $numeroVagas = $matches[2];
        $mapaVagas[$dataVagas] = $numeroVagas;
    }

    // Abre um novo arquivo temporário para escrever os agendamentos únicos
    $tempFile = fopen('temp_agendamentos.txt', 'w');

    foreach ($agendamentos as $agendamento) {
        // Extrai a data e o nome do agendamento
        preg_match('/Data: (\d{2}\/\d{2}\/\d{4}) - \d+ \| Nome: (.+) \| Matrícula: \d+/', $agendamento, $matches);
        $data = $matches[1];
        $nome = $matches[2];

        // Verifica se o agendamento (data e nome) já existe nos agendamentos únicos
        $chave = "$data - $nome";
        if (!in_array($chave, $agendamentosUnicos)) {
            $agendamentosUnicos[] = $chave;
            // Escreve o agendamento no arquivo temporário
            fwrite($tempFile, $agendamento . "\n");
            // Incrementa 1 no número de vagas no mapa
            if (isset($mapaVagas[$data])) {
                $mapaVagas[$data]++;
            }
        }
    }

    // Fecha o arquivo temporário e renomeia para substituir o arquivo original
    fclose($tempFile);
    rename('temp_agendamentos.txt', $arquivoAgendamentos);

    // Atualiza o arquivo de vagas disponíveis com os novos valores
    $vagasAtualizadas = [];
    foreach ($mapaVagas as $data => $numeroVagas) {
        $vagasAtualizadas[] = "$data - $numeroVagas";
    }

    // Salva as vagas disponíveis atualizadas no arquivo correspondente
    file_put_contents($arquivoVagasDisponiveis, implode("\n", $vagasAtualizadas));

    return true;
}

// Lista de arquivos de agendamentos
$arquivosAgendamentos = ['../admin/agendamentos.txt', '../admin/agendamentos2.txt'];

// Lista de arquivos de vagas disponíveis
$arquivosVagasDisponiveis = ['../admin/vagas_disponiveis.txt', '../admin/vagas_disponiveis2.txt'];

// Chama a função para limpar os agendamentos duplicados e atualizar as vagas disponíveis
for ($i = 0; $i < count($arquivosAgendamentos); $i++) {
    if (limparAgendamentosDuplicados($arquivosAgendamentos[$i], $arquivosVagasDisponiveis[$i])) {
        echo "Agendamentos duplicados no arquivo {$arquivosAgendamentos[$i]} foram limpos com sucesso!<br>";
    } else {
        echo "Falha ao limpar agendamentos no arquivo {$arquivosAgendamentos[$i]}.<br>";
    }
}
?>