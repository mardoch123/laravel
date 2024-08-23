

<template>
    <AppLayout title="Dashboard">
    <Head title="Missions Rejetées"/>
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold mb-4">Liste des Missions Rejetées</h1>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prénom</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Portable</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="mission in missions" :key="mission.id">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ mission.nom }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ mission.prenom }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ mission.portable }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span :class="getStatusClass(mission.statut)">
                            {{ getStatusText(mission.statut) }}
                        </span>
                    </td>
                </tr>
                <tr v-if="missions.length === 0">
                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">Aucune mission trouvée</td>
                </tr>
            </tbody>
        </table>
    </div>
</AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    missions: Array,
    csrfToken: String
});

const getStatusClass = (status) => {
    switch (status) {
        case 0: return 'bg-red-100 text-red-800';
        case 1: return 'bg-green-100 text-green-800';
        default: return 'bg-yellow-100 text-yellow-800';
    }
};

const getStatusText = (status) => {
    switch (status) {
        case 0: return 'Rejeté';
        case 1: return 'Validé';
        default: return 'Refusé';
    }
};
</script>
