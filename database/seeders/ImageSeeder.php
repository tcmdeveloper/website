<?php

namespace Database\Seeders;

use App\Models\Image;
use Illuminate\Database\Seeder;

class ImageSeeder extends Seeder
{
    public function run(): void
    {
        // SEED FROM IMPORT DATABASE

        $model = new Image();
        
        $items = $model::on('mysql_import')->get();

        foreach($items as $item){

            $model::create([
                'id' => $item->id,
                'hex' => $item->hex,
                'imageable_type' => $item->imageable_type,
                'imageable_id' => $item->imageable_id,
                'image_path' => $item->image_path,
                'alt_text' => $item->alt_text,
                'caption' => $item->caption,
                'credit_name' => $item->credit_name,
                'credit_url' => $item->credit_url,
                'is_featured' => $item->is_featured,
                'has_multiformat' => $item->has_multiformat,
                'sort_order' => $item->sort_order,
                'user_id' => $item->user_id,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
            ]);
        }
    }
}
