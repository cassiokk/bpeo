<!DOCTYPE html>
<html>
<head>
   <title>Relatório MOTOPATRULHAMENTO</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <style>
        /* Estilos existentes */
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
        .button-container {
            text-align: left;
        }
        .button {
            color: white;
            background-color: #ff5b5b;
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
            background-color: #3498db;
            padding: 8px 16px;
            border: none;
            color: white;
            cursor: pointer;
            border-radius: 5px;
        }
        .button-blue:hover {
            background-color: #2980b9;
        }
        .bold {
            font-weight: bold;
        }
        .button-center {
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
<h1>CVP <span style="color: red;"> <span style="text-decoration: underline; font-weight: bold;">RELATÓRIO MOTOPATRULHAMENTO</span> </span> 16H ÀS 00H</h1>

    <?php
    $agendamentosFile = '.agendamentos.txt';
    $vagasDisponiveisFile = 'vagas_disponiveis.txt';

    if (file_exists($agendamentosFile) && file_exists($vagasDisponiveisFile)) {
        // Abre o arquivo de agendamentos em modo de escrita, apagando o conteúdo anterior
        $fileAgendamentos = fopen($agendamentosFile, 'w');
        
        // Abre o arquivo de vagas disponíveis em modo de escrita, apagando o conteúdo anterior
        $fileVagasDisponiveis = fopen($vagasDisponiveisFile, 'w');

        if ($fileAgendamentos && $fileVagasDisponiveis) {
            fclose($fileAgendamentos);
            fclose($fileVagasDisponiveis);
            echo 'Relatório e Vagas Disponíveis limpos com sucesso!';
        } else {
            echo 'Erro ao limpar o relatório e vagas disponíveis.';
        }
    } else {
        echo 'O arquivo de agendamentos ou vagas disponíveis não foi encontrado.';
    }
    ?>

    <div class="button-container">
        <div class="button-center">
            <form method="post" action="limpar_relatorio.php">
                <button type="submit" name="limpar_relatorio" class="button button-red">Limpar Relatório e Vagas Disponíveis</button>
            </form>
        </div>
        <p class="warning-text bold">(ATENÇÃO: ao limpar o relatório e vagas, todos os dados serão perdidos e será necessário o preenchimento das vagas disponíveis novamente.)</p>
    </div>
    <p><a href="admin.php" class="button button-blue">Voltar para MOTOPATRULHAMENTO</a></p>
</body>
</html>
