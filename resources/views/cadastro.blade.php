<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <input type="text" name="name" placeholder="nome">
        <input type="text" name="cpf" placeholder="CPF">
        <input type="email" name="email" placeholder="email">
        <input type="password" name="password" placeholder="senha">
        <input type="password" name="password_confirmation" placeholder="Confirmar senha">

        <button type="submit">Cadastro</button>
    </form>
</body>
</html>
