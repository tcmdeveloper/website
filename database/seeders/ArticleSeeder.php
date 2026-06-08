<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        // SEED FROM IMPORT DATABASE

        $model = new Article();
        
        $items = $model::on('mysql_import')->get();

        foreach($items as $item){

            $model::create([
                'id' => $item->id,
                'hex' => $item->hex,
                'title' => $item->title,
                'slug' => $item->slug,
                'excerpt' => $item->excerpt,
                'content' => $item->content,
                'user_id' => $item->user_id,
                'category_id' => $item->category_id,
                'published_at' => $item->published_at,
                'is_published' => $item->is_published,
                'meta_title' => $item->meta_title,
                'meta_description' => $item->meta_description,
                'views' => $item->views,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at

            ]);

        }


    }


}
