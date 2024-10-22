<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>
    <form action="{{ route('user.login') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <label for="cpf">CPF:</label>
        <input type="text" name="cpf" required>

        <label for="password">Senha:</label>
        <input type="password" name="password" required value="admin123456">  

        <button type="submit">Login</button>
    </form>
</body>
</html>