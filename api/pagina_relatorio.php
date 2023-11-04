<!DOCTYPE html>
<html>
<head>
    <title>Relatório MOTOPATRULHAMENTO</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #ccc;
        }

        th, td {
            padding: 8px;
            text-align: left;
            color: #000;
        }

        th {
            background-color: #f2f2f2;
        }

        /* Estilo para botões */
        .button-container {
            text-align: left; /* Botão à esquerda */
        }

        .button {
            color: white;
            background-color: #ff5b5b; /* Cor vermelha */
            border: none;
            padding: 8px 16px;
            margin: 5px;
            cursor: pointer;
            border-radius: 5px;
        }

        .button:hover {
            background-color: #ff3333;
        }

        .warning-text {
            color: #ff5b5b;
            text-align: center;
            margin-top: 10px;
        }

        .button-blue {
            background-color: #3498db; /* Cor azul */
        }

        .button-blue:hover {
            background-color: #2980b9; /* Cor azul mais escura quando hover */
        }

        .bold {
            font-weight: bold;
        }

        /* Estilo para alinhar botão ao centro do aviso */
        .button-center {
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
<h1>CVP <span style="color: red;"> <span style="text-decoration: underline; font-weight: bold;">RELATÓRIO MOTOPATRULHAMENTO</span> </span> 16H ÀS 00H</h1>
    <?php
    // Verifique se o usuário é um administrador (coloque sua lógica de verificação aqui)
    $usuarioAdministrador = true; // Defina como verdadeiro se for administrador, caso contrário, defina como falso.

    $agendamentosFile = 'agendamentos.txt';

    if (file_exists($agendamentosFile)) {
        $agendamentos = file($agendamentosFile, FILE_IGNORE_NEW_LINES);

        if (!empty($agendamentos)) {
            $agendamentosPorData = [];

            foreach ($agendamentos as $agendamento) {
                if (preg_match('/\d{2}\/\d{2}\/\d{4}/', $agendamento, $matches)) {
                    $data = $matches[0];
                    $nome = str_replace('Data: ' . $data . ' - ', '', $agendamento);
                    $nome = trim($nome);

                    if (!isset($agendamentosPorData[$data])) {
                        $agendamentosPorData[$data] = [];
                    }

                    // Use uma expressão regular para pegar a numeração específica da data
                    preg_match('/\d+\s+\|\s+(.+)/', $nome, $matches);
                    $nomeFormatado = $matches[1];

                    $agendamentosPorData[$data][] = $nomeFormatado;
                }
            }

            // Ordene o array pela data, em ordem crescente
            ksort($agendamentosPorData);

            echo '<table>';

            foreach ($agendamentosPorData as $data => $nomes) {
                echo '<tr>';
                echo "<th>" . $data . "</th>";
                echo '</tr>';
                foreach ($nomes as $nome) {
                    echo '<tr>';
                    echo '<td>' . $nome . '</td>';
                    echo '</tr>';
                }
            }

            echo '</table>';
        } else {
            echo '<p>Nenhum agendamento feito por usuários.</p>';
        }
    } else {
        echo '<p>O arquivo de agendamentos não foi encontrado.</p>';
    }

    // Verifique se o usuário é um administrador para exibir os botões "Limpar Relatório" e "Voltar à Página do Administrador"
    if ($usuarioAdministrador) {
        echo '
        <div class="button-container">
            <div class="button-center">
                <form method="post" action="limpar_relatorio.php">
                    <button type="submit" name="limpar_relatorio" class="button">Limpar Relatório e Vagas Disponíveis</button>
                </form>
            </div>
            <p class="warning-text bold">(ATENÇÃO: ao limpar o relatório e vagas, todos os dados serão perdidos e será necessário o preenchimento das vagas disponíveis novamente.)</p>
        </div>
        <p><a href="admin.php" class="button button-blue">Voltar para MOTOPATRULHAMENTO</a></p>
        ';
    } else {
        // Se não for um administrador, mostrar apenas o botão "Voltar" em azul
        echo '<p><a href="indexuser.php" class="button button-blue">Voltar</a></p>';
    }
    ?>

</body>
</html>
