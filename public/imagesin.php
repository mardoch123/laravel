<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Photos</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Be Vietnam Pro', sans-serif;
        }
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
        .hidden {
            display: none !important;
        }
        .image-preview {
            margin-top: 10px;
            max-height: 100px;
            border: 1px solid #ddd;
            border-radius: 5px;
            overflow: hidden;
        }
        .image-preview img {
            display: block;
            max-width: 100%;
            max-height: 100%;
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Main Container -->
    <div class="container mx-auto py-10 mt-10 max-w-4xl bg-white shadow-md rounded-lg p-6">
        <!-- Logo -->
        <div class="text-center mb-8">
            <img src="https://www.ecoagir-climatiseur.com/wp-content/uploads/2023/07/logo-ct-1.png" alt="Logo" class="mx-auto h-16">
        </div>

        <h1 class="text-xl font-bold text-gray-800 mb-6">Téléchargez les Photos</h1>
        <form id="uploadForm" enctype="multipart/form-data" class="space-y-6">
            <!-- Champ caché pour mission_id -->
            <input type="hidden" id="mission_id" name="mission_id" value="">

            <!-- File Inputs -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <div>
                    <label for="photo_emplacement_evaporateur" class="block text-gray-700 text-sm font-semibold mb-2">
                        Photo emplacement évaporateur <span class="text-red-500">*</span>
                    </label>
                    <input type="file" id="photo_emplacement_evaporateur" name="photo_emplacement_evaporateur" accept="image/*" required
                           class="block w-full text-sm text-gray-600 border border-gray-300 rounded p-2 required-input">
                    <div class="image-preview" id="preview_photo_emplacement_evaporateur"></div>
                </div>
                <div>
                    <label for="photo_numero_serie_evaporateur" class="block text-gray-700 text-sm font-semibold mb-2">
                        Photo numéro de série évaporateur <span class="text-red-500">*</span>
                    </label>
                    <input type="file" id="photo_numero_serie_evaporateur" name="photo_numero_serie_evaporateur" accept="image/*" required
                           class="block w-full text-sm text-gray-600 border border-gray-300 rounded p-2 required-input">
                    <div class="image-preview" id="preview_photo_numero_serie_evaporateur"></div>
                </div>
                <div>
                    <label for="photo_raccordement_electrique" class="block text-gray-700 text-sm font-semibold mb-2">
                        Photo raccordement électrique <span class="text-red-500">*</span>
                    </label>
                    <input type="file" id="photo_raccordement_electrique" name="photo_raccordement_electrique" accept="image/*" required
                           class="block w-full text-sm text-gray-600 border border-gray-300 rounded p-2 required-input">
                    <div class="image-preview" id="preview_photo_raccordement_electrique"></div>
                </div>
                <div>
                    <label for="photo_emplacement_condensateur" class="block text-gray-700 text-sm font-semibold mb-2">
                        Photo emplacement condensateur <span class="text-red-500">*</span>
                    </label>
                    <input type="file" id="photo_emplacement_condensateur" name="photo_emplacement_condensateur" accept="image/*" required
                           class="block w-full text-sm text-gray-600 border border-gray-300 rounded p-2 required-input">
                    <div class="image-preview" id="preview_photo_emplacement_condensateur"></div>
                </div>
                <div>
                    <label for="photo_numero_serie_condensateur" class="block text-gray-700 text-sm font-semibold mb-2">
                        Photo numéro de série condensateur <span class="text-red-500">*</span>
                    </label>
                    <input type="file" id="photo_numero_serie_condensateur" name="photo_numero_serie_condensateur" accept="image/*" required
                           class="block w-full text-sm text-gray-600 border border-gray-300 rounded p-2 required-input">
                    <div class="image-preview" id="preview_photo_numero_serie_condensateur"></div>
                </div>
            </div>

            <!-- Notification -->
            <div id="notification" class="hidden text-red-600 font-medium text-sm mb-4">
                Veuillez charger tous les fichiers pour activer le bouton "Enregistrer".
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end gap-4">
                <button type="reset" class="bg-gray-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-600 transition">
                    Annuler
                </button>
                <button id="submitButton" type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-600 transition hidden">
                    Enregistrer
                </button>
            </div>
        </form>
    </div>

    <!-- Loader -->
    <div id="loader" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="loader"></div>
    </div>

    <script>
        $(document).ready(function () {
            const requiredInputs = $('.required-input');
            const submitButton = $('#submitButton');
            const notification = $('#notification');

            // Fonction pour récupérer l'ID de mission depuis l'URL
            function getMissionIdFromUrl() {
                const urlParams = new URLSearchParams(window.location.search);
                return urlParams.get('mission_id');
            }

            // Initialisation
            const missionId = getMissionIdFromUrl();
            if (missionId) {
                $('#mission_id').val(missionId);
            } else {
                Swal.fire({
                    title: 'Erreur!',
                    text: 'Aucun ID de mission fourni dans l\'URL.',
                    icon: 'error',
                });
                return;
            }

            // Vérifie si tous les fichiers sont fournis
            function checkInputs() {
                let allFilled = true;

                requiredInputs.each(function () {
                    if (!$(this).val()) {
                        allFilled = false;
                    }
                });

                if (allFilled) {
                    submitButton.removeClass('hidden'); // Afficher le bouton
                    notification.addClass('hidden'); // Cacher la notification
                } else {
                    submitButton.addClass('hidden'); // Masquer le bouton
                    notification.removeClass('hidden'); // Afficher la notification
                }
            }

            // Afficher l'aperçu de l'image
            requiredInputs.on('change', function () {
                const file = this.files[0];
                const previewId = `#preview_${this.id}`;

                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        $(previewId).html(`<img src="${e.target.result}" alt="Aperçu de l'image">`);
                    };
                    reader.readAsDataURL(file);
                } else {
                    $(previewId).html('');
                }

                checkInputs();
            });

            // Vérifie initialement si tous les fichiers sont fournis
            checkInputs();

            // Gestion de la soumission du formulaire
            $('#uploadForm').on('submit', function (e) {
                e.preventDefault();

                const formData = new FormData(this);

                $('#loader').removeClass('hidden'); // Show loader

                $.ajax({
                    url: 'upload.php', // Remplacez par l'URL de traitement
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
                                window.location.href = '/success_page'; // Redirection en cas de succès
                            });
                        } else {
                            Swal.fire({
                                title: 'Erreur!',
                                text: response.message,
                                icon: 'error',
                            });
                        }
                    },
                    error: function () {
                        $('#loader').addClass('hidden'); // Hide loader
                        Swal.fire({
                            title: 'Erreur!',
                            text: 'Une erreur s\'est produite lors de l\'envoi des fichiers.',
                            icon: 'error',
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>