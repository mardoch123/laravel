<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Photos</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <!-- Main Container -->
    <div class="container mx-auto py-10">
        <h1 class="text-2xl font-bold mb-6">Téléchargez les Photos</h1>
        <form id="uploadForm" enctype="multipart/form-data" class="space-y-6">
            <!-- File Inputs -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="photo_emplacement_evaporateur" class="block text-gray-700 text-sm font-semibold mb-2">
                        Photo emplacement évaporateur
                    </label>
                    <input type="file" id="photo_emplacement_evaporateur" name="photo_emplacement_evaporateur" accept="image/*" required
                           class="block w-full text-sm text-gray-600 border border-gray-300 rounded p-2">
                </div>
                <div>
                    <label for="photo_numero_serie_evaporateur" class="block text-gray-700 text-sm font-semibold mb-2">
                        Photo numéro de série évaporateur
                    </label>
                    <input type="file" id="photo_numero_serie_evaporateur" name="photo_numero_serie_evaporateur" accept="image/*" required
                           class="block w-full text-sm text-gray-600 border border-gray-300 rounded p-2">
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="photo_raccordement_electrique" class="block text-gray-700 text-sm font-semibold mb-2">
                        Photo raccordement électrique
                    </label>
                    <input type="file" id="photo_raccordement_electrique" name="photo_raccordement_electrique" accept="image/*" required
                           class="block w-full text-sm text-gray-600 border border-gray-300 rounded p-2">
                </div>
                <div>
                    <label for="photo_emplacement_condensateur" class="block text-gray-700 text-sm font-semibold mb-2">
                        Photo emplacement condensateur
                    </label>
                    <input type="file" id="photo_emplacement_condensateur" name="photo_emplacement_condensateur" accept="image/*" required
                           class="block w-full text-sm text-gray-600 border border-gray-300 rounded p-2">
                </div>
            </div>
            <div>
                <button type="submit" class="w-full bg-blue-500 text-white px-4 py-2 rounded-lg text-lg font-semibold hover:bg-blue-600 transition">
                    Enregistrer
                </button>
            </div>
        </form>
    </div>

    <!-- Loader -->
    <div id="loader" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="loader"></div>
    </div>

    <style>
        /* Loader Styling */
        .loader {
            border: 4px solid rgba(0, 0, 0, 0.1);
            border-left-color: #3498db;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>

    <script>
        $(document).ready(function () {
            // Handle form submission
            $('#uploadForm').on('submit', function (e) {
                e.preventDefault();

                const formData = new FormData(this);

                $('#loader').removeClass('hidden'); // Show loader

                $.ajax({
                    url: 'upload.php', // Endpoint PHP pour gérer les téléchargements
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        $('#loader').addClass('hidden'); // Hide loader
                        if (response.success) {
                            Swal.fire({
                                title: 'Succès!',
                                text: response.message,
                                icon: 'success',
                            }).then(() => {
                                window.location.href = 'success_page.php'; // Redirection en cas de succès
                            });
                        } else {
                            Swal.fire({
                                title: 'Erreur!',
                                text: response.message,
                                icon: 'error',
                            });
                        }
                    },
                    error: function (xhr) {
                        console.error(xhr.responseJSON);
                        $('#loader').addClass('hidden'); // Hide loader
                        Swal.fire({
                            title: 'Erreur!',
                            text: 'Une erreur est survenue lors de l\'envoi des fichiers.',
                            icon: 'error',
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>