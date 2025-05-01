<?php

namespace Database\Seeders;

use App\Models\CustomerSMA;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class CustomerSynchronizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        $customers = DB::connection('old_db') // assuming customertbl is in another db
        ->table('customertbl')
            ->get();

        foreach ($customers as $customer) {
            $originalName = $customer->cus_name ?? 'Customer';
            $uniqueName = $originalName;

            // Ensure unique name
            $suffixTries = 0;
            while (DB::table((new CustomerSMA())->getTable())->where('name', $uniqueName)->exists()) {
                $suffix = '_' . rand(1000, 9999);
                $uniqueName = $originalName . $suffix;
                $suffixTries++;
                if ($suffixTries > 10) break; // prevent infinite loops
            }

            // Get created_at from audittrail_head_created
            $created = DB::connection('old_db')
                ->table('audittrail_head_created')
                ->where('formstatus', 'Customer')
                ->where('createditemid', $customer->id)
                ->orderByDesc('createdtime')
                ->value('createdtime');

            DB::table((new CustomerSMA())->getTable())->insert([
                'old_db_id' => $customer->id,
                'group_id' => 3,
                'group_name' => 'customer',
                'customer_group_id' => 1,
                'customer_group_name' => 'General',
                'name' => $uniqueName,
                'company' => $uniqueName,
                'address' => $customer->address ?? '',
                'city' => '',
                'phone' => $customer->contactno ?? '',
                'email' => $customer->email ?? '',
                'created_at' => $created ?? Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
