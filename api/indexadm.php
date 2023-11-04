<!DOCTYPE html>
<html>
<head>
    <title>Página de Administração</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <h1>Bem-vindo à Página de Administração</h1>

    <h2>Agendamentos:</h2>
    <div class="agendamentos">
        <?php
        $agendamentos = file_get_contents('agendamentos.txt');
        $agendamentosArray = explode("\n", $agendamentos);
        
        foreach ($agendamentosArray as $agendamento) {
            $agendamento = trim($agendamento);
            if (!empty($agendamento)) {
                echo '<pre>' . $agendamento . '</pre>';
            }
        }
        ?>
    </div>
</body>
</html>
