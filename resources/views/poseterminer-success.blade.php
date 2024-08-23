<!-- resources/views/poseterminer-success.blade.php -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enregistrement Réussi</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@^2.0/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="max-w-lg mx-auto bg-white rounded-lg shadow-lg p-6">
        <h1 class="text-2xl font-bold text-green-600 mb-4">Photos enregistrées avec succès !</h1>
        <p class="text-gray-700 mb-4">Les fichiers ont été envoyés avec succès et sont maintenant disponibles.</p>

        <div class="mb-4">
            <h2 class="text-xl font-semibold text-gray-800 mb-2">Aperçu des photos :</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                @foreach ($images as $key => $image)
                    <div class="flex flex-col items-center">
                        <img src="{{ $image }}" alt="{{ $key }}" class="w-full h-32 object-cover rounded-md shadow-sm">
                    </div>
                @endforeach
            </div>
        </div>

        <a href="{{ route('dashboard') }}" class="text-blue-500 hover:underline">Retour à l'accueil</a>
    </div>
</body>
</html>
