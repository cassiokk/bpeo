<!DOCTYPE html>
<html>
<head>
    <title>Página de Administração</title>
    <link rel="stylesheet" type="text/css" href="../css/style.css">
</head>
<body>
    <h1>Página de Administração</h1>
    <a href="../data/agendamentos.txt" download>Download do arquivo de agendamentos</a>
</body>
</html>
<!DOCTYPE html>
<html>
<head>
    <title>Página de Administração</title>
    <link rel="stylesheet" type="text/css" href="../css/style.css">
</head>
<body>
    <h1>Página de Administração</h1>
    
    <?php
    // Abra o arquivo CSV para leitura
    $arquivo = fopen("../data/agendamentos.csv", "r");

    // Verifique se o arquivo foi aberto com sucesso
    if ($arquivo !== false) {
        echo "<table>";
        echo "<tr><th>Nome</th><th>Matrícula</th><th>Dia Escolhido</th></tr>";
        
        // Leia e exiba os registros do arquivo CSV
        while (($dados = fgetcsv($arquivo, 1000, ",")) !== false) {
            echo "<tr><td>" . $dados[0] . "</td><td>" . $dados[1] . "</td><td>" . $dados[2] . "</td></tr>";
        }
        
        echo "</table>";

        // Feche o arquivo
        fclose($arquivo);
    } else {
        echo "Erro ao abrir o arquivo de agendamentos.";
    }
    ?>

    <a href="../data/agendamentos.csv" download>Download do arquivo de agendamentos (CSV)</a>
</body>
</html>
