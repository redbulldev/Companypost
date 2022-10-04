<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Companypost\Models\Post;
use function GuzzleHttp\json_decode;
use Faker\Factory;
use Illuminate\Support\Facades\DB;
use Faker\Provider\Internet;
use Faker\Provider\Image;

class PostTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();

        for ($i=0 ;$i<50 ; $i++){
            DB::table('posts')->insert(
                [
                    'title'        => $faker->catchPhrase,
                    'photo'        => "/images/" . strval($faker->numberBetween(1,10)) .".jpg", 
                    'photo_name'   => $faker->name,
                    'quote'        => $faker->text,
                    'content'      => $faker->text,
                    'status'       => 'active',
                    'tag_ig'       => rand(1, 4),
                    'slug'         => $faker->slug,
                    'user_id'      => $faker->numberBetween(1,2),
                    'is_featured'  => $faker->numberBetween(0,1),
                    'created_at'   => date("Y-m-d"),
                ]
            );
        }
    }
}
