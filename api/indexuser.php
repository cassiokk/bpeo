<!DOCTYPE html>
<html>
<head>
    <title>BPEO</title>
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #333;
            color: #fff;
            margin: 0;
            padding: 0;
            text-align: center;
        }

        h1 {
            background-color: #222;
            color: #fff;
            padding: 20px;
        }

        h2 {
            margin-top: 30px;
        }

        form {
            background-color: #444;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            margin: 0 auto;
        }

        label {
            display: block;
            margin: 10px 0;
        }

        input[type="text"],
        select {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 3px;
            text-align: center;
        }

        select {
            height: 50px;
            font-size: 90%;
        }

        .button-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 20px;
        }

        input[type="submit"],
        input[type="button"],
        .view-report-link,
        .delete-appointment-button {
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 80%;
            margin: 5px 0;
            text-decoration: none;
            color: #fff;
            font-weight: bold;
            font-size: 14px;
        }

        input[type="submit"] {
            background-color: #4CAF50;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        input[type="button"] {
            background-color: #3498db;
        }

        input[type="button"]:hover {
            background-color: #2980b9;
        }

        .view-report-link {
            background-color: #ff5b5b;
        }

        .view-report-link:hover {
            background-color: #ff3333;
        }

        .delete-appointment-button {
            background-color: #ff5b5b;
        }

        .delete-appointment-button:hover {
            background-color: #ff3333;
        }

        footer {
            background-color: #222;
            color: #fff;
            padding: 10px;
        }
    </style>
</head>
<body>
    <h1>BPEO</h1>

    <h2>Agende um Dia</h2>
    <form method="post" action="processa_agendamento.php">
        <label for="nome" style="text-align: center;">Nome:</label>
        <input type="text" id="nome" name="nome" style="width: 80%; margin: 5px auto;" required><br>
        
        <label for="matricula" style="text-align: center;">Matrícula:</label>
        <input type="text" id="matricula" name="matricula" style="width: 80%; margin: 5px auto;" required><br>
        
        <label for="patrulhamento" style="text-align: center;">Tipo de Patrulhamento:</label>
        <select id="patrulhamento" name="patrulhamento" required>
            <option value="">Selecione o Tipo de Patrulhamento</option>
            <option value="MOTOPATRULHAMENTO">MOTOPATRULHAMENTO 16H ÀS 00H</option>
            <option value="RADIOPATRULHAMENTO">RADIOPATRULHAMENTO 18H ÀS 02H</option>
        </select><br>

        <label for="data" style="text-align: center;">Data:</label>
        <select id="data" name="data" required>
            <option value="">Selecione uma Data</option> <!-- Opção vazia para data -->
            <?php
            $vagas_disponiveis = file_get_contents('vagas_disponiveis.txt');
            $vagas_disponiveis2 = file_get_contents('vagas_disponiveis2.txt');
            $vagas_array = explode("\n", $vagas_disponiveis);
            $vagas_array2 = explode("\n", $vagas_disponiveis2);
            $selected_patrulhamento = "MOTOPATRULHAMENTO"; // Defina o tipo de patrulhamento padrão
            if (isset($_POST['patrulhamento'])) {
                $selected_patrulhamento = $_POST['patrulhamento'];
            }
            $selected_vagas = ($selected_patrulhamento === "MOTOPATRULHAMENTO") ? $vagas_array : $vagas_array2;
            foreach ($selected_vagas as $vaga) {
                echo '<option value="' . $vaga . '">' . $vaga . '</option>';
            }
            ?>
        </select><br>

        <div class="button-container">
            <input type="submit" value="Agendar">
            <a href="relatorio.php" class="view-report-link">Relatório MOTOPATRULHAMENTO</a>
            <a href="relatorio2.php" class="view-report-link">Relatório RADIOPATRULHAMENTO</a>

            <a href="indexuser.php" style="display: block; text-align: center; width: 80%; margin: 5px auto;">
                <input type="button" value="Voltar">
            </a>
        </div>
    </form>
    
    <script>
        // Atualize as datas com base na seleção do tipo de patrulhamento
        document.getElementById("patrulhamento").addEventListener("change", function() {
            var selectedPatrulhamento = this.value;
            var dataSelect = document.getElementById("data");
            dataSelect.innerHTML = ""; // Limpa as opções anteriores
            var selectedVagas = (selectedPatrulhamento === "MOTOPATRULHAMENTO") ? <?php echo json_encode($vagas_array); ?> : <?php echo json_encode($vagas_array2); ?>;
            selectedVagas.forEach(function(vaga) {
                var option = document.createElement("option");
                option.value = vaga;
                option.text = vaga;
                dataSelect.appendChild(option);
            });
        });
    </script>

    <footer>
        Desenvolvido pelo SD PM CÁSSIO
    </footer>
</body>
</html>
