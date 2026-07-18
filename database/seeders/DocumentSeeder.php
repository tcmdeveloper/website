<?php

namespace Database\Seeders;

use App\Models\Document;
use Illuminate\Database\Seeder;

class DocumentSeeder extends Seeder
{
    public function run(): void
    {
        // SEED FROM IMPORT DATABASE

        $model = new Document();
        
        $items = $model::on('mysql_import')->get();

        foreach($items as $item){

            $model::create([
                'id' => $item->id,
                'hex' => $item->hex,
                'name' => $item->name,
                'slug' => $item->slug,
                'description' => $item->description,
                'pdf_path' => $item->pdf_path,
                'pages' => $item->pages,
                'filesize' => $item->filesize,
                'meta_title' => $item->meta_title,
                'meta_description' => $item->meta_description,
                'og_image' => $item->og_image,
                'views' => $item->views,
                'user_id' => $item->user_id,
                'category_id' => $item->category_id,
                'criminal_case_id' => $item->criminal_case_id,
                'docket_entry_id' => $item->docket_entry_id,
                'published_at' => $item->published_at,
                'is_published' => $item->is_published,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at
            ]);
        }
    }
}
