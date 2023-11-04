<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar as credenciais (login e senha)
    $admin_login = "bpeo";
    $admin_senha = "tenregina2023bpeo"; // Use uma senha mais segura em um ambiente real

    $input_login = $_POST["login"];
    $input_senha = $_POST["senha"];

    if ($input_login === $admin_login && $input_senha === $admin_senha) {
        // Credenciais corretas, redirecione para a página de administração
        header('Location: admin.php');
        exit;
    } else {
        $erro = "Credenciais inválidas. Tente novamente.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Bem Vindo - BPEO</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #333; /* Fundo cinza escuro */
            color: #fff; /* Texto branco */
            margin: 0;
            padding: 0;
            text-align: center;
        }

        h1 {
            background-color: #222; /* Fundo mais escuro para o cabeçalho */
            color: #fff;
            padding: 20px;
        }

        p {
            color: #fff; /* Cor do texto branco */
        }

        form {
            background-color: #444; /* Fundo cinza escuro para o formulário */
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            margin: 0 auto;
        }

        label {
            display: block;
            margin: 10px 0;
            color: #fff; /* Cor do texto das labels */
        }

        input[type="text"],
        input[type="password"] {
            width: 90%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 3px;
            text-align: center;
        }

        button {
            background-color: #4CAF50; /* Cor verde para o botão de login */
            color: #fff; /* Cor do texto do botão */
            border: none;
            padding: 10px;
            width: 49%; /* Largura definida para 49% para garantir que ambos os botões tenham o mesmo tamanho */
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049; /* Cor verde um pouco mais escura no hover */
        }

        a.button-container {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }

        a.button-blue {
            background-color: #3498db; /* Cor azul para o botão "Voltar" */
            color: #fff; /* Cor do texto do botão */
            padding: 10px;
            width: 49%; /* Largura definida para 49% para garantir que ambos os botões tenham o mesmo tamanho */
            border-radius: 5px;
            text-decoration: none;
        }

        a.button-blue:hover {
            background-color: #2980b9; /* Cor azul mais escura no hover */
        }

        footer {
            background-color: #222; /* Fundo mais escuro para o rodapé */
            color: #fff; /* Cor do texto do rodapé */
            padding: 10px;
        }
    </style>
</head>
<body>
    <h1>Bem Vindo - BPEO</h1>
    <h2>Login do Administrador</h2>
    <?php
    if (isset($erro)) {
        echo '<p style="color: red;">' . $erro . '</p>';
    }
    ?>
    <form method="post">
        <label for="login">Login:</label>
        <input type="text" name="login" id="login" required><br>
        <label for="senha">Senha:</label>
        <input type="password" name="senha" id="senha" required><br>
        <div class="button-container">
            <button type="submit">Entrar</button>
            <a href="index.php" class="button-blue">Voltar</a>
        </div>
    </form>
    <footer>Desenvolvido pelo SD PM CÁSSIO</footer>
</body>
</html>
