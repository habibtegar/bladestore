<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            // ELEKTRONIK
            [
                'name' => 'TWS Bluetooth 5.3 Earbuds Noise Cancelling',
                'category' => 'Elektronik',
                'description' => 'Earbuds nirkabel dengan latensi rendah, active noise cancellation, daya tahan baterai hingga 28 jam dengan casing pengisian, dan sertifikasi tahan air IPX5.',
                'price' => 249000,
                'stock' => 35,
                'image' => 'https://images.unsplash.com/photo-1590658268037-6bf12165a8df?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'name' => 'Smartwatch Fitness Tracker AMOLED Display',
                'category' => 'Elektronik',
                'description' => 'Jam tangan pintar layar sentuh AMOLED 1.43 inch dengan monitor detak jantung, sensor SpO2, pelacak 100+ mode olahraga, dan notifikasi pesan instan.',
                'price' => 489000,
                'stock' => 20,
                'image' => 'https://images.unsplash.com/photo-1579586337278-3befd40fd17a?auto=format&fit=crop&w=600&q=80',
            ],

            // FASHION
            [
                'name' => 'Kemeja Flannel Casual Pria Premium',
                'category' => 'Fashion',
                'description' => 'Kemeja lengan panjang berbahan katun flannel lembut dan hangat. Cocok untuk hangout maupun aktivitas kasual sehari-hari.',
                'price' => 189000,
                'stock' => 25,
                'image' => 'https://images.unsplash.com/photo-1596755094514-f87e34085b2c?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'name' => 'Kaos Polos Cotton Combed 30s',
                'category' => 'Fashion',
                'description' => 'T-shirt basic katun combed 30s premium yang adem, menyerap keringat dengan baik, dan tidak mudah melar.',
                'price' => 75000,
                'stock' => 50,
                'image' => 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'name' => 'Jaket Denim Vintage Classic',
                'category' => 'Fashion',
                'description' => 'Jaket jeans dengan potongan regular fit dan detail washed yang stylish. Tahan lama dan nyaman dipakai di segala cuaca.',
                'price' => 349000,
                'stock' => 15,
                'image' => 'https://images.unsplash.com/photo-1576995853123-5a10305d93c0?auto=format&fit=crop&w=600&q=80',
            ],

            // KECANTIKAN
            [
                'name' => 'Brightening Face Serum Niacinamide 10%',
                'category' => 'Kecantikan',
                'description' => 'Serum wajah dengan konsentrasi tinggi Niacinamide dan Zinc PCA untuk menyamarkan noda hitam, meratakan warna kulit, dan mengontrol minyak berlebih.',
                'price' => 119000,
                'stock' => 40,
                'image' => 'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'name' => 'Sunscreen Gel SPF 50+ PA++++ UV Shield',
                'category' => 'Kecantikan',
                'description' => 'Tabir surya tekstur gel ringan tanpa white-cast, cepat meresap dengan proteksi maksimal sinar UVA dan UVB untuk semua jenis kulit.',
                'price' => 89000,
                'stock' => 60,
                'image' => 'https://images.unsplash.com/photo-1556228720-195a672e8a03?auto=format&fit=crop&w=600&q=80',
            ],

            // RUMAH TANGGA
            [
                'name' => 'Humidifier Diffuser Aromaterapi LED 500ml',
                'category' => 'Rumah Tangga',
                'description' => 'Pelembab udara ultrasonik dengan lampu tidur LED 7 warna, kapasitas air 500ml, mode timer otomatis, dan kompatibel dengan essential oil.',
                'price' => 135000,
                'stock' => 30,
                'image' => 'https://images.unsplash.com/photo-1544816155-12df9643f363?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'name' => 'Set Pisau Dapur Stainless Steel 6 In 1',
                'category' => 'Rumah Tangga',
                'description' => 'Set pisau dapur premium berlapis anti-lengket dengan gagang ergonomis. Termasuk pisau chef, pisau roti, pisau utilitas, gunting, dan pengupas.',
                'price' => 179000,
                'stock' => 18,
                'image' => 'https://images.unsplash.com/photo-1593618998160-e34014e67546?auto=format&fit=crop&w=600&q=80',
            ],

            // MAKANAN KEMASAN
            [
                'name' => 'Kopi Arabika Gayo Single Origin 250g',
                'category' => 'Makanan Kemasan',
                'description' => 'Biji kopi sangrai specialty dari dataran tinggi Aceh Gayo. Karakter rasa kaya aroma floral, citrus segar, dan sentuhan cokelat karamel.',
                'price' => 95000,
                'stock' => 45,
                'image' => 'https://images.unsplash.com/photo-1559056199-641a0ac8b55e?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'name' => 'Granola Roasted Almond & Honey Sehat 400g',
                'category' => 'Makanan Kemasan',
                'description' => 'Sereal sarapan sehat dari oat gandum panggang, kacang almond utuh, biji chia, dan madu alami tanpa pemanis buatan maupun pengawet.',
                'price' => 78000,
                'stock' => 35,
                'image' => 'https://images.unsplash.com/photo-1517673132405-a56a62b18caf?auto=format&fit=crop&w=600&q=80',
            ],

            // OLAHRAGA
            [
                'name' => 'Matras Yoga Anti-Slip TPE Eco 6mm',
                'category' => 'Olahraga',
                'description' => 'Matras senam yoga dan pilates berbahan TPE ramah lingkungan dengan tekstur ganda anti-selip, tebal 6mm empuk melindungi lutut dan sendi.',
                'price' => 165000,
                'stock' => 22,
                'image' => 'https://images.unsplash.com/photo-1601925260368-ae2f83cf8b7f?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'name' => 'Resistance Band Set 5 In 1 Fitness',
                'category' => 'Olahraga',
                'description' => 'Set tali elastis latihan otot 5 tingkat beban (10 lbs hingga 50 lbs) dilengkapi pegangan busa lembut, strap pergelangan, dan jangkar pintu.',
                'price' => 110000,
                'stock' => 28,
                'image' => 'https://images.unsplash.com/photo-1598971639058-fab3c3109a00?auto=format&fit=crop&w=600&q=80',
            ],

            // AKSESORI
            [
                'name' => 'Tas Ransel Laptop Waterproof 20L',
                'category' => 'Aksesori',
                'description' => 'Backpack modern dengan kompartemen laptop hingga 15.6 inch, bahan tahan percikan air, serta slot charger USB terintegrasi.',
                'price' => 299000,
                'stock' => 30,
                'image' => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'name' => 'Sepatu Sneaker Canvas Low-Top',
                'category' => 'Aksesori',
                'description' => 'Sneakers kanvas dengan sol karet anti-slip dan insole empuk. Desain minimalis yang pas dipadukan dengan berbagai outfit.',
                'price' => 275000,
                'stock' => 20,
                'image' => 'https://images.unsplash.com/photo-1525966222134-fcfa99b8ae77?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'name' => 'Jam Tangan Kulit Minimalist Pria',
                'category' => 'Aksesori',
                'description' => 'Jam tangan analog dengan strap kulit sintetis berkualitas tinggi dan dial stainless steel tahan air (water resistant 3ATM).',
                'price' => 450000,
                'stock' => 12,
                'image' => 'https://images.unsplash.com/photo-1524805444758-089113d48a6d?auto=format&fit=crop&w=600&q=80',
            ],

            // OTOMOTIF
            [
                'name' => 'Holder HP Mobil Magnetic Dashboard Rotasi 360',
                'category' => 'Otomotif',
                'description' => 'Dudukan smartphone untuk dashboard mobil dengan magnet Neodymium N52 super kuat, rotasi bola 360 derajat, dan perekat 3M VHB tahan panas.',
                'price' => 69000,
                'stock' => 50,
                'image' => 'https://images.unsplash.com/photo-1584438784894-089d6a62b8fa?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'name' => 'Pompa Ban Elektrik Portable Digital LCD',
                'category' => 'Otomotif',
                'description' => 'Kompresor udara portabel baterai isi ulang 4000mAh dengan layar digital indikator tekanan PSI/Bar otomatis berhenti saat tekanan tercapai.',
                'price' => 299000,
                'stock' => 16,
                'image' => 'https://images.unsplash.com/photo-1486006920555-c77dce18193b?auto=format&fit=crop&w=600&q=80',
            ],
        ];

        foreach ($products as $item) {
            $slug = Str::slug($item['name']);
            Product::updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $item['name'],
                    'category' => $item['category'],
                    'description' => $item['description'],
                    'price' => $item['price'],
                    'stock' => $item['stock'],
                    'image' => $item['image'],
                ]
            );
        }
    }
}
