<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method="POST" action="{{ route('login') }}">

    @csrf
    <input type="text" name="cpf" placeholder="CPF">
    <input type="password" name="password" placeholder="password">

    <button type="submit">Login</button>
    </form>
</body>
</html>