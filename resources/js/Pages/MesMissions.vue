<script setup>
import { computed, ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';

// Accéder aux données des missions passées depuis Laravel via Inertia
const { props } = usePage();
const missions = computed(() => props.missions || []);
const searchQuery = ref('');

// Filtrage des missions
const filteredMissions = computed(() => {
    const query = searchQuery.value.toLowerCase();
    return missions.value.filter(mission => {
        // Convertir les propriétés en chaînes pour éviter les erreurs
        const nom = String(mission.nom || '').toLowerCase();
        const statut = String(mission.statut || '').toLowerCase();
        return nom.includes(query) || statut.includes(query);
    });
});

const viewMissionDetails = (missionId) => {
    window.location.href = `/missionsz/${missionId}`;
};

const markAsCompleted = (missionId) => {
    console.log(`Mission ${missionId} marquée comme terminée`);
};
</script>

<template>
    <Head title="Mes missions" />
    <AppLayout title="Dashboard">
        <div class="container mx-auto p-6">
            <h2 class="text-3xl font-semibold mb-6">Mes Missions</h2>
            
            <!-- Barre de recherche -->
            <div class="mb-6">
                <input
                    v-model="searchQuery"
                    type="text"
                    placeholder="Rechercher par nom ou statut..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
            </div>

            <!-- Tableau des missions -->
            <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-lg">
                <thead class="bg-gray-200 border-b border-gray-300">
                    <tr>
                        <th class="py-3 px-4 text-left font-medium text-gray-700">ID</th>
                        <th class="py-3 px-4 text-left font-medium text-gray-700">Nom</th>
                        <th class="py-3 px-4 text-left font-medium text-gray-700">Prénom</th>
                        <th class="py-3 px-4 text-left font-medium text-gray-700">Portable</th>
                        <th class="py-3 px-4 text-left font-medium text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="mission in filteredMissions" :key="mission.id" class="hover:bg-gray-50">
                        <td class="py-3 px-4 border-b text-gray-700">{{ mission.id }}</td>
                        <td class="py-3 px-4 border-b text-gray-700">{{ mission.nom }}</td>
                        <td class="py-3 px-4 border-b text-gray-700">{{ mission.prenom }}</td>
                        <td class="py-3 px-4 border-b text-gray-700">{{ mission.portable }}</td>
                        <td class="py-3 px-4 border-b">
                            <button
                                @click="viewMissionDetails(mission.id)"
                                :class="[
                                    'px-4 py-2 rounded font-semibold text-white',
                                    mission.raisonsocial == 1 ? 'bg-orange-500 hover:bg-orange-600' : 'bg-blue-500 hover:bg-blue-600'
                                ]"
                            >
                                {{ mission.raisonsocial == 1 ? 'Plus' : 'Détails et démarré' }}
                            </button>
                            <!-- Notification -->
                            <div v-if="mission.raisonsocial !== undefined" class="mt-2">
                                <div
                                    v-if="mission.raisonsocial == 1"
                                    class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded-md relative"
                                    role="alert"
                                >
                                    <strong class="font-bold">Attention !</strong><br>
                                    <span class="block sm:inline">En cours de validation</span>
                                </div>
                                <div
                                    v-else
                                    class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-md relative"
                                    role="alert"
                                >
                                    <strong class="font-bold">Attention !</strong>
                                    <span class="block sm:inline">Mission non démarrée <br> ou à corriger</span>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AppLayout>
</template>

<style scoped>
/* Ajoute tes styles personnalisés ici */
</style>
