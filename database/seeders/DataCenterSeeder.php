<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Role;
use App\Models\User;
use App\Models\Resource;
use App\Models\ResourceCategory;
use Illuminate\Support\Facades\Hash;

class DataCenterSeeder extends Seeder
{
    public function run(): void
    {
        // 0. NETTOYAGE (Évite les erreurs de clés étrangères)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Role::truncate();
        User::truncate();
        Resource::truncate();
        ResourceCategory::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 1. CRÉATION DES RÔLES (Sans la colonne 'description' qui cause l'erreur)
        $adminRole = Role::create(['name' => 'Admin']);
        $techRole = Role::create(['name' => 'Responsable Technique']);
        $userRole = Role::create(['name' => 'Utilisateur Interne']);

        // 2. CRÉATION DES CATÉGORIES
        $catSrv = ResourceCategory::create([
            'name' => 'Serveur',
            'description' => 'Serveurs haute performance',
            'icon' => 'bx bxs-server',
            'img' => 'Serveur.png'
        ]);

        $catVM = ResourceCategory::create([
            'name' => 'VM',
            'description' => 'Machines virtuelles Cloud',
            'icon' => 'bx bxs-cloud',
            'img' => 'VM.png'
        ]);

        $catSto = ResourceCategory::create([
            'name' => 'Stockage',
            'description' => 'Solutions NVMe et SAN',
            'icon' => 'bx bxs-hdd',
            'img' => 'Stockage.png'
        ]);

        $catNet = ResourceCategory::create([
            'name' => 'Réseau',
            'description' => 'Infrastructure réseau 100Gbps',
            'icon' => 'bx bx-transfer-alt',
            'img' => 'Réseau.png'
        ]);

        $catFw = ResourceCategory::create([
            'name' => 'Firewall',
            'description' => 'Sécurité réseau avancée',
            'icon' => 'bx bxs-shield',
            'img' => 'Firewall.png'
        ]);

        $catLb = ResourceCategory::create([
            'name' => 'Load Balancer',
            'description' => 'Répartitio optimisée',
            'icon' => 'bx bx-loader-circle',
            'img' => 'Balancer.png'
        ]);

        $catBkp = ResourceCategory::create([
            'name' => 'Backup',
            'description' => 'Sauvegarde et restauration',
            'icon' => 'bx bx-data',
            'img' => 'Backup.png'
        ]);

        $catMon = ResourceCategory::create([
            'name' => 'Monitoring',
            'description' => 'Surveillance et alertes système',
            'icon' => 'bx bx-line-chart',
            'img' => 'Monitoring.png'
        ]);


        // 3. CRÉATION DES UTILISATEURS
        User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => Hash::make('admin12345'),
            'role_id' => $adminRole->id,
            'status' => 'active'
        ]);

        User::create([
            'name' => 'Responsable technique',
            'email' => 'tech@test.com',
            'password' => Hash::make('tech12345'),
            'role_id' => $techRole->id,
            'status' => 'active'
        ]);

        User::create([
            'name' => 'Utilisateur Interne',
            'email' => 'user@test.com',
            'password' => Hash::make('user12345'),
            'role_id' => $userRole->id,
            'status' => 'active'
        ]);

        // 4. GÉNÉRATION DES 50 RESSOURCES (Correction de l'erreur SQL 'type')
        $models = [
            'VM' => ['Ubuntu Server', 'Windows Core'],
            'Stockage' => ['NetApp SAN', 'Synology NAS'],
            'Réseau' => ['Cisco Switch', 'Juniper Router']
        ];
        // 1. SERVEURS (ID: 1) - 5 équipements
        for ($i = 1; $i <= 5; $i++) {
            Resource::create([
                'name' => "Equipement-IT-$i",
                'resource_category_id' => 1,
                'cpu' => rand(8, 32) . ' Cores',
                'ram' => rand(16, 128) . ' Go',
                'os' => 'Linux / Windows',
                'location' => 'Rack-' . rand(1, 20),
                'status' => 'available',
                'bandwidth' => null,
                'capacity' => null // Non compatibles
            ]);
        }

        // 2. VM (ID: 2) - 15 équipements
        for ($i = 6; $i <= 11; $i++) {
            Resource::create([
                'name' => "Equipement-IT-$i",
                'resource_category_id' => 2,
                'cpu' => rand(2, 8) . ' vCPU',
                'ram' => rand(4, 32) . ' Go',
                'capacity' => rand(100, 2000) . ' Go',
                'os' => 'Ubuntu / Debian',
                'location' => 'Virtual-Cluster',
                'status' => 'available',
                'bandwidth' => null
            ]);
        }

        // 3. STOCKAGE (ID: 3) - 10 équipements
        for ($i = 12; $i <= 17; $i++) {
            Resource::create([
                'name' => "Equipement-IT-$i",
                'resource_category_id' => 3,
                'capacity' => rand(1, 100) . ' To',
                'location' => 'Baie-Stockage-' . rand(1, 5),
                'status' => 'available',
                'cpu' => null,
                'ram' => null,
                'bandwidth' => null,
                'os' => null
            ]);
        }

        // 4. RÉSEAU (ID: 4) - 10 équipements
        for ($i = 18; $i <= 22; $i++) {
            Resource::create([
                'name' => "Equipement-IT-$i",
                'resource_category_id' => 4,
                'bandwidth' => rand(1, 100) . ' Gbps',
                'location' => 'Rack-Network-' . rand(1, 10),
                'status' => 'available',
                'cpu' => null,
                'ram' => null,
                'capacity' => null,
                'os' => 'Cisco IOS'
            ]);
        }

        // 5. FIREWALL (ID: 5) – 5 équipements
        for ($i = 23; $i <= 30; $i++) {
            Resource::create([
                'name' => "Firewall-$i",
                'resource_category_id' => 5,
                'bandwidth' => rand(10, 100) . ' Gbps',
                'location' => 'Zone-Sécurité-' . rand(1, 3),
                'status' => 'available',
                'cpu' => null,
                'ram' => null,
                'capacity' => null,
                'os' => 'FortiOS / pfSense'
            ]);
        }

        // 6. LOAD BALANCER (ID: 6) – 5 équipements
        for ($i = 31; $i <= 37; $i++) {
            Resource::create([
                'name' => "LoadBalancer-$i",
                'resource_category_id' => 6,
                'bandwidth' => rand(20, 200) . ' Gbps',
                'location' => 'Datacenter-Core',
                'status' => 'available',
                'cpu' => null,
                'ram' => null,
                'capacity' => null,
                'os' => 'HAProxy / Nginx'
            ]);
        }


        // 7. BACKUP (ID: 7) – 5 équipements
        for ($i = 38; $i <= 42; $i++) {
            Resource::create([
                'name' => "Backup-System-$i",
                'resource_category_id' => 7,
                'capacity' => rand(10, 500) . ' To',
                'location' => 'Salle-Backup',
                'status' => 'available',
                'cpu' => null,
                'ram' => null,
                'bandwidth' => null,
                'os' => 'Veeam / Bacula'
            ]);
        }


        // 8. MONITORING (ID: 8) – 5 équipements
        for ($i = 43; $i <= 50; $i++) {
            Resource::create([
                'name' => "Monitoring-$i",
                'resource_category_id' => 8,
                'cpu' => rand(4, 16) . ' Cores',
                'ram' => rand(8, 64) . ' Go',
                'location' => 'Salle-Supervision',
                'status' => 'available',
                'capacity' => null,
                'bandwidth' => null,
                'os' => 'Zabbix / Prometheus'
            ]);
        }
    }
}
