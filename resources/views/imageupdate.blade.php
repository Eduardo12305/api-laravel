<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teste de Atualização de Imagem</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Teste de Atualização de Imagem</h2>
        <form action="{{ url('api/user/-OAJGvVwLNRgWesJlDjU/update-image') }}" method="POST" enctype="multipart/form-data" >
            @csrf
            <div class="form-group">
                <label for="image">Escolha a Imagem</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
            </div>
            <button type="submit" class="btn btn-primary">Enviar Imagem</button>
        </form>
    </div>
</body>
</html>
