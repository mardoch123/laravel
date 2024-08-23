<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau des Missions</title>
    @vite('resources/css/app.css')

    <style>
        .gallery {
            display: flex;
            flex-wrap: wrap;
            gap: 5px; /* Espace entre les images */
            padding: 10px;
        }
        .gallery-item {
            border-radius: 8px; /* Rayon de la bordure des images */
            overflow: hidden; /* Assure que les coins arrondis sont appliqués correctement */
            cursor: pointer; /* Change le curseur au survol */
        }
        .gallery-item img {
            display: block;
            width: 100px; /* Largeur fixe des images */
            height: 80px; /* Hauteur automatique pour garder les proportions */
            border-radius: 8px; /* Rayon de la bordure des images */
            object-fit: cover;
        }
    </style>
</head>
<body>
    
    <div class="container mx-auto p-4">
        <div class="bg-white shadow-md rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prénom</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Portable</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($missions->filter(fn($mission) => $mission->statut == 1 AND $mission->attributerpose == null) as $mission)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $mission->nom }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $mission->prenom }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $mission->portable }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $mission->statut == null ? 'bg-yellow-100 text-yellow-800' : ($mission->statut == 1 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                                Validé
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button data-id="{{ $mission->id }}" class="btn-apercu bg-blue-500 text-white px-4 py-2 rounded-lg">Aperçu</button>
                            <form action="{{ route('missions.postuler', $mission->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="ml-2 bg-green-500 text-white px-4 py-2 rounded-lg">Postuler</button>
                            </form>                            
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">Aucune mission trouvée</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            
        </div>
    </div>

    <!-- Modal d'aperçu détaillé -->
    <div id="modal-apercu" style="overflow: auto;" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center hidden">
        <div class="bg-white p-4 rounded-lg shadow-lg max-w-md w-full">
            <h2 id="modal-titre" class="text-lg font-bold mb-2"></h2>
            <p id="modal-description" class="mb-4"> Description </p>

            <div id="gallery" class="gallery">
                <!-- Les images seront insérées ici via JavaScript -->
            </div>

            <button id="close-modal" class="bg-red-500 text-white px-4 py-2 rounded-lg">Fermer</button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('modal-apercu');
            const closeModal = document.getElementById('close-modal');

            document.querySelectorAll('.btn-apercu').forEach(button => {
                button.addEventListener('click', () => {
                    const id = button.getAttribute('data-id');
                    fetch(`/missions/${id}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.error) {
                                console.error('Erreur lors de la récupération des données:', data.error);
                                return;
                            }

                            const climatiseurs = data.climatiseurs;
                            const images = data.images;
                            const totalClimatiseurs = data.totalClimatiseurs;

                            const climatiseur = climatiseurs[0];

                            // Construire le HTML pour les images avec lazy loading
                            const imageGallery = images.map(image => `
                                <a href="${image.image_path}" class="gallery-item" target="_blank">
                                    <img src="${image.image_path}" alt="Image">
                                </a>
                            `).join('');

                            document.getElementById('modal-titre').innerText = `Marque: ${climatiseur.marque}, Puissance: ${climatiseur.puissance}`;
                            document.getElementById('modal-description').innerHTML = `
                                <p><strong>Total de climatiseurs:</strong> ${totalClimatiseurs}</p>
                                <p><strong>Type d'installation:</strong> ${climatiseur.installation_type}</p>
                                <p><strong>Nombre d'étages:</strong> ${climatiseur.nbreetage}</p>
                                <p><strong>Entretien:</strong> ${climatiseur.entretien}</p>
                                <p><strong>Disjoncteur:</strong> ${climatiseur.disjoncteur}</p>
                                <p><strong>Prix net après prime:</strong> ${climatiseur.prix_net_apres_prime}</p>
                                <p><strong>TTC:</strong> ${climatiseur.ttc}</p>
                                <p><strong>Wifi:</strong> ${climatiseur.wifi}</p>
                                <p><strong>Carotage:</strong> ${climatiseur.carotage}</p>
                                <p><strong>Images:</strong></p>
                            `;

                            document.getElementById('gallery').innerHTML = imageGallery;

                            modal.classList.remove('hidden');
                        })
                        .catch(error => console.error('Erreur lors de la récupération des données:', error));
                });
            });

            closeModal.addEventListener('click', () => {
                modal.classList.add('hidden');
            });
        });
    </script>
    @if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: 'Succès',
                text: '{{ session('success') }}',
                showConfirmButton: true,
                timer: 5000 // Optionnel : ferme automatiquement après 5 secondes
            });
        });
    </script>
@endif

</body>
</html>
