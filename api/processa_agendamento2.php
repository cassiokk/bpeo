<?php
session_start();

// Função para verificar e definir o tempo do último agendamento por dispositivo
function setLastScheduledTime() {
    if (!isset($_SESSION['last_scheduled'])) {
        $_SESSION['last_scheduled'] = 0;
    }

    $currentTime = time();
    $lastScheduledTime = $_SESSION['last_scheduled'];

    if ($currentTime - $lastScheduledTime >= 30) {
        $_SESSION['last_scheduled'] = $currentTime;
        return true; // Pode agendar
    } else {
        return false; // Deve esperar 30 segundos
    }
}

if (isset($_POST['nome']) && isset($_POST['matricula']) && isset($_POST['data']) && isset($_POST['patrulhamento'])) {
    $nome = $_POST['nome'];
    $matricula = $_POST['matricula'];
    $data_agendamento = $_POST['data'];
    $patrulhamento = $_POST['patrulhamento'];

    // Use o endereço IP do usuário como identificação única
    $identificador = $_SERVER['REMOTE_ADDR'];

    if (!isset($_SESSION['agendamentos'][$identificador])) {
        $_SESSION['agendamentos'][$identificador] = [];
    }

    $agendamentos = &$_SESSION['agendamentos'][$identificador];

    // Determine qual arquivo de agendamentos usar com base no tipo de patrulhamento
    if ($patrulhamento === 'MOTOPATRULHAMENTO') {
        $agendamentos_file = fopen('../admin/agendamentos.txt', 'a');
    } elseif ($patrulhamento === 'RADIOPATRULHAMENTO') {
        $agendamentos_file = fopen('../admin/agendamentos2.txt', 'a');
    }

    // Ler o arquivo de vagas disponíveis correspondente ao tipo de patrulhamento
    $vagas_disponiveis_file = ($patrulhamento === 'MOTOPATRULHAMENTO') ? '../admin/vagas_disponiveis.txt' : '../admin/vagas_disponiveis2.txt';
    $vagas_disponiveis = file_get_contents($vagas_disponiveis_file);
    $vagas_array = explode("\n", $vagas_disponiveis);

    $agendamento_realizado = false;

    foreach ($vagas_array as $key => $vaga) {
        if (strpos($vaga, $data_agendamento) !== false) {
            list($data, $quantidadeVagas) = explode(' - ', $vaga);

            if ($quantidadeVagas > 0 && setLastScheduledTime()) {
                // Reduza a quantidade de vagas disponíveis
                $quantidadeVagas--;
                $vagas_array[$key] = "$data - $quantidadeVagas";

                // Salve as vagas atualizadas no arquivo correspondente
                $vagas_disponiveis = implode("\n", $vagas_array);
                file_put_contents($vagas_disponiveis_file, $vagas_disponiveis);

                // Registre o agendamento do usuário no arquivo correspondente
                $agendamento = "Data: $data_agendamento | Nome: $nome | Matrícula: $matricula";
                fwrite($agendamentos_file, $agendamento . PHP_EOL);

                fclose($agendamentos_file);

                // Adicione o agendamento à sessão do usuário
                $agendamentos[] = $agendamento;

                $redirectURL = 'index.php';
                $message = 'Agendamento realizado com sucesso para ' . $data_agendamento . '!';
                $delay = 30;
                $disabled = 'disabled';

                $script = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <title>Agendamento de Vagas</title>
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

        .container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            padding: 20px;
            margin: 20px auto;
            max-width: 400px;
        }

        h1 {
            color: #333;
        }

        .message {
            font-weight: bold;
        }

        .success {
            color: green;
        }

        .error {
            color: red;
        }

        a.button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #3498db;
            color: #fff;
            text-decoration: none;
            border: none;
            border-radius: 5px;
        }

        a.button.disabled {
            background-color: #888;
            pointer-events: none;
        }

        a.button:hover {
            background-color: #1e87d3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Agendamento de Vagas</h1>
        <p class="message success">$message</p>
        <a id="voltar-botao" class="button $disabled" href="$redirectURL">
            Aguarde $delay segundos para que o botão Voltar seja ativado. Não atualize a página, pois isso pode resultar na perda da vaga devido à falta de visibilidade do tempo restante.
        </a>
    </div>
    <script type="text/javascript">
        // Função para habilitar o botão após $delay segundos
        var botao = document.getElementById("voltar-botao");
        var timer = $delay;
        
        function updateButton() {
            if (timer > 0) {
                botao.innerHTML = "Aguarde " + timer + " segundos para que o botão Voltar seja ativado. Não atualize a página, pois isso pode resultar na perda da vaga devido à falta de visibilidade do tempo restante.";
                timer--;
                setTimeout(updateButton, 1000);
            } else {
                botao.classList.remove("disabled");
                botao.innerHTML = "Voltar para a página do usuário";
            }
        }
        
        setTimeout(updateButton, 1000);
    </script>
</body>
</html>
HTML;

                echo $script;
                $agendamento_realizado = true;
                break;
            } elseif ($quantidadeVagas == 0) {
                $redirectURL = 'index.php';
                $message = 'Desculpe, não há vagas disponíveis para ' . $data_agendamento . '.';
                
                $script = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <title>Agendamento de Vagas</title>
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

        .container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            padding: 20px;
            margin: 20px auto;
            max-width: 400px;
        }

        h1 {
            color: #333;
        }

        .message {
            font-weight: bold;
        }

        .error {
            color: red;
        }

        a.button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #3498db;
            color: #fff;
            text-decoration: none;
            border: none;
            border-radius: 5px;
        }

        a.button:hover {
            background-color: #1e87d3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Agendamento de Vagas</h1>
        <p class="message error">$message</p>
        <a class="button" href="$redirectURL">Voltar para a página do usuário</a>
    </div>
</body>
</html>
HTML;

                echo $script;
                $agendamento_realizado = true;
                break;
            }
        }
    }

    if (!$agendamento_realizado) {
        $redirectURL = 'index.php';
        $message = 'Você deve esperar 30 segundos antes de fazer outro agendamento.';
        
        $script = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <title>Agendamento de Vagas</title>
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

        .container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            padding: 20px;
            margin: 20px auto;
            max-width: 400px;
        }

        h1 {
            color: #333;
        }

        .message {
            font-weight: bold;
        }

        .error {
            color: red;
        }

        a.button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #3498db;
            color: #fff;
            text-decoration: none;
            border: none;
            border-radius: 5px;
        }

        a.button:hover {
            background-color: #1e87d3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Agendamento de Vagas</h1>
        <p class="message error">$message</p>
        <a class="button" href="$redirectURL">Voltar para a página do usuário</a>
    </div>
</body>
</html>
HTML;

        echo $script;
    }
}
?>