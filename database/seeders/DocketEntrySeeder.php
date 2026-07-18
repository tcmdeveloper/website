<?php

namespace Database\Seeders;

use App\Models\DocketEntry;
use Illuminate\Database\Seeder;

class DocketEntrySeeder extends Seeder
{
    public function run(): void
    {
        // SEED FROM IMPORT DATABASE

        $model = new DocketEntry();
    
        $items = $model::on('mysql_import')->get();

        foreach($items as $item){

            $model::create([
                'id' => $item->id,
                'hex' => $item->hex,
                'criminal_case_id' => $item->criminal_case_id,
                'sequence_number' => $item->sequence_number,
                'docket_code' => $item->docket_code,
                'filed_at' => $item->filed_at,
                'title' => $item->title,
                'has_document' => $item->has_document,
                'input_document_id' => $item->input_document_id,
                'document_title' => $item->document_title,
                'viewer_qs' => $item->viewer_qs,
                'viewer_parameters' => $item->viewer_parameters,
                'mobile_viewer_parameters' => $item->mobile_viewer_parameters,
                'encoded_document_path' => $item->encoded_document_path,
                'page_count' => $item->page_count,
                'raw_data' => $item->raw_data,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,

            ]);
        }
    }
}
