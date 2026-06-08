<?php

namespace Database\Seeders;

use App\Models\ArticleImage;
use Illuminate\Database\Seeder;

class ArticleImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // SEED FROM IMPORT DATABASE

        $model = new ArticleImage();
        
        $items = $model::on('mysql_import')->get();

        foreach($items as $item){

            $model::create([
                'id' => $item->id,
                'article_id' => $item->article_id,
                'path' => $item->path,
                'caption' => $item->caption,
                'source' => $item->source,
                'source_url' => $item->source_url,
                'alt_text' => $item->alt_text,
                'is_featured' => $item->is_featured,
                'sort_order' => $item->sort_order,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
            ]);

        }
    }
}
