<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\City;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            'Amazonas' => ['Leticia', 'Puerto Nariño', 'La Chorrera'],
            'Antioquia' => ['Medellín', 'Bello', 'Itagüí'],
            'Arauca' => ['Arauca', 'Tame', 'Saravena'],
            'Atlántico' => ['Barranquilla', 'Soledad', 'Malambo'],
            'Bolívar' => ['Cartagena', 'Magangué', 'Turbaco'],
            'Boyacá' => ['Tunja', 'Duitama', 'Sogamoso'],
            'Caldas' => ['Manizales', 'La Dorada', 'Chinchiná'],
            'Caquetá' => ['Florencia', 'San Vicente del Caguán', 'Puerto Rico'],
            'Casanare' => ['Yopal', 'Aguazul', 'Villanueva'],
            'Cauca' => ['Popayán', 'Santander de Quilichao', 'Puerto Tejada'],
            'Cesar' => ['Valledupar', 'Aguachica', 'Bosconia'],
            'Chocó' => ['Quibdó', 'Istmina', 'Condoto'],
            'Córdoba' => ['Montería', 'Cereté', 'Lorica'],
            'Cundinamarca' => ['Bogotá', 'Soacha', 'Fusagasugá'],
            'Guainía' => ['Inírida', 'Barranco Minas', 'Mapiripana'],
            'Guaviare' => ['San José del Guaviare', 'Calamar', 'El Retorno'],
            'Huila' => ['Neiva', 'Pitalito', 'Garzón'],
            'La Guajira' => ['Riohacha', 'Maicao', 'Uribia'],
            'Magdalena' => ['Santa Marta', 'Ciénaga', 'Fundación'],
            'Meta' => ['Villavicencio', 'Acacías', 'Granada'],
            'Nariño' => ['Pasto', 'Tumaco', 'Ipiales'],
            'Norte de Santander' => ['Cúcuta', 'Ocaña', 'Pamplona'],
            'Putumayo' => ['Mocoa', 'Puerto Asís', 'Orito'],
            'Quindío' => ['Armenia', 'Calarcá', 'La Tebaida'],
            'Risaralda' => ['Pereira', 'Dosquebradas', 'Santa Rosa de Cabal'],
            'San Andrés y Providencia' => ['San Andrés', 'Providencia', 'Santa Catalina'],
            'Santander' => ['Bucaramanga', 'Floridablanca', 'Girón'],
            'Sucre' => ['Sincelejo', 'Corozal', 'San Marcos'],
            'Tolima' => ['Ibagué', 'Espinal', 'Girardot'],
            'Valle del Cauca' => ['Cali', 'Palmira', 'Buenaventura'],
            'Vaupés' => ['Mitú', 'Caruru', 'Taraira'],
            'Vichada' => ['Puerto Carreño', 'La Primavera', 'Cumaribo'],
        ];

        foreach ($departments as $departmentName => $cities) {
            $department = Department::create([
                'name' => $departmentName
            ]);

            foreach ($cities as $cityName) {
                City::create([
                    'department_id' => $department->id,
                    'name' => $cityName
                ]);
            }
        }
    }
}
