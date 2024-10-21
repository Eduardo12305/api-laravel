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
        <input type="text" name="name" required>

        <label for="email">Email:</label>
        <input type="email" name="email" required>

        <label for="password">Senha:</label>
        <input type="password" name="password" required>

        <label for="password_confirmation">Confirmação de Senha:</label>
        <input type="password" name="password_confirmation" required>

        <label for="cpf">CPF:</label>
        <input type="text" name="cpf" required>

        <label for="image">Imagem:</label>
        <input type="file" name="image" accept="image/*">

        <label for="dt_venc">Data de Vencimento:</label>
        <input type="date" name="dt_venc">

        <button type="submit">Criar Usuário</button>
    </form>
</body>
</html>
