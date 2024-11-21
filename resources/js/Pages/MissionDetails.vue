<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import Swal from 'sweetalert2';

const { props } = usePage();
const mission = ref(props.mission);
const climatiseurs = ref(props.climatiseurs);
const images = ref(props.images);
const showModal = ref(false);

// Propriété pour détecter les petits écrans
const screenWidth = ref(window.innerWidth);
const isSmallScreen = computed(() => screenWidth.value <= 768);

const updateScreenWidth = () => {
    screenWidth.value = window.innerWidth;
};

onMounted(() => {
    window.addEventListener('resize', updateScreenWidth);
});

onUnmounted(() => {
    window.removeEventListener('resize', updateScreenWidth);
});

// Prévisualisation des images sélectionnées
const selectedImages = ref({
    photo_emplacement_evaporateur: null,
    photo_numero_serie_evaporateur: null,
    photo_raccordement_electrique: null,
    photo_emplacement_condensateur: null,
    photo_numero_serie_condensateur: null,
});

// Barre de progression et contrôle du chargement
const uploadProgress = ref(0);
const isUploading = ref(false);
const submitDisabled = ref(true);

// Obtenir le jeton CSRF depuis la balise meta
const csrfTokenElement = document.querySelector('meta[name="csrf-token"]');
const csrfToken = csrfTokenElement ? csrfTokenElement.getAttribute('content') : null;

if (!csrfToken) {
    console.error("Erreur : le jeton CSRF est manquant dans la balise <meta>. Vérifiez que la balise <meta name='csrf-token'> est présente dans votre HTML.");
}

// Fonction pour afficher le modal
const showModalHandler = () => {
    showModal.value = true;
    submitDisabled.value = true; // Désactiver le bouton jusqu'à la fin du chargement
};

// Fonction pour fermer le modal
const closeModalHandler = () => {
    showModal.value = false;
};

// Fonction pour gérer la sélection des fichiers
const fileInputHandler = (event, key) => {
    const file = event.target.files[0];
    if (file && file.size > 3 * 1024 * 1024) {
        Swal.fire({
            title: "Erreur",
            text: "La taille du fichier dépasse 3 Mo. Veuillez sélectionner une image plus petite.",
            icon: "error",
        });
        event.target.value = ""; // Réinitialiser l'input
    } else {
        selectedImages.value[key] = file; // Stocke le fichier pour le formulaire global
        checkAllFilesSelected(); // Vérifier si tous les fichiers sont sélectionnés
    }
};

// Fonction pour vérifier si tous les fichiers requis sont sélectionnés
const checkAllFilesSelected = () => {
    submitDisabled.value = !Object.values(selectedImages.value).every(file => file !== null);
};

// Fonction pour créer en toute sécurité l'URL d'aperçu d'une image
const getPreviewUrl = (file) => {
    return file ? URL.createObjectURL(file) : '';
};

// Fonction pour soumettre le formulaire et charger tous les fichiers ensemble
const submitForm = async (event) => {
    event.preventDefault();

    if (isUploading.value) {
        Swal.fire({
            title: "Veuillez patienter",
            text: "Le chargement des images est en cours. Veuillez patienter jusqu'à la fin.",
            icon: "info",
        });
        return;
    }

    const formData = new FormData();
    formData.append('mission_id', mission.value.id);
    if (csrfToken) formData.append('_token', csrfToken);

    // Ajouter chaque fichier sélectionné dans formData
    for (let [key, file] of Object.entries(selectedImages.value)) {
        if (file) formData.append(key, file);
    }

    isUploading.value = true;
    submitDisabled.value = true;
    uploadProgress.value = 0;

    try {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', '/poseterminer', true);

        // Suivi de la progression de l'upload
        xhr.upload.addEventListener("progress", (event) => {
            if (event.lengthComputable) {
                uploadProgress.value = Math.round((event.loaded / event.total) * 100);
            }
        });

        xhr.onload = async () => {
            isUploading.value = false;
            submitDisabled.value = false;

            if (xhr.status === 200) {
                Swal.fire({
                    title: "Upload terminé",
                    text: "Les images ont été téléchargées avec succès",
                    icon: "success",
                }).then(() => {
                    window.history.back();
                });
                closeModalHandler();
            } else {
                const errorData = JSON.parse(xhr.responseText);
                console.error("Erreur lors du téléchargement des images:", errorData);
                Swal.fire({
                    title: "Erreur",
                    text: "Erreur lors du téléchargement des images",
                    icon: "error",
                });
            }
        };

        xhr.onerror = () => {
            isUploading.value = false;
            submitDisabled.value = false;
            Swal.fire({
                title: "Erreur",
                text: "Erreur lors du téléchargement des images",
                icon: "error",
            });
        };

        xhr.send(formData);
    } catch (error) {
        console.error("Erreur lors de l'envoi des données:", error);
        isUploading.value = false;
        submitDisabled.value = false;
        Swal.fire({
            title: "Erreur",
            text: "Erreur lors du téléchargement des images",
            icon: "error",
        });
    }
};
</script>

