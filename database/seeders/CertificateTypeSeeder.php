<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CertificateType;

class CertificateTypeSeeder extends Seeder
{
    public function run(): void
    {
        $certificates = [
            [
                'name' => 'নাগরিকত্ব সনদ',
                'fee' => 100,
                'form_fields' => [
                    ['name'=>'photo','type'=>'file','required'=>true],
                    ['name'=>'bangla_full_name','type'=>'text','required'=>true],
                    ['name'=>'father_name','type'=>'text','required'=>true],
                    ['name'=>'mother_name','type'=>'text','required'=>true],
                    ['name'=>'permanent_address','type'=>'text','required'=>true],
                    ['name'=>'present_address','type'=>'text','required'=>true],
                    ['name'=>'nid','type'=>'text','required'=>true],
                    ['name'=>'dob','type'=>'text','required'=>true],
                    ['name'=>'nid_doc','type'=>'file','required'=>true],
                ],
            ],
            [
                'name' => 'ট্রেড লাইসেন্স',
                'fee' => 200,
                'form_fields' => [
                    ['name'=>'photo','type'=>'file','required'=>true],
                    ['name'=>'bangla_name','type'=>'text','required'=>true],
                    ['name'=>'english_name','type'=>'text','required'=>true],
                    ['name'=>'father_bangla_name','type'=>'text','required'=>true],
                    ['name'=>'father_english_name','type'=>'text','required'=>true],
                    ['name'=>'mother_bangla_name','type'=>'text','required'=>true],
                    ['name'=>'mother_english_name','type'=>'text','required'=>true],
                    ['name'=>'business_name','type'=>'text','required'=>true],
                    ['name'=>'business_type','type'=>'text','required'=>true],
                    ['name'=>'business_present_address','type'=>'text','required'=>true],
                    ['name'=>'nid','type'=>'text','required'=>true],
                    ['name'=>'phone','type'=>'text','required'=>true],
                    ['name'=>'owner_address','type'=>'text','required'=>true],
                    ['name'=>'nid_doc','type'=>'file','required'=>true],
                    ['name'=>'doc1','type'=>'file','required'=>true],
                    ['name'=>'doc2','type'=>'file','required'=>false],
                    ['name'=>'doc3','type'=>'file','required'=>false],
                    ['name'=>'doc4','type'=>'file','required'=>false],
                ],
            ],
            [
                'name' => 'ওয়ারিশান সনদ',
                'fee' => 150,
                'form_fields' => [
                    ['name'=>'bangla_name','type'=>'text','required'=>true],
                    ['name'=>'father_name','type'=>'text','required'=>true],
                    ['name'=>'mother_name','type'=>'text','required'=>true],
                    ['name'=>'nid','type'=>'file','required'=>true],
                    ['name'=>'father_nid','type'=>'file','required'=>true],
                    ['name'=>'mother_nid','type'=>'file','required'=>true],
                ],
            ],
            [
                'name' => 'ভূমিহীন সনদ',
                'fee' => 150,
                'form_fields' => [
                    ['name'=>'bangla_name','type'=>'text','required'=>true],
                    ['name'=>'father_name','type'=>'text','required'=>true],
                    ['name'=>'mother_name','type'=>'text','required'=>true],
                    ['name'=>'nid','type'=>'file','required'=>true],
                    ['name'=>'father_nid','type'=>'file','required'=>true],
                    ['name'=>'mother_nid','type'=>'file','required'=>true],
                ],
            ],
            [
                'name' => 'পারিবারিক সনদ',
                'fee' => 100,
                'form_fields' => [
                    ['name'=>'photo','type'=>'file','required'=>true],
                    ['name'=>'bangla_full_name','type'=>'text','required'=>true],
                    ['name'=>'father_name','type'=>'text','required'=>true],
                    ['name'=>'mother_name','type'=>'text','required'=>true],
                    ['name'=>'permanent_address','type'=>'text','required'=>true],
                    ['name'=>'present_address','type'=>'text','required'=>true],
                    ['name'=>'nid','type'=>'text','required'=>true],
                    ['name'=>'dob','type'=>'text','required'=>true],
                    ['name'=>'nid_doc','type'=>'file','required'=>true],
                ],
            ],
            [
                'name' => 'অবিবাহিত সনদ',
                'fee' => 80,
                'form_fields' => [
                    ['name'=>'name','type'=>'text','required'=>true],
                    ['name'=>'father_name','type'=>'text','required'=>true],
                    ['name'=>'mother_name','type'=>'text','required'=>true],
                    ['name'=>'gram','type'=>'text','required'=>true],
                    ['name'=>'thana','type'=>'text','required'=>true],
                    ['name'=>'jila','type'=>'text','required'=>true],
                    ['name'=>'nid','type'=>'file','required'=>true],
                ],
            ],
            [
                'name' => 'পুনর্বিবাহ না হওয়া সনদ',
                'fee' => 90,
                'form_fields' => [
                    ['name'=>'name','type'=>'text','required'=>true],
                    ['name'=>'father_name','type'=>'text','required'=>true],
                    ['name'=>'mother_name','type'=>'text','required'=>true],
                    ['name'=>'gram','type'=>'text','required'=>true],
                    ['name'=>'thana','type'=>'text','required'=>true],
                    ['name'=>'jila','type'=>'text','required'=>true],
                    ['name'=>'nid','type'=>'file','required'=>true],
                ],
            ],
            [
                'name' => 'একই নামের প্রত্যয়ন',
                'fee' => 70,
                'form_fields' => [
                    ['name'=>'photo','type'=>'file','required'=>true],
                    ['name'=>'bangla_full_name','type'=>'text','required'=>true],
                    ['name'=>'father_name','type'=>'text','required'=>true],
                    ['name'=>'mother_name','type'=>'text','required'=>true],
                    ['name'=>'permanent_address','type'=>'text','required'=>true],
                    ['name'=>'present_address','type'=>'text','required'=>true],
                    ['name'=>'nid','type'=>'text','required'=>true],
                    ['name'=>'dob','type'=>'text','required'=>true],
                    ['name'=>'nid_doc','type'=>'file','required'=>true],
                ],
            ],
            [
                'name' => 'প্রতিবন্ধী সনদপত্র',
                'fee' => 120,
                'form_fields' => [
                    ['name'=>'name','type'=>'text','required'=>true],
                    ['name'=>'father_name','type'=>'text','required'=>true],
                    ['name'=>'mother_name','type'=>'text','required'=>true],
                    ['name'=>'protibondhir_dhoron','type'=>'text','required'=>true],
                    ['name'=>'gram','type'=>'text','required'=>true],
                    ['name'=>'thana','type'=>'text','required'=>true],
                    ['name'=>'jila','type'=>'text','required'=>true],
                    ['name'=>'nid','type'=>'file','required'=>true],
                    ['name'=>'jonmonibondhon','type'=>'file','required'=>false],
                    ['name'=>'medical_doc','type'=>'file','required'=>true],
                ],
            ],
            [
                'name' => 'অর্থনৈতিক অসচ্ছলতার সনদপত্র',
                'fee' => 150,
                'form_fields' => [
                    ['name'=>'name','type'=>'text','required'=>true],
                    ['name'=>'father_name','type'=>'text','required'=>true],
                    ['name'=>'mother_name','type'=>'text','required'=>true],
                    ['name'=>'gram','type'=>'text','required'=>true],
                    ['name'=>'thana','type'=>'text','required'=>true],
                    ['name'=>'jila','type'=>'text','required'=>true],
                    ['name'=>'nid','type'=>'file','required'=>true],
                ],
            ],
            [
                'name' => 'বিবাহিত সনদ',
                'fee' => 100,
                'form_fields' => [
                    ['name'=>'name','type'=>'text','required'=>true],
                    ['name'=>'father_name','type'=>'text','required'=>true],
                    ['name'=>'mother_name','type'=>'text','required'=>true],
                    ['name'=>'gram','type'=>'text','required'=>true],
                    ['name'=>'thana','type'=>'text','required'=>true],
                    ['name'=>'jila','type'=>'text','required'=>true],
                    ['name'=>'nid','type'=>'file','required'=>true],
                    ['name'=>'kabin_nama','type'=>'file','required'=>true],
                ],
            ],
            [
                'name' => 'দ্বিতীয় বিবাহের অনুমতি পত্র',
                'fee' => 110,
                'form_fields' => [
                    ['name'=>'name','type'=>'text','required'=>true],
                    ['name'=>'father_name','type'=>'text','required'=>true],
                    ['name'=>'mother_name','type'=>'text','required'=>true],
                    ['name'=>'gram','type'=>'text','required'=>true],
                    ['name'=>'thana','type'=>'text','required'=>true],
                    ['name'=>'jila','type'=>'text','required'=>true],
                    ['name'=>'nid','type'=>'file','required'=>true],
                ],
            ],
            [
                'name' => 'নতুন ভোটার প্রত্যয়ন',
                'fee' => 60,
                'form_fields' => [
                    ['name'=>'name','type'=>'text','required'=>true],
                    ['name'=>'father_name','type'=>'text','required'=>true],
                    ['name'=>'mother_name','type'=>'text','required'=>true],
                    ['name'=>'gram','type'=>'text','required'=>true],
                    ['name'=>'thana','type'=>'text','required'=>true],
                    ['name'=>'jila','type'=>'text','required'=>true],
                    ['name'=>'nid','type'=>'file','required'=>true],
                ],
            ],
            [
                'name' => 'জাতীয়তা সনদ',
                'fee' => 80,
                'form_fields' => [
                    ['name'=>'name','type'=>'text','required'=>true],
                    ['name'=>'father_name','type'=>'text','required'=>true],
                    ['name'=>'mother_name','type'=>'text','required'=>true],
                    ['name'=>'gram','type'=>'text','required'=>true],
                    ['name'=>'thana','type'=>'text','required'=>true],
                    ['name'=>'jila','type'=>'text','required'=>true],
                    ['name'=>'nid','type'=>'file','required'=>true],
                ],
            ],
            [
                'name' => 'এতিম সনদ',
                'fee' => 90,
                'form_fields' => [
                    ['name'=>'name','type'=>'text','required'=>true],
                    ['name'=>'father_name','type'=>'text','required'=>true],
                    ['name'=>'mother_name','type'=>'text','required'=>true],
                    ['name'=>'gram','type'=>'text','required'=>true],
                    ['name'=>'thana','type'=>'text','required'=>true],
                    ['name'=>'jila','type'=>'text','required'=>true],
                    ['name'=>'nid','type'=>'file','required'=>true],
                ],
            ],
            [
                'name' => 'মাসিক আয়ের সনদ',
                'fee' => 100,
                'form_fields' => [
                    ['name'=>'name','type'=>'text','required'=>true],
                    ['name'=>'father_name','type'=>'text','required'=>true],
                    ['name'=>'mother_name','type'=>'text','required'=>true],
                    ['name'=>'gram','type'=>'text','required'=>true],
                    ['name'=>'thana','type'=>'text','required'=>true],
                    ['name'=>'jila','type'=>'text','required'=>true],
                    ['name'=>'nid','type'=>'file','required'=>true],
                ],
            ],
        ];

        foreach($certificates as $cert){
            CertificateType::create([
                'name' => $cert['name'],
                'fee' => $cert['fee'],
                'form_fields' => $cert['form_fields'],
            ]);
        }
    }
}
