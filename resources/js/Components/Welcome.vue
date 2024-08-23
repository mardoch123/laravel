<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import ApplicationMark from '@/Components/ApplicationMark.vue';
import Banner from '@/Components/Banner.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';

defineProps({
    title: String,
});

const showingNavigationDropdown = ref(false);

const switchToTeam = (team) => {
    router.put(route('current-team.update'), {
        team_id: team.id,
    }, {
        preserveState: false,
    });
};

const logout = () => {
    router.post(route('logout'));
};
</script>
<template>
    <div class="container mx-auto p-6">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Cadre 1: Mes Missions -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
          <img
            src="/solar-8499901_640.jpg"
            alt="Installation de panneaux solaires"
            class="w-full h-40 object-cover"
          />
          <div class="p-6">
            <h2 class="text-2xl font-semibold mb-4">Mes Missions</h2>
            <p class="text-gray-600 mb-4">Consultez et gérez vos missions d'installation de panneaux solaires.</p>

            <NavLink :href="route('mes-missions')" :active="route().current('mes-missions')" 
            class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                                    Mes Missions
                                </NavLink>
          </div>
        </div>
  
        <!-- Cadre 2: Missions -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
          <img
            src="/solar-panel-7518786_640.jpg"
            alt="Missions disponibles"
            class="w-full h-40 object-cover"
          />
          <div class="p-6">
            <h2 class="text-2xl font-semibold mb-4">Missions</h2>
            <p class="text-gray-600 mb-4">Explorez les missions disponibles pour l'installation de systèmes de climatisation.</p>

            <NavLink :href="route('missions.index')" :active="route().current('missions-disponibles')" 
            class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                Missions
            </NavLink>
          </div>
        </div>
  
        <!-- Cadre 3: Missions Rejetées -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
          <img
            src="/solar-system-2939551_640.jpg"
            alt="Missions rejetées"
            class="w-full h-40 object-cover"
          />
          <div class="p-6">
            <h2 class="text-2xl font-semibold mb-4">Missions Rejetées</h2>
            <p class="text-gray-600 mb-4">Consultez les missions rejetées pour les installations de panneaux solaires et de climatisation.</p>

            <NavLink :href="route('missions.rejetees')" :active="route().current('missions/rejetees')" 
            class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                                    Rejetées
                                </NavLink>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <style scoped>
  /* Ajoutez des styles personnalisés ici si nécessaire */
  </style>
  