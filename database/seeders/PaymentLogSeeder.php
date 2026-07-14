<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentLogSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('payment_logs')->insert([
            ['id' => 1, 'commission_id' => 3, 'order_id' => '308312', 'transaction_status' => 'paid', 'payload' => '{"id":308312,"user_id":100015584,"amount":615000,"status":"DONE","reason":"","timestamp":"2026-06-19 21:14:50","bank_code":"bri","account_number":"08762556123","recipient_name":"Dummy Name","sender_bank":null,"remark":"Komisi 2026-06","receipt":"https://flip-receipt.oss-ap-southeast-5.aliyuncs.com/debit_receipt/","time_served":"2026-06-19 21:15:01","bundle_id":0,"company_id":95401,"recipient_city":391,"created_from":"API","direction":"DOMESTIC_TRANSFER","sender":null,"fee":1998,"beneficiary_email":"rizki@gmail.com","idempotency_key":"commission-3-2026-06-615000.00-1781878489","is_virtual_account":false}', 'created_at' => '2026-06-19 14:15:02', 'updated_at' => '2026-06-19 14:15:02'],
            ['id' => 2, 'commission_id' => 5, 'order_id' => '309939', 'transaction_status' => 'paid', 'payload' => '{"id":309939,"user_id":100015583,"amount":110000,"status":"DONE","reason":"","timestamp":"2026-06-25 22:13:23","bank_code":"kalimantan_tengah","account_number":"81929791297","recipient_name":"","sender_bank":null,"remark":"Komisi 2026-06","receipt":"https://flip-receipt.oss-ap-southeast-5.aliyuncs.com/debit_receipt/","time_served":"2026-06-25 22:31:57","bundle_id":0,"company_id":95400,"recipient_city":391,"created_from":"API","direction":"DOMESTIC_TRANSFER","sender":null,"fee":1998,"beneficiary_email":"suroso@gmail.com","idempotency_key":"commission-5-2026-06-110000.00-1782400400","is_virtual_account":false}', 'created_at' => '2026-06-25 15:31:58', 'updated_at' => '2026-06-25 15:31:58']
        ]);
    }
}
