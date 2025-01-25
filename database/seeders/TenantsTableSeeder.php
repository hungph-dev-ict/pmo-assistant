<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TenantsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tenants')->insert([
            [
                'name' => 'Apple Inc.',
                'description' => 'Apple Inc. is an American multinational technology company that specializes in consumer electronics, computer software, and online services.',
                'head_user_id' => 2, // Bạn có thể thay thế bằng ID của user head nếu có
                'plan_id' => 1, // Thay thế bằng ID của kế hoạch nếu có
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'name' => 'Microsoft Corporation',
                'description' => 'Microsoft Corporation is an American multinational technology company that develops, manufactures, licenses, supports, and sells computer software, consumer electronics, and personal computers.',
                'head_user_id' => 2,
                'plan_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'name' => 'Tesla, Inc.',
                'description' => 'Tesla, Inc. is an American electric vehicle and clean energy company headquartered in Palo Alto, California.',
                'head_user_id' => 2,
                'plan_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
        ]);
    }
}
