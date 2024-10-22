<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Usuário</title>
</head>
<body>
    <h1>Criar Usuário</h1>
    <form action="{{ route('user.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label for="name">Nome:</label>
        <input type="text" name="name" required value="teste">

        <label for="email">Email:</label>
        <input type="email" name="email" required value="teste@gmail.com">

        <label for="password">Senha:</label>
        <input type="password" name="password" required value="admin123456">

        <label for="password_confirmation">Confirmação de Senha:</label>
        <input type="password" name="password_confirmation" required value="admin123456">

        <label for="cpf">CPF:</label>
        <input type="text" name="cpf" required>

        <label for="image">Imagem:</label>
        <input type="file" name="image" accept="image/*">

        <button type="submit">Criar Usuário</button>
    </form>
</body>
</html>
