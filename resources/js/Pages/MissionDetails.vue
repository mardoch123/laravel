<script setup>
import { ref, onMounted } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';

// Variables récupérées des props via Inertia.js
const { props } = usePage();
const mission = ref(props.mission); // Les informations de la mission sont issues des props envoyées depuis le contrôleur
const climatiseurs = ref(props.climatiseurs); // Liste des climatiseurs envoyée depuis le serveur
const images = ref(props.images); // Images associées à la mission

// Gestion des modales (par exemple pour les images)
const showModal = ref(false);
const showModalHandler = () => {
    showModal.value = true;
};
const closeModalHandler = () => {
    showModal.value = false;
};
</script>

<template>
    <Head title="Détails de la Mission" />
    <AppLayout title="Détails de la Mission">
        <div class="container mx-auto p-6 bg-gray-100 rounded-lg shadow-md overflow-x-auto">
            <h2 class="text-3xl font-extrabold text-gray-800 mb-6">Détails de la Mission</h2>

            <!-- Informations de la Mission -->
            <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                <h3 class="text-2xl font-semibold text-gray-700 mb-4">Informations de la Mission</h3>
                <p class="text-gray-600 mb-2"><strong>ID:</strong> {{ mission.id }}</p>
                <p class="text-gray-600 mb-2"><strong>Description:</strong> {{ mission.observations }}</p>
                <p class="text-gray-600 mb-2"><strong>Adresse:</strong> {{ mission.adresse }}</p>
                <p class="text-gray-600"><strong>Date:</strong> {{ mission.date }}</p> <!-- Affichage de la date -->
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

            <!-- Bouton -->
            <button 
                :onclick="`window.location.href='/imagesin?mission_id=${mission.id}'`" 
                class="bg-green-500 text-white px-6 py-3 rounded-lg text-lg font-semibold hover:bg-green-600 transition duration-200"
            >
                Marquer comme terminé
            </button>
        </div>
    </AppLayout>
</template>

<style scoped>
@media (min-width: 640px) {
    .modal-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }
}

@media (min-width: 1024px) {
    .modal-grid {
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
    }
}
</style>