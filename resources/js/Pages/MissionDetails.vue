<script setup>
import { ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';

// Accéder aux données passées depuis Laravel via Inertia
const { props } = usePage();
const mission = ref(props.mission);
const climatiseurs = ref(props.climatiseurs);
const images = ref(props.images);
const showModal = ref(false);

// Fonction pour afficher le modal
const showModalHandler = () => {
    showModal.value = true;
};

// Fonction pour fermer le modal
const closeModalHandler = () => {
    showModal.value = false;
};

// Fonction pour marquer la mission comme terminée
const markAsCompleted = () => {
    showModalHandler();
};

// Fonction pour soumettre les données du formulaire
const submitForm = async (event) => {
    event.preventDefault();
    const formData = new FormData(event.target);

    try {
        await fetch('/poseterminer', {
            method: 'POST',
            body: formData,
        });
        // Mettre à jour l'attribut raisonsocial ici
        await fetch(`/missions/${mission.value.id}/update-raisonsocial`, {
            method: 'PUT',
        });
        closeModalHandler();
    } catch (error) {
        console.error('Erreur lors de l\'envoi des données:', error);
    }
};
</script>

<template>
    <Head title="Détails de la Mission" />
    <AppLayout title="Détails de la Mission">
        <div class="container mx-auto p-6 bg-gray-100 rounded-lg shadow-md">
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
                        <a :href="image.image_path" class="absolute inset-0" target="blanck"></a>
                    </div>
                </div>
            </div>

            <!-- Bouton pour marquer comme terminé -->
            <button 
                @click="markAsCompleted" 
                class="bg-green-500 text-white px-6 py-3 rounded-lg text-lg font-semibold hover:bg-green-600 transition duration-200"
            >
                Marquer comme terminé
            </button>
        </div>

        <!-- Modal pour télécharger des photos -->
        <div v-if="showModal" class="fixed inset-0 flex items-center justify-center z-50 overflow-y-auto bg-black bg-opacity-50">
    <div class="bg-white p-6 rounded-lg shadow-lg max-w-lg w-full">
        <!-- Contenu du modal ici -->
                <h3 class="text-xl font-semibold mb-4">Télécharger les Photos</h3>
                <form method="POST" action="/poseterminer" enctype="multipart/form-data">
   
    <input type="hidden" name="mission_id" :value="mission.id" />

    <div class="mb-2">
        <label for="photo_emplacement_evaporateur" class="block text-gray-700 mb-1 text-sm">
            Photo emplacement évaporateur <span class="text-red-500">*</span>
        </label>
        <input 
            type="file" 
            id="photo_emplacement_evaporateur" 
            name="photo_emplacement_evaporateur" 
            required 
            accept="image/*" 
            class="block w-full text-sm text-gray-700 border border-gray-300 rounded p-1 h-8"
        />
        <small class="text-gray-500">Max 2.5 Mo</small>
    </div>
    
    <div class="mb-2">
        <label for="photo_numero_serie_evaporateur" class="block text-gray-700 mb-1 text-sm">
            Photo numéro de série évaporateur <span class="text-red-500">*</span>
        </label>
        <input 
            type="file" 
            id="photo_numero_serie_evaporateur" 
            name="photo_numero_serie_evaporateur" 
            required 
            accept="image/*" 
            class="block w-full text-sm text-gray-700 border border-gray-300 rounded p-1 h-8"
        />
        <small class="text-gray-500">Max 2.5 Mo</small>
    </div>
    
    <div class="mb-2">
        <label for="photo_raccordement_electrique" class="block text-gray-700 mb-1 text-sm">
            Photo raccordement électrique <span class="text-red-500">*</span>
        </label>
        <input 
            type="file" 
            id="photo_raccordement_electrique" 
            name="photo_raccordement_electrique" 
            required 
            accept="image/*" 
            class="block w-full text-sm text-gray-700 border border-gray-300 rounded p-1 h-8"
        />
        <small class="text-gray-500">Max 2.5 Mo</small>
    </div>
    
    <div class="mb-2">
        <label for="photo_emplacement_condensateur" class="block text-gray-700 mb-1 text-sm">
            Photo emplacement condensateur <span class="text-red-500">*</span>
        </label>
        <input 
            type="file" 
            id="photo_emplacement_condensateur" 
            name="photo_emplacement_condensateur" 
            required 
            accept="image/*" 
            class="block w-full text-sm text-gray-700 border border-gray-300 rounded p-1 h-8"
        />
        <small class="text-gray-500">Max 2.5 Mo</small>
    </div>
    
    <div class="mb-2">
        <label for="photo_numero_serie_condensateur" class="block text-gray-700 mb-1 text-sm">
            Photo numéro de série condensateur <span class="text-red-500">*</span>
        </label>
        <input 
            type="file" 
            id="photo_numero_serie_condensateur" 
            name="photo_numero_serie_condensateur" 
            required 
            accept="image/*" 
            class="block w-full text-sm text-gray-700 border border-gray-300 rounded p-1 h-8"
        />
        <small class="text-gray-500">Max 2.5 Mo</small>
    </div>

    <div class="flex justify-end mt-4">
        <button 
            type="submit" 
            class="bg-blue-500 text-white px-4 py-2 rounded-lg text-lg font-semibold hover:bg-blue-600 transition duration-200"
        >
            Enregistrer
        </button>
        <button 
                            type="button" 
                            @click="showModal = false"
                            class="bg-red-500 text-white px-4 py-2 rounded-lg text-lg font-semibold hover:bg-red-600 transition duration-200 ml-4"
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
/* Ajoute tes styles personnalisés ici */
</style>
<script>
export default {
    data() {
        return {
            showModal: false,
            mission: { id: 1 } // Exemple de données, à adapter
        };
    }
};
</script>

