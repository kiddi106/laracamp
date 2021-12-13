<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        

        // Database Seeder
        // User::create([
        //     'name'=>'Dicky Kurniawan',
        //     'email'=>'kiddi106@yahoo.com',
        //     'password'=>bcrypt('123')
        // ]);
        User::factory(3)->create();

        Category::create([
            'name'=>'Programming',
            'slug'=>'programming'
        ]);
        Category::create([
            'name'=>'Web Design',
            'slug'=>'web-design'
        ]);
        Category::create([
            'name'=>'Personal',
            'slug'=>'personal'
        ]);
        

        Post::factory(10)->create();

        // Post::create([
        //     'title'=>'Doraemon',
        //     'slug'=>'doraemon-1997',
        //     'excerpt'=>'Porro minus natus consequatur dignissimos eum neque mollitia tempora.',
        //     'body'=>'<p>Porro minus natus consequatur dignissimos eum neque mollitia tempora. </p><p> maiores fuga itaque velit dolorum, necessitatibus cum aut quo nam distinctio animi temporibus ab vel iste quae iusto eaque pariatur quas alias aliquid explicabo at voluptas delectus? Ipsa eligendi culpa eos, nemo minima laborum! Dolor voluptatem ducimus molestiae laboriosam nam quia labore error optio reiciendis nobis. Rerum reiciendis magni, omnis ducimus, laborum quam, molestiae tempore facilis iste quidem fuga itaque ipsum atque rem numquam distinctio libero provident asperiores eaque blanditiis veniam tenetur. Illum repudiandae, iusto corporis neque blanditiis numquam nobis est reiciendis.</p>',
        //     'category_id'=>2,
        //     'user_id'=>1

        // ]);
        // Post::create([
        //     'title'=>'Dragon-Ball',
        //     'slug'=>'dragon-ball-1995',
        //     'excerpt'=>'Porro minus natus consequatur dignissimos eum neque mollitia tempora.',
        //     'body'=>'<p>Porro minus natus consequatur dignissimos eum neque mollitia tempora. </p><p> maiores fuga itaque velit dolorum, necessitatibus cum aut quo nam distinctio animi temporibus ab vel iste quae iusto eaque pariatur quas alias aliquid explicabo at voluptas delectus? Ipsa eligendi culpa eos, nemo minima laborum! Dolor voluptatem ducimus molestiae laboriosam nam quia labore error optio reiciendis nobis. Rerum reiciendis magni, omnis ducimus, laborum quam, molestiae tempore facilis iste quidem fuga itaque ipsum atque rem numquam distinctio libero provident asperiores eaque blanditiis veniam tenetur. Illum repudiandae, iusto corporis neque blanditiis numquam nobis est reiciendis.</p>',
        //     'category_id'=>1,
        //     'user_id'=>1

        // ]);
        // Post::create([
        //     'title'=>'Gintama',
        //     'slug'=>'gintama-1997',
        //     'excerpt'=>'Porro minus natus consequatur dignissimos eum neque mollitia tempora.',
        //     'body'=>'<p>Porro minus natus consequatur dignissimos eum neque mollitia tempora. </p><p> maiores fuga itaque velit dolorum, necessitatibus cum aut quo nam distinctio animi temporibus ab vel iste quae iusto eaque pariatur quas alias aliquid explicabo at voluptas delectus? Ipsa eligendi culpa eos, nemo minima laborum! Dolor voluptatem ducimus molestiae laboriosam nam quia labore error optio reiciendis nobis. Rerum reiciendis magni, omnis ducimus, laborum quam, molestiae tempore facilis iste quidem fuga itaque ipsum atque rem numquam distinctio libero provident asperiores eaque blanditiis veniam tenetur. Illum repudiandae, iusto corporis neque blanditiis numquam nobis est reiciendis.</p>',
        //     'category_id'=>1,
        //     'user_id'=>1

        // ]);
        // Post::create([
        //     'title'=>'Bleach',
        //     'slug'=>'bleach-1992',
        //     'excerpt'=>'Porro minus natus consequatur dignissimos eum neque mollitia tempora.',
        //     'body'=>'<p>Porro minus natus consequatur dignissimos eum neque mollitia tempora. </p><p> maiores fuga itaque velit dolorum, necessitatibus cum aut quo nam distinctio animi temporibus ab vel iste quae iusto eaque pariatur quas alias aliquid explicabo at voluptas delectus? Ipsa eligendi culpa eos, nemo minima laborum! Dolor voluptatem ducimus molestiae laboriosam nam quia labore error optio reiciendis nobis. Rerum reiciendis magni, omnis ducimus, laborum quam, molestiae tempore facilis iste quidem fuga itaque ipsum atque rem numquam distinctio libero provident asperiores eaque blanditiis veniam tenetur. Illum repudiandae, iusto corporis neque blanditiis numquam nobis est reiciendis.</p>',
        //     'category_id'=>2,
        //     'user_id'=>1

        // ]);
        
    }
}
