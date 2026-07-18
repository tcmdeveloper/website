<?php

namespace Database\Seeders;

use App\Models\DocumentPage;
use Illuminate\Database\Seeder;

class DocumentPageSeeder extends Seeder
{
    public function run(): void
    {
        // SEED FROM IMPORT DATABASE

        $model = new DocumentPage();
        
        $items = $model::on('mysql_import')->get();

        foreach($items as $item){

            $model::create([
                'id' => $item->id,
                'hex' => $item->hex,
                'user_id' => $item->user_id,
                'document_id' => $item->document_id,
                'page_number' => $item->page_number,
                'image_path' => $item->image_path,
                'width' => $item->width,
                'height' => $item->height,
                'is_optimized' => $item->is_optimized,
                'views' => $item->views,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at
            ]);
        }
    }
}
