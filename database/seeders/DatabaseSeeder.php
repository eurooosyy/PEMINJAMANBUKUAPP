<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Buat Role jika belum ada
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $petugasRole = Role::firstOrCreate(['name' => 'Petugas']);
        $userRole = Role::firstOrCreate(['name' => 'User']);
        $siswaRole = Role::firstOrCreate(['name' => 'Siswa']);

        // Buat User Admin jika belum ada
        User::firstOrCreate(
            ['email' => 'admin@mail.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('12345678'),
                'role_id' => $adminRole->id
            ]
        );

        // Buat User Petugas jika belum ada
        User::firstOrCreate(
            ['email' => 'petugas@mail.com'],
            [
                'name' => 'Petugas User',
                'password' => bcrypt('12345678'),
                'role_id' => $petugasRole->id
            ]
        );

        // Buat User Siswa jika belum ada
        User::firstOrCreate(
            ['email' => 'siswa@mail.com'],
            [
                'name' => 'Siswa User',
                'password' => bcrypt('12345678'),
                'role_id' => $siswaRole->id
            ]
        );

        // Tambahkan data peralatan contoh
        $equipment = [
            [
                'nama_peralatan' => 'Multimeter Digital',
                'merk' => 'Fluke',
                'nomor_identitas' => 'MT001',
                'tahun_pembelian' => '2023',
                'kategori' => 'Pengukur Listrik',
                'stock' => 5,
                'description' => 'A young wizard discovers his magical heritage.'
            ],
            [
                'title' => 'To Kill a Mockingbird',
                'author' => 'Harper Lee',
                'isbn' => '978-0-06-112008-4',
                'publisher' => 'J. B. Lippincott & Co.',
                'year' => 1960,
                'stock' => 3,
                'description' => 'A story of racial injustice and childhood innocence.'
            ],
            [
                'title' => '1984',
                'author' => 'George Orwell',
                'isbn' => '978-0-452-28423-4',
                'publisher' => 'Secker & Warburg',
                'year' => 1949,
                'stock' => 4,
                'description' => 'A dystopian novel about totalitarianism.'
            ],
            [
                'title' => 'The Great Gatsby',
                'author' => 'F. Scott Fitzgerald',
                'isbn' => '978-0-7432-7356-5',
                'publisher' => 'Charles Scribner\'s Sons',
                'year' => 1925,
                'stock' => 6,
                'description' => 'A classic American novel about the Jazz Age.'
            ],
            [
                'title' => 'Pride and Prejudice',
                'author' => 'Jane Austen',
                'isbn' => '978-0-14-143951-8',
                'publisher' => 'T. Egerton',
                'year' => 1813,
                'stock' => 4,
                'description' => 'A romantic novel about manners and marriage.'
            ],
            [
                'title' => 'The Catcher in the Rye',
                'author' => 'J.D. Salinger',
                'isbn' => '978-0-316-76948-0',
                'publisher' => 'Little, Brown and Company',
                'year' => 1951,
                'stock' => 3,
                'description' => 'A controversial novel about teenage angst.'
            ],
            [
                'title' => 'Lord of the Rings: The Fellowship of the Ring',
                'author' => 'J.R.R. Tolkien',
                'isbn' => '978-0-544-00341-5',
                'publisher' => 'George Allen & Unwin',
                'year' => 1954,
                'stock' => 5,
                'description' => 'An epic fantasy adventure.'
            ],
            [
                'title' => 'The Hobbit',
                'author' => 'J.R.R. Tolkien',
                'isbn' => '978-0-547-92822-7',
                'publisher' => 'George Allen & Unwin',
                'year' => 1937,
                'stock' => 4,
                'description' => 'A fantasy adventure for all ages.'
            ],
            [
                'title' => 'Dune',
                'author' => 'Frank Herbert',
                'isbn' => '978-0-441-17271-9',
                'publisher' => 'Chilton Books',
                'year' => 1965,
                'stock' => 3,
                'description' => 'A science fiction masterpiece.'
            ],
            [
                'title' => 'Neuromancer',
                'author' => 'William Gibson',
                'isbn' => '978-0-441-56956-4',
                'publisher' => 'Ace Books',
                'year' => 1984,
                'stock' => 2,
                'description' => 'The definitive cyberpunk novel.'
            ],
            [
                'title' => 'The Hitchhiker\'s Guide to the Galaxy',
                'author' => 'Douglas Adams',
                'isbn' => '978-0-345-39180-3',
                'publisher' => 'Pan Books',
                'year' => 1979,
                'stock' => 4,
                'description' => 'A hilarious science fiction comedy.'
            ],
            [
                'title' => 'Ender\'s Game',
                'author' => 'Orson Scott Card',
                'isbn' => '978-0-8125-5070-2',
                'publisher' => 'Tor Books',
                'year' => 1985,
                'stock' => 3,
                'description' => 'A military science fiction classic.'
            ],
            [
                'title' => 'The Name of the Wind',
                'author' => 'Patrick Rothfuss',
                'isbn' => '978-0-7564-0407-9',
                'publisher' => 'DAW Books',
                'year' => 2007,
                'stock' => 5,
                'description' => 'An epic fantasy novel about a gifted young man.'
            ],
            [
                'title' => 'Mistborn: The Final Empire',
                'author' => 'Brandon Sanderson',
                'isbn' => '978-0-7653-5178-1',
                'publisher' => 'Tor Books',
                'year' => 2006,
                'stock' => 4,
                'description' => 'A fantasy novel about a heist in a dystopian world.'
            ],
            [
                'title' => 'The Way of Kings',
                'author' => 'Brandon Sanderson',
                'isbn' => '978-0-7653-2635-1',
                'publisher' => 'Tor Books',
                'year' => 2010,
                'stock' => 6,
                'description' => 'An epic fantasy novel about war and honor.'
            ],
            [
                'title' => 'Assassin\'s Apprentice',
                'author' => 'Robin Hobb',
                'isbn' => '978-0-553-57345-6',
                'publisher' => 'Bantam Books',
                'year' => 1995,
                'stock' => 4,
                'description' => 'A fantasy novel about a royal bastard trained as an assassin.'
            ],
            [
                'title' => 'The Lies of Locke Lamora',
                'author' => 'Scott Lynch',
                'isbn' => '978-0-553-84137-6',
                'publisher' => 'Gollancz',
                'year' => 2006,
                'stock' => 3,
                'description' => 'A fantasy novel about a clever con artist.'
            ],
            [
                'title' => 'American Gods',
                'author' => 'Neil Gaiman',
                'isbn' => '978-0-380-97365-8',
                'publisher' => 'William Morrow',
                'year' => 2001,
                'stock' => 5,
                'description' => 'A modern fantasy novel about mythology in America.'
            ],
            [
                'title' => 'The Priory of the Orange Tree',
                'author' => 'Samantha Shannon',
                'isbn' => '978-1-5266-1009-8',
                'publisher' => 'Bloomsbury Publishing',
                'year' => 2019,
                'stock' => 2,
                'description' => 'An epic standalone fantasy novel.'
            ],
            [
                'title' => 'Red Rising',
                'author' => 'Pierce Brown',
                'isbn' => '978-0-345-53978-9',
                'publisher' => 'Del Rey',
                'year' => 2014,
                'stock' => 4,
                'description' => 'A science fiction novel about class warfare on Mars.'
            ],
            [
                'title' => 'The Night Circus',
                'author' => 'Erin Morgenstern',
                'isbn' => '978-0-385-53583-2',
                'publisher' => 'Doubleday',
                'year' => 2011,
                'stock' => 3,
                'description' => 'A magical fantasy novel about a mysterious circus.'
            ]
        ];
        $this->call(BookSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(BorrowerSeeder::class);

        // Buat data Loan test untuk siswa
        $siswaUser = \App\Models\User::where('email', 'siswa@mail.com')->first();
        $petugasUser = \App\Models\User::where('email', 'petugas@mail.com')->first();

        if ($siswaUser && $petugasUser) {
            // Loan 1 - Active (masih dipinjam)
            $loan1 = \App\Models\Loan::firstOrCreate(
                [
                    'borrower_id' => $siswaUser->id,
                    'loan_date' => now()->subDays(5),
                    'petugas_id' => $petugasUser->id,
                ],
                [
                    'due_date' => now()->addDays(2),
                    'status' => 'active'
                ]
            );

            // Loan 2 - Returned
            $loan2 = \App\Models\Loan::firstOrCreate(
                [
                    'borrower_id' => $siswaUser->id,
                    'loan_date' => now()->subDays(15),
                    'petugas_id' => $petugasUser->id,
                ],
                [
                    'due_date' => now()->subDays(8),
                    'return_date' => now()->subDays(8),
                    'status' => 'returned'
                ]
            );

            // Loan 3 - Active (baru)
            $loan3 = \App\Models\Loan::firstOrCreate(
                [
                    'borrower_id' => $siswaUser->id,
                    'loan_date' => now()->subDays(1),
                    'petugas_id' => $petugasUser->id,
                ],
                [
                    'due_date' => now()->addDays(6),
                    'status' => 'active'
                ]
            );

            // Buat LoanItems untuk masing-masing Loan
            $equipmentList = \App\Models\Equipment::limit(3)->get();

            if ($equipmentList->count() >= 3) {
                \App\Models\LoanItem::firstOrCreate(
                    ['loan_id' => $loan1->id, 'equipment_id' => $equipmentList[0]->id],
                    ['quantity' => 1]
                );

                \App\Models\LoanItem::firstOrCreate(
                    ['loan_id' => $loan2->id, 'equipment_id' => $equipmentList[1]->id],
                    ['quantity' => 1]
                );

                \App\Models\LoanItem::firstOrCreate(
                    ['loan_id' => $loan3->id, 'equipment_id' => $equipmentList[2]->id],
                    ['quantity' => 1]
                );
            }
        }
    }
}
