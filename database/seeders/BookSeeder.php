<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $imagePool = [
            'images/books/1.img.jpg',
            'images/books/2.img.jpg',
            'images/books/3.img.jpg',
            'images/books/1770800650.jpg',
            'images/books/1770800807.jpg',
        ];

        $books = [
            [
                'title' => 'Laskar Pelangi',
                'author' => 'Andrea Hirata',
                'category' => 'Fiksi',
                'publisher' => 'Bentang Pustaka',
                'year' => 2005,
                'isbn' => '9789791227204',
                'stock' => 8,
                'description' => 'Novel inspiratif tentang perjuangan anak-anak Belitung dalam mengejar pendidikan dengan segala keterbatasan.',
            ],
            [
                'title' => 'Sang Pemimpi',
                'author' => 'Andrea Hirata',
                'category' => 'Fiksi',
                'publisher' => 'Bentang Pustaka',
                'year' => 2006,
                'isbn' => '9789791227211',
                'stock' => 6,
                'description' => 'Lanjutan kisah persahabatan, mimpi besar, dan semangat belajar yang tumbuh di tengah keadaan sederhana.',
            ],
            [
                'title' => 'Negeri 5 Menara',
                'author' => 'Ahmad Fuadi',
                'category' => 'Pendidikan',
                'publisher' => 'Gramedia Pustaka Utama',
                'year' => 2009,
                'isbn' => '9789792232658',
                'stock' => 7,
                'description' => 'Cerita perjuangan santri muda yang belajar disiplin, bahasa, dan kekuatan cita-cita di pesantren.',
            ],
            [
                'title' => 'Ranah 3 Warna',
                'author' => 'Ahmad Fuadi',
                'category' => 'Pendidikan',
                'publisher' => 'Gramedia Pustaka Utama',
                'year' => 2011,
                'isbn' => '9789792263256',
                'stock' => 5,
                'description' => 'Novel yang menekankan kerja keras, kesabaran, dan tekad dalam melanjutkan pendidikan ke jenjang lebih tinggi.',
            ],
            [
                'title' => 'Bumi',
                'author' => 'Tere Liye',
                'category' => 'Fiksi',
                'publisher' => 'Gramedia Pustaka Utama',
                'year' => 2014,
                'isbn' => '9786020300116',
                'stock' => 9,
                'description' => 'Petualangan fantasi remaja yang penuh misteri, kekuatan unik, dan dunia paralel yang menarik.',
            ],
            [
                'title' => 'Bulan',
                'author' => 'Tere Liye',
                'category' => 'Fiksi',
                'publisher' => 'Gramedia Pustaka Utama',
                'year' => 2015,
                'isbn' => '9786020324785',
                'stock' => 7,
                'description' => 'Kelanjutan serial fantasi yang mengajak pembaca menjelajahi konflik, persahabatan, dan keberanian.',
            ],
            [
                'title' => 'Hujan',
                'author' => 'Tere Liye',
                'category' => 'Fiksi',
                'publisher' => 'Gramedia Pustaka Utama',
                'year' => 2016,
                'isbn' => '9786020324789',
                'stock' => 6,
                'description' => 'Kisah emosional tentang kehilangan, pemulihan, dan cinta di tengah dunia yang berubah drastis.',
            ],
            [
                'title' => 'Ayat-Ayat Cinta',
                'author' => 'Habiburrahman El Shirazy',
                'category' => 'Fiksi',
                'publisher' => 'Republika',
                'year' => 2004,
                'isbn' => '9789793210607',
                'stock' => 5,
                'description' => 'Novel religi populer tentang cinta, nilai moral, dan tantangan hidup mahasiswa Indonesia di luar negeri.',
            ],
            [
                'title' => 'Filosofi Teras',
                'author' => 'Henry Manampiring',
                'category' => 'Non-Fiksi',
                'publisher' => 'Kompas',
                'year' => 2018,
                'isbn' => '9786024125180',
                'stock' => 10,
                'description' => 'Pengantar filsafat stoik yang ringan dan aplikatif untuk membantu mengelola emosi dan pikiran.',
            ],
            [
                'title' => 'Atomic Habits',
                'author' => 'James Clear',
                'category' => 'Non-Fiksi',
                'publisher' => 'Gramedia Pustaka Utama',
                'year' => 2019,
                'isbn' => '9786020633177',
                'stock' => 8,
                'description' => 'Buku pengembangan diri tentang membangun kebiasaan kecil yang berdampak besar secara konsisten.',
            ],
            [
                'title' => 'Madilog',
                'author' => 'Tan Malaka',
                'category' => 'Referensi',
                'publisher' => 'Narasi',
                'year' => 2014,
                'isbn' => '9789791684458',
                'stock' => 4,
                'description' => 'Karya pemikiran yang membahas materialisme, dialektika, dan logika dalam konteks kemajuan bangsa.',
            ],
            [
                'title' => 'Bung Karno: Penyambung Lidah Rakyat',
                'author' => 'Cindy Adams',
                'category' => 'Biografi',
                'publisher' => 'Media Pressindo',
                'year' => 2013,
                'isbn' => '9789799114704',
                'stock' => 5,
                'description' => 'Biografi tokoh proklamator yang menampilkan perjalanan hidup, gagasan, dan perjuangannya.',
            ],
            [
                'title' => 'Sejarah Indonesia Modern 1200-2008',
                'author' => 'M. C. Ricklefs',
                'category' => 'Sejarah',
                'publisher' => 'Serambi',
                'year' => 2008,
                'isbn' => '9789790243618',
                'stock' => 4,
                'description' => 'Referensi sejarah yang merangkum perkembangan Indonesia dari masa awal hingga era modern.',
            ],
            [
                'title' => 'Sapiens',
                'author' => 'Yuval Noah Harari',
                'category' => 'Sejarah',
                'publisher' => 'Kepustakaan Populer Gramedia',
                'year' => 2017,
                'isbn' => '9786024246946',
                'stock' => 6,
                'description' => 'Pembahasan sejarah umat manusia dari masa pemburu-peramu hingga masyarakat modern global.',
            ],
            [
                'title' => 'A Brief History of Time',
                'author' => 'Stephen Hawking',
                'category' => 'Sains',
                'publisher' => 'Bantam Books',
                'year' => 1988,
                'isbn' => '9780553380163',
                'stock' => 5,
                'description' => 'Penjelasan populer tentang alam semesta, ruang, waktu, lubang hitam, dan asal mula kosmos.',
            ],
            [
                'title' => 'Fisika Dasar',
                'author' => 'Halliday dan Resnick',
                'category' => 'Sains',
                'publisher' => 'Erlangga',
                'year' => 2010,
                'isbn' => '9789790331452',
                'stock' => 7,
                'description' => 'Buku pelajaran fisika dasar untuk memahami konsep gerak, energi, gaya, dan gelombang.',
            ],
            [
                'title' => 'Biologi Campbell',
                'author' => 'Neil A. Campbell',
                'category' => 'Sains',
                'publisher' => 'Erlangga',
                'year' => 2012,
                'isbn' => '9789790331919',
                'stock' => 5,
                'description' => 'Referensi biologi komprehensif yang membahas sel, genetika, evolusi, dan ekosistem.',
            ],
            [
                'title' => 'Matematika SMA Kelas X',
                'author' => 'Tim Eduka',
                'category' => 'Matematika',
                'publisher' => 'Yudhistira',
                'year' => 2022,
                'isbn' => '9786023012344',
                'stock' => 12,
                'description' => 'Buku pelajaran matematika SMA yang mencakup aljabar, fungsi, persamaan, dan statistika dasar.',
            ],
            [
                'title' => 'Matematika SMA Kelas XI',
                'author' => 'Tim Cerdas Nusantara',
                'category' => 'Matematika',
                'publisher' => 'Yrama Widya',
                'year' => 2022,
                'isbn' => '9786024226719',
                'stock' => 10,
                'description' => 'Materi matematika tingkat lanjut untuk siswa kelas XI dengan contoh soal dan pembahasan.',
            ],
            [
                'title' => 'Bahasa Indonesia untuk SMA',
                'author' => 'E. Kosasih',
                'category' => 'Bahasa',
                'publisher' => 'Erlangga',
                'year' => 2021,
                'isbn' => '9786232661986',
                'stock' => 9,
                'description' => 'Buku bahasa Indonesia yang membantu keterampilan membaca, menulis, menyimak, dan berbicara.',
            ],
            [
                'title' => 'English Grammar in Use',
                'author' => 'Raymond Murphy',
                'category' => 'Bahasa',
                'publisher' => 'Cambridge University Press',
                'year' => 2019,
                'isbn' => '9781108457651',
                'stock' => 7,
                'description' => 'Panduan tata bahasa Inggris yang populer untuk latihan mandiri dan pembelajaran di kelas.',
            ],
            [
                'title' => 'Pemrograman Web dengan Laravel',
                'author' => 'Jubilee Enterprise',
                'category' => 'Teknologi',
                'publisher' => 'Elex Media Komputindo',
                'year' => 2023,
                'isbn' => '9786230049212',
                'stock' => 8,
                'description' => 'Panduan membangun aplikasi web modern menggunakan framework Laravel dari dasar hingga praktik.',
            ],
            [
                'title' => 'Dasar-Dasar Jaringan Komputer',
                'author' => 'Wahana Komputer',
                'category' => 'Teknologi',
                'publisher' => 'Andi Offset',
                'year' => 2021,
                'isbn' => '9789792959304',
                'stock' => 6,
                'description' => 'Buku pengantar jaringan komputer yang membahas topologi, protokol, perangkat, dan konfigurasi.',
            ],
            [
                'title' => 'Pengantar Basis Data',
                'author' => 'Abdul Kadir',
                'category' => 'Teknologi',
                'publisher' => 'Andi Offset',
                'year' => 2019,
                'isbn' => '9789792957713',
                'stock' => 7,
                'description' => 'Materi dasar basis data yang meliputi desain tabel, relasi, query, dan penerapan sistem informasi.',
            ],
            [
                'title' => 'Pendidikan Pancasila',
                'author' => 'Tim Kemendikbud',
                'category' => 'Pendidikan',
                'publisher' => 'Pusat Kurikulum',
                'year' => 2022,
                'isbn' => '9786022449103',
                'stock' => 11,
                'description' => 'Buku pelajaran yang mengupas nilai Pancasila, kewarganegaraan, dan kehidupan sosial berbangsa.',
            ],
            [
                'title' => 'Ensiklopedia Sains Pelajar',
                'author' => 'Tim Redaksi Sains',
                'category' => 'Referensi',
                'publisher' => 'Bhuana Ilmu Populer',
                'year' => 2020,
                'isbn' => '9786230401232',
                'stock' => 6,
                'description' => 'Referensi sains bergambar untuk siswa yang memuat konsep penting dalam fisika, kimia, dan biologi.',
            ],
            [
                'title' => 'Atlas Dunia untuk Pelajar',
                'author' => 'Tim Geografi Nusantara',
                'category' => 'Referensi',
                'publisher' => 'Yudhistira',
                'year' => 2021,
                'isbn' => '9786022998014',
                'stock' => 5,
                'description' => 'Atlas pendidikan dengan peta dunia, peta Indonesia, dan informasi geografis yang mudah dipahami.',
            ],
            [
                'title' => 'Kamus Besar Bahasa Indonesia Ringkas',
                'author' => 'Tim Bahasa Nasional',
                'category' => 'Bahasa',
                'publisher' => 'Balai Pustaka',
                'year' => 2020,
                'isbn' => '9786022601877',
                'stock' => 8,
                'description' => 'Kamus ringkas yang membantu siswa memahami kosakata bahasa Indonesia dengan cepat.',
            ],
            [
                'title' => 'Kimia SMA Kelas XII',
                'author' => 'Mikrajuddin Abdullah',
                'category' => 'Pelajaran',
                'publisher' => 'Grafindo',
                'year' => 2022,
                'isbn' => '9786237489080',
                'stock' => 9,
                'description' => 'Materi kimia kelas XII yang meliputi senyawa organik, elektrokimia, dan aplikasi kimia sehari-hari.',
            ],
            [
                'title' => 'Ekonomi untuk SMA',
                'author' => 'Alam S',
                'category' => 'Pelajaran',
                'publisher' => 'Esis',
                'year' => 2021,
                'isbn' => '9786230018577',
                'stock' => 7,
                'description' => 'Buku ekonomi sekolah menengah yang membahas pasar, konsumsi, produksi, dan kebijakan ekonomi.',
            ],
            [
                'title' => 'B.J. Habibie: Detik-Detik yang Menentukan',
                'author' => 'B. J. Habibie',
                'category' => 'Biografi',
                'publisher' => 'THC Mandiri',
                'year' => 2006,
                'isbn' => '9789799052150',
                'stock' => 4,
                'description' => 'Catatan tokoh nasional mengenai keputusan penting, kepemimpinan, dan perjalanan hidupnya.',
            ],
            [
                'title' => 'Perahu Kertas',
                'author' => 'Dee Lestari',
                'category' => 'Fiksi',
                'publisher' => 'Bentang Pustaka',
                'year' => 2009,
                'isbn' => '9786022914632',
                'stock' => 6,
                'description' => 'Novel remaja tentang mimpi, persahabatan, cinta, dan proses menemukan jati diri.',
            ],
            [
                'title' => 'Cantik Itu Luka',
                'author' => 'Eka Kurniawan',
                'category' => 'Fiksi',
                'publisher' => 'Gramedia Pustaka Utama',
                'year' => 2015,
                'isbn' => '9786020332797',
                'stock' => 3,
                'description' => 'Novel sastra Indonesia dengan gaya penceritaan kuat yang memadukan sejarah, mitos, dan tragedi.',
            ],
        ];

        $inserted = 0;
        $updated = 0;
        $hasCategoryColumn = Book::hasCategoryColumn();

        foreach ($books as $index => $book) {
            $payload = $book;
            $payload['image'] = $imagePool[$index % count($imagePool)];

            if (!$hasCategoryColumn) {
                unset($payload['category']);
            }

            $existing = Book::where('title', $payload['title'])->first();

            if ($existing) {
                $existing->update($payload);
                $updated++;
                continue;
            }

            Book::create($payload);
            $inserted++;
        }

        $this->command->info($inserted . ' buku baru ditambahkan.');
        $this->command->info($updated . ' buku lama diperbarui.');
        $this->command->info('Total katalog siap pakai: ' . count($books) . ' buku.');
    }
}