<template>
    <Head title="Détails de la Mission" />
    <AppLayout title="Détails de la Mission">
        <div class="container mx-auto p-6 bg-gray-100 rounded-lg shadow-md overflow-x-auto" >
            <h2 class="text-3xl font-extrabold text-gray-800 mb-6">Détails de la Mission</h2>

            <!-- Informations de la Mission -->
            <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                <h3 class="text-2xl font-semibold text-gray-700 mb-4">Informations de la Mission</h3>
                <p class="text-gray-600 mb-2"><strong>ID:</strong> {{ mission.id }}</p>
                <p class="text-gray-600 mb-2"><strong>Description:</strong> {{ mission.observations }}</p>
                <p class="text-gray-600"><strong>Adresse:</strong> {{ mission.adresse }}</p>
            </div>

            <!-- Tableau des Climatiseurs -->
            <div class="bg-white p-6 rounded-lg shadow-sm mb-6 overflow-x-auto">
                <h3 class="text-2xl font-semibold text-gray-700 mb-4">Climatiseurs</h3>
                <table class="min-w-full bg-white border border-gray-300">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="py-3 px-4 border-b text-left">Marque</th>
                            <th class="py-3 px-4 border-b text-left">Puissance</th>
                            <th class="py-3 px-4 border-b text-left">Prix Unitaire</th>
                            <th class="py-3 px-4 border-b text-left">Quantité</th>
                            <th class="py-3 px-4 border-b text-left">Flexible (m)</th>
                            <th class="py-3 px-4 border-b text-left">Nbre étage</th>
                            <th class="py-3 px-4 border-b text-left">Wifi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="climatiseur in climatiseurs" :key="climatiseur.id">
                            <td class="py-2 px-4 border-b text-gray-700">{{ climatiseur.marque }}</td>
                            <td class="py-2 px-4 border-b text-gray-700">{{ climatiseur.puissance }}</td>
                            <td class="py-2 px-4 border-b text-gray-700">{{ climatiseur.prix_unitaire }}</td>
                            <td class="py-2 px-4 border-b text-gray-700">{{ climatiseur.quantite }}</td>
                            <td class="py-2 px-4 border-b text-gray-700">{{ climatiseur.flexible }}</td>
                            <td class="py-2 px-4 border-b text-gray-700">{{ climatiseur.nbreetage }}</td>
                            <td class="py-2 px-4 border-b text-gray-700">{{ climatiseur.wifi }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Images -->
            <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                <h3 class="text-2xl font-semibold text-gray-700 mb-4">Images</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div v-for="image in images" :key="image.id" class="relative group">
                        <img 
                            :src="image.image_path" 
                            alt="Image" 
                            class="w-full h-auto rounded-lg transition-transform transform group-hover:scale-105 cursor-pointer"
                            @click="window.location.href=`/image/${image.id}`" 
                        />
                        <a :href="image.image_path" class="absolute inset-0" target="blank"></a>
                    </div>
                </div>
            </div>

            <!-- Bouton pour marquer comme terminé -->
            <button 
                @click="showModalHandler" 
                class="bg-green-500 text-white px-6 py-3 rounded-lg text-lg font-semibold hover:bg-green-600 transition duration-200"
            >
                Marquer comme terminé
            </button>
        </div>

        <!-- Modal pour télécharger des photos -->
        <!-- Modal pour télécharger des photos -->
        <div v-if="showModal" class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
            <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md md:max-w-lg lg:max-w-xl h-full md:h-auto md:overflow-visible overflow-y-auto">
                <h3 class="text-xl font-semibold mb-4">Télécharger les Photos</h3>

                <!-- Loader visible pendant le chargement -->
                <div v-if="showLoader" class="flex items-center justify-center my-4">
                    <div class="loader"></div> <!-- Style pour loader dans CSS -->
                </div>
                
                <form @submit.prevent="submitForm" class="flex flex-col space-y-4">

                    <!-- Champs d'upload avec prévisualisation et responsive layout -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div v-for="(label, key) in {
                                photo_emplacement_evaporateur: 'Photo emplacement évaporateur',
                                photo_numero_serie_evaporateur: 'Photo numéro de série évaporateur',
                                photo_raccordement_electrique: 'Photo raccordement électrique',
                                photo_emplacement_condensateur: 'Photo emplacement condensateur',
                                photo_numero_serie_condensateur: 'Photo numéro de série condensateur'
                            }" :key="key" class="mb-4">
                            <label :for="key" class="block text-gray-700 mb-2 text-sm font-semibold">
                                {{ label }} <span class="text-red-500">*</span>
                            </label>
                            <div class="flex items-center gap-2">
                                <input 
                                    type="file" 
                                    :id="key" 
                                    :name="key" 
                                    required 
                                    accept="image/*"
                                    @change="event => fileInputHandler(event, key)"
                                    class="block w-full text-sm text-gray-600 border border-gray-300 rounded p-2"
                                />
                                <div v-if="selectedImages[key]" class="w-16 h-16 border border-gray-300 rounded-lg overflow-hidden">
                                    <img :src="getPreviewUrl(selectedImages[key])" alt="Preview" class="w-full h-full object-cover" />
                                </div>
                            </div>
                            <small class="text-gray-500">Taille maximale : 3 Mo</small>
                        </div>
                    </div>

                    <!-- Barre de progression -->
                    <!-- Barre de progression -->
                    <div v-if="isUploading" class="w-full bg-gray-200 rounded-full h-4">
                        <div :style="{ width: uploadProgress + '%' }" class="bg-blue-500 h-4 rounded-full transition-all duration-500"></div>
                    </div>

                    <!-- Boutons d'action avec responsive adaptatif pour mobile, tablette, et ordinateur -->
                    <div 
    :class="{
        'fixed bottom-0 left-0 w-full bg-white p-4 border-t border-gray-300': isSmallScreen,
        'mt-4 md:relative': !isSmallScreen
    }"
    class="z-50 flex flex-col sm:flex-row sm:space-x-4 lg:space-x-6">
    <button 
        type="submit" 
        :disabled="submitDisabled" 
        class="w-full bg-blue-500 text-white px-4 py-2 rounded-lg text-lg font-semibold hover:bg-blue-600 transition duration-200 disabled:opacity-50"
    >
        Enregistrer
    </button>
    <button 
        type="button" 
        @click="closeModalHandler"
        class="w-full bg-red-500 text-white px-4 py-2 rounded-lg text-lg font-semibold hover:bg-red-600 transition duration-200 mt-4 sm:mt-0"
    >
        Annuler
    </button>
</div>

                </form>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
@media (min-width: 640px) {
    /* Small screens, usually tablets */
    .modal-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }
}

@media (min-width: 1024px) {
    /* Large screens, usually desktops */
    .modal-grid {
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
    }
}

/* Loader CSS */
.loader {
    border: 4px solid rgba(0, 0, 0, 0.1);
    border-left-color: #3498db;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}
</style>