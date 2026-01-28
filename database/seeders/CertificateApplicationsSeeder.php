<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CertificateApplication;
use App\Models\User;
use App\Models\CertificateType;

class CertificateApplicationsSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();
        $certificateTypes = CertificateType::all();
        
        if ($users->isEmpty() || $certificateTypes->isEmpty()) {
            $this->command->info('No users or certificate types found. Skipping.');
            return;
        }
        
        // কিছু sample applications তৈরি করুন
        $applications = [
            [
                'user_id' => $users->first()->id,
                'certificate_id' => $certificateTypes->first()->id,
                'application_no' => 'APP-' . date('Ymd') . '-0001',
                'form_data' => [
                    'name_bangla' => 'আবদুল করিম',
                    'name_english' => 'Abdul Karim',
                    'nid_number' => '1980123456789',
                    'father_name' => 'আবদুল রহিম',
                    'mother_name' => 'আয়েশা বেগম',
                    'date_of_birth' => '1980-05-15',
                    'address' => 'মিরপুর, ঢাকা'
                ],
                'fee' => 100.00,
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'remarks' => 'নতুন আবেদন'
            ],
            [
                'user_id' => $users->first()->id,
                'certificate_id' => $certificateTypes->first()->id,
                'application_no' => 'APP-' . date('Ymd') . '-0002',
                'form_data' => [
                    'name_bangla' => 'ফাতেমা খাতুন',
                    'name_english' => 'Fatema Khatun',
                    'nid_number' => '1990123456789',
                    'father_name' => 'মোহাম্মদ আলী',
                    'mother_name' => 'রুবিনা বেগম',
                    'date_of_birth' => '1990-08-20',
                    'address' => 'উত্তরা, ঢাকা'
                ],
                'fee' => 100.00,
                'status' => 'approved',
                'payment_status' => 'paid',
                'certificate_number' => 'CERT-' . date('Y') . '-0001',
                'approved_at' => now(),
                'approved_by' => $users->first()->id,
                'remarks' => 'অনুমোদিত আবেদন'
            ],
            [
                'user_id' => $users->first()->id,
                'certificate_id' => $certificateTypes->first()->id,
                'application_no' => 'APP-' . date('Ymd') . '-0003',
                'form_data' => [
                    'name_bangla' => 'রহিম উদ্দিন',
                    'name_english' => 'Rahim Uddin',
                    'nid_number' => '1975123456789',
                    'father_name' => 'করিম উদ্দিন',
                    'mother_name' => 'সালমা বেগম',
                    'date_of_birth' => '1975-12-10',
                    'address' => 'গুলশান, ঢাকা'
                ],
                'fee' => 100.00,
                'status' => 'rejected',
                'payment_status' => 'unpaid',
                'rejected_at' => now(),
                'rejected_by' => $users->first()->id,
                'rejection_reason' => 'অসম্পূর্ণ তথ্য',
                'remarks' => 'বাতিল আবেদন'
            ]
        ];
        
        foreach ($applications as $appData) {
            CertificateApplication::create($appData);
        }
        
        $this->command->info('Created ' . count($applications) . ' sample applications.');
    }
}