<!DOCTYPE html>
<html>
<head>
    <title>CVP RADIOPATRULHAMENTO</title>
    <style>
        /* Estilo para botões */
        .button {
            color: white;
            border: none;
            padding: 8px 16px;
            margin: 5px;
            cursor: pointer;
            border-radius: 5px;
        }

        .button-red {
            background-color: #ff5b5b; /* Cor vermelha */
        }

        .button-red:hover {
            background-color: #f2f2f2;
        }

        .button-green {
            background-color: #4CAF50; /* Cor verde */
        }

        .button-green:hover {
            background-color: #45a049; /* Cor verde mais escura quando hover */
        }

        .button-blue {
            background-color: #3498db; /* Cor azul */
        }

        .button-blue:hover {
            background-color: #2980b9; /* Cor azul mais escura quando hover */
        }

        .button-clear {
            background-color: #ff5b5b; /* Cor vermelha para Limpar Vagas Disponíveis */
            float: right; /* Move o botão para o canto direito */
        }

        .button-clear:hover {
            background-color: #ff3333;
        }

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
    </style>
</head>
<body>
<h1>CVP <span style="color: red; text-decoration: underline; font-weight: bold;">RADIOPATRULHAMENTO</span> 18H ÀS 02H</h1>

    <!-- Abas de navegação -->
    <div>
        <a href="admin.php" class="button" style="background-color: #3498db; color: white; text-decoration: none;">MOTOPATRULHAMENTO</a>
        <a href="admin2.php" class="button" style="background-color: #ff5b5b; color: white;">RADIOPATRULHAMENTO 18H ÀS 02H</a>
    </div>

    <h2>Adicionar Data e Quantidade de Vagas:</h2>
    <form method="post">
        <label for="data">Data (DD/MM/AAAA):</label>
        <input type="text" name="data" id="data" required pattern="\d{2}/\d{2}/\d{4}" title="Digite a data no formato DD/MM/AAAA" placeholder="DD/MM/AAAA">
        <label for "quantidade_vagas">Quantidade de Vagas:</label>
        <input type="text" name="quantidade_vagas" id="quantidade_vagas" required pattern="\d+" title="Digite apenas números">
        <button type="submit" name="adicionar_vagas" class="button button-green">Adicionar Data e Vagas</button>
    </form>

    <?php
    if (isset($_POST['adicionar_vagas'])) {
        $data = $_POST['data'];
        $quantidade_vagas = $_POST['quantidade_vagas'];
        $linha = $data . ' - ' . $quantidade_vagas;

        $arquivo = file('../vagas_disponiveis2.txt', FILE_IGNORE_NEW_LINES);
        if (!in_array($linha, $arquivo)) {
            $arquivo[] = $linha;
            file_put_contents('../vagas_disponiveis2.txt', implode(PHP_EOL, $arquivo));
        } else {
            echo '<p style="color: red;">A data já existe. Não é possível adicionar datas repetidas.</p>';
        }
    }
    ?>

    <h2>Vagas Disponíveis:</h2>
    <table>
        <thead>
            <tr>
                <th>Data</th>
                <th>Quantidade de Vagas</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $vagas_disponiveis = file('../vagas_disponiveis2.txt', FILE_IGNORE_NEW_LINES);
            if (!empty($vagas_disponiveis)) {
                foreach ($vagas_disponiveis as $vaga) {
                    list($data, $quantidadeVagas) = explode(' - ', $vaga);
                    echo '<tr>';
                    echo '<td>' . $data . '</td>';
                    echo '<td>' . $quantidadeVagas . '</td>';
                    echo '<td><a href="?remover_vaga=' . urlencode($vaga) . '" class="button button-red">Remover</a></td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="3">Nenhuma vaga disponível no momento.</td></tr>';
            }
            ?>
        </tbody>
    </table>


    <div style="margin-top: 20px;">
     <a href="pagina_relatorio2.php" class="button" style="background-color: #ff5b5b; color: white; text-decoration: none;">Relatório RADIOPATRULHAMENTO</a>
        <a href="excluir_agendamento.php" class="button button-blue" style="color: white; text-decoration: none;">Alguém desistiu do agendamento? clique aqui!</a>
       
    </div>

    <?php
    if (isset($_GET['remover_vaga'])) {
        $vaga_remover = $_GET['remover_vaga'];
        $arquivo = file('../vagas_disponiveis2.txt', FILE_IGNORE_NEW_LINES);

        $arquivo = array_filter($arquivo, function ($linha) use ($vaga_remover) {
            return $linha !== $vaga_remover;
        });

        file_put_contents('../vagas_disponiveis2.txt', implode(PHP_EOL, $arquivo));
    }

    if (isset($_POST['limpar_vagas'])) {
        file_put_contents('../vagas_disponiveis2.txt', ''); // Limpa o arquivo de vagas disponíveis.
    }
    ?>
</body>
</html>
