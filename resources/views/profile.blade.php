<!-- resources/views/profile.blade.php -->

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Télécharger Photo de Profil</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <a href="{{ route('profile') }}">Voir le profil</a>
    @csrf
    <div class="container">
        <h1>Télécharger votre photo de profil</h1>
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @elseif(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('profile.upload') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="profilePhoto">Choisissez une photo :</label>
                <input type="file" id="profilePhoto" name="profilePhoto" accept="image/*" required>
                @error('profilePhoto')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit">Télécharger</button>
        </form>
    </div>
</body>
</html>