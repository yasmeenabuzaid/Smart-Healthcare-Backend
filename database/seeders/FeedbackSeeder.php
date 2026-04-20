<?php

namespace Database\Seeders;

use Illuminate\Support\Arr;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FeedbackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userIds = DB::table('users')->pluck('id');
        $hospitalIds = DB::table('hospitals')->pluck('id');
        $departmentIds = DB::table('departments')->pluck('id');

        $scopes = ['system', 'hospital', 'department'];
        $types = ['complaint', 'suggestion', 'inquiry'];
        $statuses = ['pending', 'in_progress', 'resolved'];

        $messages = [
            'الخدمة بطيئة جداً',
            'التطبيق ممتاز لكن يحتاج تحسين',
            'لم يتم الرد على طلبي',
            'تجربة جيدة بشكل عام',
            'يوجد ازدحام كبير في المستشفى',
            'النظام يحتاج تطوير في حجز المواعيد',
            'طاقم طبي ممتاز',
            'انتظار طويل في الدور',
            'اقتراح إضافة إشعارات أفضل',
            'المواعيد غير واضحة',
        ];

        for ($i = 0; $i < 50; $i++) {

            $scope = Arr::random($scopes);

            $hospitalId = null;
            $departmentId = null;

            if ($scope === 'hospital') {
                $hospitalId = $hospitalIds->random();
            }

            if ($scope === 'department') {
                $hospitalId = $hospitalIds->random();
                $departmentId = $departmentIds->random();
            }

            DB::table('feedback')->insert([
                'user_id' => $userIds->random(),
                'scope' => $scope,

                'hospital_id' => $hospitalId,
                'department_id' => $departmentId,

                'type' => Arr::random($types),
                'message' => Arr::random($messages),

                'status' => Arr::random($statuses),

                'admin_reply' => rand(0, 1)
                    ? 'تمت معالجة الطلب بنجاح'
                    : null,

                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }    
    }
}
