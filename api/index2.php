<!DOCTYPE html>
<html>
<head>
    <title>Limpar Agendamentos</title>
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

        .button-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 20px;
        }

        input[type="button"] {
            background-color: #ff5b5b;
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

        input[type="button"]:hover {
            background-color: #ff3333;
        }
    </style>
</head>
<body>
    <h1>Limpar Agendamentos</h1>

    <div class="button-container">
        <input type="button" value="Limpar Agendamentos" id="limparAgendamentos">
    </div>

    <script>
        document.getElementById("limparAgendamentos").addEventListener("click", function() {
            // Fazer uma requisição AJAX para o arquivo PHP que limpará os agendamentos
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "limpar_agendamentos.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    // A resposta da requisição pode ser usada para exibir uma mensagem ou atualizar a página, se necessário
                    alert(xhr.responseText);
                }
            };
            xhr.send("tipoPatrulhamento=motopatrulhamento"); // Define o tipo de patrulhamento aqui
        });
    </script>
</body>
</html>
