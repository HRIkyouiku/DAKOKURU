<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SimpleDataSetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $createdDatetime = '2020-12-20';
        
        DB::table('users')->where('created_at', '=', $createdDatetime)->delete();
        DB::table('names')->where('created_at', '=', $createdDatetime)->delete();
        DB::table('groups')->where('created_at', '=', $createdDatetime)->delete();
        DB::table('group_affiliations')->where('created_at', '=', $createdDatetime)->delete();
        DB::table('group_authorities')->where('created_at', '=', $createdDatetime)->delete();
        DB::table('employment_statuses')->where('created_at', '=', $createdDatetime)->delete();
        DB::table('departments')->where('created_at', '=', $createdDatetime)->delete();
        DB::table('department_affiliations')->where('created_at', '=', $createdDatetime)->delete();
        DB::table('work_places')->where('created_at', '=', $createdDatetime)->delete();
        DB::table('timestamps')->where('created_at', '=', $createdDatetime)->delete();

        DB::table('users')->insert([
            'email' => 'mail@mail.com',
            'password' => Hash::make('password'),
            'employee_no' => 99999,
            'joining_date' => '2023-04-01',
            'retirement_date' => null,
            'remember_token' => Str::random(10),
            'created_at' => $createdDatetime
        ]);
        $userId = DB::getPdo()->lastInsertId();

        DB::table('names')->insert([
            'user_id' => $userId,
            'fn_jp' => '太郎',
            'fn_jp_hira' => 'たろう',
            'fn_jp_kata' => 'タロウ',
            'fn_en' => 'Tarou',
            'ln_jp' => '山田',
            'ln_jp_hira' => 'ヤマダ',
            'ln_jp_kata' => 'タロウ',
            'ln_en' => 'Yamada',
            'oln_jp' => '田中',
            'oln_jp_hira' => 'たなか',
            'oln_jp_kata' => 'タナカ',
            'oln_en' => 'Tanaka',
            'mn_jp' => '丸楠',
            'mn_jp_hira' => 'まるくす',
            'mn_jp_kata' => 'マルクス',
            'mn_en' => 'Marukusu',
            'english_notation' => true,
            'created_at' => $createdDatetime
        ]);

        DB::table('groups')->insert(
            [
                'id' => 9999,
                'name' => 'サンプルグループ',
                'created_at' => $createdDatetime
            ]
        );

        DB::table('group_affiliations')->insert(
            [
                'user_id' => $userId,
                'group_id' => 9999,
                'created_at' => $createdDatetime
            ]
        );

        DB::table('group_authorities')->insert(
            [
                'group_id' => 9999,
                'target_group_id' => 9999,
                'authorities' => json_encode(['TEST']),
                'created_at' => $createdDatetime
            ]
        );

        DB::table('employment_statuses')->insert([
            'user_id' => $userId,
            'contract_start_date' => '2024-01-01',
            'contract_end_date' => '2025-01-01',
            'business_start_time' => '10:00:00',
            'business_end_time' => '19:00:00',
            'coretime_start_time' => '12:00:00',
            'coretime_end_time' => '15:00:00',
            'night_shift_start_time' => '22:00:00',
            'night_shift_end_time' => '05:00:00',
            'break_minute' => 60,
            'holiday_type' => 1,
            'legal_holiday' => json_encode([0]),
            'non_legal_holiday' => json_encode([6]),
            'work_deadline_date' => 30,
            'initial_calculation_day_of_week' => 0,
            'initial_calculation_month' => 12,
            'initial_calculation_day' => 31,
            'overtime_calculation_month' => 11,
            'overtime_calculation_day' => 30,
            'counted_unfilled_overtime' => true,
            'flex_type' => 0,
            'created_at' => $createdDatetime,
        ]);

        DB::table('departments')->insert(
            [
                'name_jp' => 'サンプル営業部',
                'name_en' => 'sample_sales',
                'created_at' => $createdDatetime
            ]
        );
        $departmentsId = DB::getPdo()->lastInsertId();

        DB::table('department_affiliations')->insert(
            [
                'user_id' => $userId,
                'department_id' => $departmentsId,
                'created_at' => $createdDatetime
            ]
        );

        DB::table('work_places')->insert(
            [
                'id' => 99,
                'name' => 'サンプルオフィス',
                'created_at' => $createdDatetime
            ]
        );
        $workPlaceId = DB::getPdo()->lastInsertId();

        DB::table('timestamps')->insert([
            [
                'user_id' => $userId, 'date' => '2024-01-01', 'time' => '10:00:00', 'type' => 1,
                'work_place_id' => $workPlaceId, 'remark' => '特になし', 'approved' => 0, 'created_at' => $createdDatetime
            ],
            [
                'user_id' => $userId, 'date' => '2024-01-01', 'time' => '14:00:00', 'type' => 2,
                'work_place_id' => $workPlaceId, 'remark' => '特になし', 'approved' => 0, 'created_at' => $createdDatetime
            ],
            [
                'user_id' => $userId, 'date' => '2024-01-01', 'time' => '15:00:00', 'type' => 3,
                'work_place_id' => $workPlaceId, 'remark' => '特になし', 'approved' => 0, 'created_at' => $createdDatetime
            ],
            [
                'user_id' => $userId, 'date' => '2024-01-01', 'time' => '23:00:00', 'type' => 4,
                'work_place_id' => $workPlaceId, 'remark' => '特になし', 'approved' => 0, 'created_at' => $createdDatetime
            ],
            [
                'user_id' => $userId, 'date' => '2024-01-02', 'time' => '10:30:00', 'type' => 1,
                'work_place_id' => $workPlaceId, 'remark' => '特になし', 'approved' => 0, 'created_at' => $createdDatetime
            ],
            [
                'user_id' => $userId, 'date' => '2024-01-02', 'time' => '12:00:00', 'type' => 4,
                'work_place_id' => $workPlaceId, 'remark' => '特になし', 'approved' => 0, 'created_at' => $createdDatetime
            ],
            [
                'user_id' => $userId, 'date' => '2024-01-03', 'time' => '10:30:00', 'type' => 1,
                'work_place_id' => $workPlaceId, 'remark' => '特になし', 'approved' => 0, 'created_at' => $createdDatetime
            ],
            [
                'user_id' => $userId, 'date' => '2024-01-03', 'time' => '18:00:00', 'type' => 4,
                'work_place_id' => $workPlaceId, 'remark' => '特になし', 'approved' => 0, 'created_at' => $createdDatetime
            ],
            [
                'user_id' => $userId, 'date' => '2024-01-05', 'time' => '09:00:00', 'type' => 1,
                'work_place_id' => $workPlaceId, 'remark' => '特になし', 'approved' => 0, 'created_at' => $createdDatetime
            ],
            [
                'user_id' => $userId, 'date' => '2024-01-05', 'time' => '13:00:00', 'type' => 2,
                'work_place_id' => $workPlaceId, 'remark' => '特になし', 'approved' => 0, 'created_at' => $createdDatetime
            ],
            [
                'user_id' => $userId, 'date' => '2024-01-05', 'time' => '13:30:00', 'type' => 3,
                'work_place_id' => $workPlaceId, 'remark' => '特になし', 'approved' => 0, 'created_at' => $createdDatetime
            ],
            [
                'user_id' => $userId, 'date' => '2024-01-06', 'time' => '03:00:00', 'type' => 4,
                'work_place_id' => $workPlaceId, 'remark' => '特になし', 'approved' => 0, 'created_at' => $createdDatetime
            ],
            [
                'user_id' => $userId, 'date' => '2024-01-06', 'time' => '10:00:00', 'type' => 1,
                'work_place_id' => $workPlaceId, 'remark' => '特になし', 'approved' => 0, 'created_at' => $createdDatetime
            ],
            [
                'user_id' => $userId, 'date' => '2024-01-06', 'time' => '12:00:00', 'type' => 2,
                'work_place_id' => $workPlaceId, 'remark' => '特になし', 'approved' => 0, 'created_at' => $createdDatetime
            ],
            [
                'user_id' => $userId, 'date' => '2024-01-06', 'time' => '13:00:00', 'type' => 3,
                'work_place_id' => $workPlaceId, 'remark' => '特になし', 'approved' => 0, 'created_at' => $createdDatetime
            ],
            [
                'user_id' => $userId, 'date' => '2024-01-06', 'time' => '22:30:00', 'type' => 4,
                'work_place_id' => $workPlaceId, 'remark' => '特になし', 'approved' => 0, 'created_at' => $createdDatetime
            ],
            [
                'user_id' => $userId, 'date' => '2024-01-07', 'time' => '17:00:00', 'type' => 1,
                'work_place_id' => $workPlaceId, 'remark' => '特になし', 'approved' => 0, 'created_at' => $createdDatetime
            ],
            [
                'user_id' => $userId, 'date' => '2024-01-07', 'time' => '22:30:00', 'type' => 4,
                'work_place_id' => $workPlaceId, 'remark' => '特になし', 'approved' => 0, 'created_at' => $createdDatetime
            ],
        ]);
    }
}

