<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\PricingPackage;
use App\Models\Transaction;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        $starterPkg = PricingPackage::where('name', 'Starter')->first();
        $proPkg = PricingPackage::where('name', 'Professional')->first();
        $bizPkg = PricingPackage::where('name', 'Business')->first();

        $userStarter = User::where('email', 'starter@scanyuk.com')->first();
        $userPro = User::where('email', 'pro@scanyuk.com')->first();
        $userBiz = User::where('email', 'business@scanyuk.com')->first();

        $data = [
            [
                'id' => 'TXN001',
                'user_id' => $userPro->id,
                'pricing_package_id' => $proPkg->id,
                'amount' => $proPkg->price,
                'status' => 'Success',
                'created_at' => now()->subDays(4)
            ],
            [
                'id' => 'TXN002',
                'user_id' => $userStarter->id,
                'pricing_package_id' => $starterPkg->id,
                'amount' => $starterPkg->price,
                'status' => 'Success',
                'created_at' => now()->subDays(5)
            ],
            [
                'id' => 'TXN003',
                'user_id' => $userBiz->id,
                'pricing_package_id' => $bizPkg->id,
                'amount' => $bizPkg->price,
                'status' => 'Success',
                'created_at' => now()->subDays(9)
            ],
            [
                'id' => 'TXN004',
                'user_id' => $userStarter->id,
                'pricing_package_id' => $starterPkg->id,
                'amount' => $starterPkg->price,
                'status' => 'Pending',
                'created_at' => now()->subDay()
            ],
        ];

        foreach ($data as $item) {
            Transaction::updateOrCreate(['id' => $item['id']], $item);
        }
    }
}