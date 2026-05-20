<?php

namespace App\Database\Seeders;

use CodeIgniter\Database\Seeder;

class CatalogSeeder extends Seeder
{
    public function run()
    {
        $countries = [
            ['code' => 'EC', 'name_es' => 'Ecuador', 'name_en' => 'Ecuador'],
            ['code' => 'US', 'name_es' => 'Estados Unidos', 'name_en' => 'United States'],
            ['code' => 'CO', 'name_es' => 'Colombia', 'name_en' => 'Colombia'],
            ['code' => 'PE', 'name_es' => 'Perú', 'name_en' => 'Peru'],
            ['code' => 'CL', 'name_es' => 'Chile', 'name_en' => 'Chile'],
            ['code' => 'AR', 'name_es' => 'Argentina', 'name_en' => 'Argentina'],
            ['code' => 'MX', 'name_es' => 'México', 'name_en' => 'Mexico'],
            ['code' => 'BR', 'name_es' => 'Brasil', 'name_en' => 'Brazil'],
            ['code' => 'ES', 'name_es' => 'España', 'name_en' => 'Spain'],
            ['code' => 'DE', 'name_es' => 'Alemania', 'name_en' => 'Germany'],
            ['code' => 'FR', 'name_es' => 'Francia', 'name_en' => 'France'],
            ['code' => 'IT', 'name_es' => 'Italia', 'name_en' => 'Italy'],
            ['code' => 'GB', 'name_es' => 'Reino Unido', 'name_en' => 'United Kingdom'],
            ['code' => 'CN', 'name_es' => 'China', 'name_en' => 'China'],
            ['code' => 'JP', 'name_es' => 'Japón', 'name_en' => 'Japan'],
            ['code' => 'CA', 'name_es' => 'Canadá', 'name_en' => 'Canada'],
            ['code' => 'AU', 'name_es' => 'Australia', 'name_en' => 'Australia'],
        ];
        $this->db->table('catalog_countries')->insertBatch($countries);

        $greetings = [
            ['code' => 'SR', 'name_es' => 'Sr.', 'name_en' => 'Mr.'],
            ['code' => 'SRA', 'name_es' => 'Sra.', 'name_en' => 'Mrs.'],
            ['code' => 'SRTA', 'name_es' => 'Srta.', 'name_en' => 'Ms.'],
            ['code' => 'DR', 'name_es' => 'Dr.', 'name_en' => 'Dr.'],
            ['code' => 'DRA', 'name_es' => 'Dra.', 'name_en' => 'Dr.'],
        ];
        $this->db->table('catalog_greetings')->insertBatch($greetings);

        $languages = [
            ['name_es' => 'Español', 'name_en' => 'Spanish'],
            ['name_es' => 'Inglés', 'name_en' => 'English'],
            ['name_es' => 'Francés', 'name_en' => 'French'],
            ['name_es' => 'Alemán', 'name_en' => 'German'],
            ['name_es' => 'Portugués', 'name_en' => 'Portuguese'],
            ['name_es' => 'Chino', 'name_en' => 'Chinese'],
            ['name_es' => 'Japonés', 'name_en' => 'Japanese'],
        ];
        $this->db->table('catalog_languages')->insertBatch($languages);

        $units = [
            ['code' => 'KG', 'name_es' => 'Kilogramos', 'name_en' => 'Kilograms'],
            ['code' => 'TN', 'name_es' => 'Toneladas', 'name_en' => 'Tons'],
            ['code' => 'L', 'name_es' => 'Litros', 'name_en' => 'Liters'],
            ['code' => 'GL', 'name_es' => 'Galones', 'name_en' => 'Gallons'],
            ['code' => 'UN', 'name_es' => 'Unidades', 'name_en' => 'Units'],
            ['code' => 'PAR', 'name_es' => 'Pares', 'name_en' => 'Pairs'],
            ['code' => 'SET', 'name_es' => 'Sets', 'name_en' => 'Sets'],
            ['code' => 'M2', 'name_es' => 'Metros cuadrados', 'name_en' => 'Square meters'],
            ['code' => 'M3', 'name_es' => 'Metros cúbicos', 'name_en' => 'Cubic meters'],
        ];
        $this->db->table('catalog_units')->insertBatch($units);

        $certificates = [
            ['name_es' => 'ISO 9001', 'name_en' => 'ISO 9001'],
            ['name_es' => 'ISO 14001', 'name_en' => 'ISO 14001'],
            ['name_es' => 'HACCP', 'name_en' => 'HACCP'],
            ['name_es' => 'BRC', 'name_en' => 'BRC'],
            ['name_es' => 'Fair Trade', 'name_en' => 'Fair Trade'],
            ['name_es' => 'Orgánico', 'name_en' => 'Organic'],
            ['name_es' => 'Rainforest Alliance', 'name_en' => 'Rainforest Alliance'],
            ['name_es' => 'UTZ Certified', 'name_en' => 'UTZ Certified'],
            ['name_es' => 'Kosher', 'name_en' => 'Kosher'],
            ['name_es' => 'Halal', 'name_en' => 'Halal'],
        ];
        $this->db->table('catalog_certificates')->insertBatch($certificates);

        $sectors = [
            ['name_es' => 'Agroindustria', 'name_en' => 'Agroindustry'],
            ['name_es' => 'Alimentos y Bebidas', 'name_en' => 'Food and Beverages'],
            ['name_es' => 'Textiles y Confecciones', 'name_en' => 'Textiles and Apparel'],
            ['name_es' => 'Cueros y Calzado', 'name_en' => 'Leather and Footwear'],
            ['name_es' => 'Metalurgia y Manufactura', 'name_en' => 'Metallurgy and Manufacturing'],
            ['name_es' => 'Plásticos y Químicos', 'name_en' => 'Plastics and Chemicals'],
            ['name_es' => 'Madera y Muebles', 'name_en' => 'Wood and Furniture'],
            ['name_es' => 'Turismo', 'name_en' => 'Tourism'],
            ['name_es' => 'Tecnología y Servicios', 'name_en' => 'Technology and Services'],
            ['name_es' => 'Energía Renovable', 'name_en' => 'Renewable Energy'],
        ];
        $this->db->table('sectors')->insertBatch($sectors);

        $roles = [
            ['name' => 'admin'],
            ['name' => 'seller'],
            ['name' => 'buyer'],
        ];
        $this->db->table('roles')->insertBatch($roles);

        $subpartidas = [
            ['code' => '0801.30', 'description_es' => 'Café tostado sin cafeína', 'description_en' => 'Decaffeinated roasted coffee'],
            ['code' => '0801.90', 'description_es' => 'Los demás café tostado', 'description_en' => 'Other roasted coffee'],
            ['code' => '0901.11', 'description_es' => 'Café sin tostar sin descafeinar', 'description_en' => 'Non-roasted decaffeinated coffee'],
            ['code' => '0901.12', 'description_es' => 'Café sin tostar descafeinado', 'description_en' => 'Decaffeinated non-roasted coffee'],
            ['code' => '0901.21', 'description_es' => 'Café sin tostar sin descafeinar', 'description_en' => 'Non-roasted non-decaffeinated coffee'],
            ['code' => '1701.99', 'description_es' => 'Los demás azúcares de caña', 'description_en' => 'Other cane sugars'],
            ['code' => '1801.00', 'description_es' => 'Cacao en grano', 'description_en' => 'Cocoa beans'],
            ['code' => '0306.19', 'description_es' => 'Los demás crustáceos congelados', 'description_en' => 'Other frozen crustaceans'],
            ['code' => '0306.29', 'description_es' => 'Los demás crustáceos vivos', 'description_en' => 'Other live crustaceans'],
            ['code' => '8544.42', 'description_es' => 'Los demás conductores eléctricos', 'description_en' => 'Other electric conductors'],
        ];
        $this->db->table('subpartidas')->insertBatch($subpartidas);
    }
}