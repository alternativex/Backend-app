<?php

class DatabaseSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        Eloquent::unguard();

        $this->call('UserTableSeeder');
        $this->call('RoyaltyProviderTableSeeder');
        $this->call('RoyaltyShareTableSeeder');
        $this->call('RoyaltyTypeTableSeeder');

        $this->command->info('Database seeded successfully.');
    }

}

class UserTableSeeder extends Seeder {
    public function run() {
        DB::table('users')->truncate();

        User::create([
            'email' => 'bogdan.luca@eloquentix.com',
            'password' => 'test',
            'type' => 'admin',
            'name' => 'Bogdan Luca - Admin',
        ]);

        User::create([
            'email' => 'luca.bogdan@gmail.com',
            'password' => 'test',
            'type' => 'publisher',
            'name' => 'Bogdan Luca - Publisher',
        ]);
    }
}

class RoyaltyProviderTableSeeder extends Seeder {
    public function run() {
        DB::table('royalty_providers')->truncate();

        RoyaltyProvider::create([
            'id' => '2',
            'name' => 'BMI',
            'etl_upload_location' => '/var/home/royalty/pentaho/TheRoyaltyExchange/ETL-PUB/Files/BMIPublisher',
            'etl_command' => '/var/home/royalty/pentaho/design-tools/data-integration/kitchen-wrapper-bmi-pub.sh> /dev/null 2>/dev/null &',
        ]);

        RoyaltyProvider::create([
            'id' => '3',
            'name' => 'ASCAP - International',
            'etl_upload_location' => '/var/home/royalty/pentaho/TheRoyaltyExchange/ETL-PUB/Files/ASCAP/International',
            'etl_command' => '/var/home/royalty/pentaho/design-tools/data-integration/kitchen-wrapper-ascap-international-pub.sh> /dev/null 2>/dev/null &',
        ]);

        RoyaltyProvider::create([
            'id' => '48',
            'name' => 'SESAC',
            'etl_upload_location' => '/var/home/royalty/pentaho/TheRoyaltyExchange/ETL-PUB/Files/SESAC',
            'etl_command' => '/var/home/royalty/pentaho/design-tools/data-integration/kitchen-wrapper-sesac-pub.sh> /dev/null 2>/dev/null &',
        ]);

        RoyaltyProvider::create([
            'id' => '50',
            'name' => 'ASCAP - Domestic',
            'etl_upload_location' => '/var/home/royalty/pentaho/TheRoyaltyExchange/ETL-PUB/Files/ASCAP/Domestic',
            'etl_command' => '/var/home/royalty/pentaho/design-tools/data-integration/kitchen-wrapper-ascap-domestic-pub.sh> /dev/null 2>/dev/null &',
        ]);

        RoyaltyProvider::create([
            'id' => '54',
            'name' => 'The Orchard',
            'etl_upload_location' => '/var/home/royalty/pentaho/TheRoyaltyExchange/ETL-PUB/Files/Orchard',
            'etl_command' => '/var/home/royalty/pentaho/design-tools/data-integration/kitchen-wrapper-orchard-pub.sh> /dev/null 2>/dev/null &',
        ]);
    }
}

class RoyaltyShareTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('royalty_types')->truncate();

        RoyaltyShare::create([
            'royalty_share_name' => 'Publisher\'s Share',
            'royalty_share_name_short' => 'Publisher',
            'royalty_share_name_symbol' => 'PUB',
        ]);

        RoyaltyShare::create([
            'royalty_share_name' => 'Writer\'s Share',
            'royalty_share_name_short' => 'Writer',
            'royalty_share_name_symbol' => 'WRI',
        ]);

        RoyaltyShare::create([
            'royalty_share_name' => 'Artist\'s Share',
            'royalty_share_name_short' => 'Artist',
            'royalty_share_name_symbol' => 'ART',
        ]);

        RoyaltyShare::create([
            'royalty_share_name' => 'Producer\'s Share',
            'royalty_share_name_short' => 'Producer',
            'royalty_share_name_symbol' => 'PROD',
        ]);
    }
}

class RoyaltyTypeTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('royalty_types')->truncate();

        foreach (['Mechanical', 'Performance', 'Synchronization', 'Print', 'Digital', 'Audio Home Recording', 'Youtube', 'Google'] as $name) {
            RoyaltyType::create([
                'royalty_type_name' => $name,
            ]);
        }
    }
}
