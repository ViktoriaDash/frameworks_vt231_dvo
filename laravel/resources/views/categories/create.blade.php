<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Додати категорію</title>
</head>
<body style="font-family: Arial; padding: 30px;">
    <h1>Додати нову категорію в бібліотеку</h1>

    @if(session('success'))
        <p style="color: green; font-weight: bold; font-size: 18px;">{{ session('success') }}</p>
    @endif

    <form action="{{ route('categories.store') }}" method="POST">
        @csrf
        <label>Назва жанру / категорії:</label><br>
        <input type="text" name="name" required style="padding: 8px; width: 350px; margin-top: 8px;"><br><br>
        
        <button type="submit" style="padding: 10px 20px; background: #28a745; color: white; border: none; cursor: pointer; font-size: 16px;">Зберегти у базу</button>
    </form>
</body>
</html>