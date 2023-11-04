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

        ul {
            list-style-type: none; /* Remova marcadores de lista */
            padding: 0;
        }

        li {
            margin-top: 20px; /* Espaço entre os itens da lista */
        }

        a {
            text-decoration: none; /* Remova sublinhado dos links */
            padding: 10px 20px;
            border-radius: 5px;
            display: inline-block;
        }

        a.user-button {
            background-color: #4CAF50; /* Cor verde para o botão de usuário */
            color: #fff; /* Cor do texto dos links */
        }

        a.admin-button {
            background-color: #ff5b5b; /* Cor vermelha para o botão de administrador */
            color: #fff; /* Cor do texto dos links */
        }

        a.admin-button:hover {
            background-color: #ff3333; /* Cor vermelha mais escura ao passar o mouse */
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
    <p>Escolha uma opção:</p>
    <ul>
        <li><a href="indexuser.php" class="user-button">Acessar como Usuário</a></li>
        <li><a href="admin_login.php" class="admin-button">Acessar como Administrador</a></li>
    </ul>
    <footer>Desenvolvido pelo SD PM CÁSSIO</footer>
</body>
</html>
