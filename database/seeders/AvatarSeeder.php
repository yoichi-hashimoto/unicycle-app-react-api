<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UserAvatar;

class AvatarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserAvatar::insert([[
            'id' => 1,
            'avatar_path'=> './images/users/avatar_01_r1_c1.webp'
        ],[
            'id'=>2,
            'avatar_path' =>'./images/users/avatar_02_r1_c2.webp'
        ],[
                        'id' => 3,
            'avatar_path'=> './images/users/avatar_03_r1_c3.webp'
        ],[
                        'id' => 4,
            'avatar_path'=> './images/users/avatar_04_r1_c4.webp'

        ],[
                        'id' => 5,
            'avatar_path'=> './images/users/avatar_05_r1_c5.webp'

        ],[
                        'id' => 6,
            'avatar_path'=> './images/users/avatar_06_r1_c6.webp'

        ],[
                        'id' => 7,
            'avatar_path'=> './images/users/avatar_07_r1_c7.webp'
        ],[
                        'id' => 8,
            'avatar_path'=> './images/users/avatar_08_r1_c8.webp'

        ],[
                        'id' => 9,
            'avatar_path'=> './images/users/avatar_09_r1_c9.webp'

        ],[
            'id' => 10,
            'avatar_path'=> './images/users/avatar_10_r1_c10.webp'
        ],[
            'id'=>11,
            'avatar_path' =>'./images/users/avatar_11_r2_c1.webp'
        ],[
            'id'=>12,
            'avatar_path' =>'./images/users/avatar_12_r2_c2.webp'
        ],[
            'id'=>13,
            'avatar_path' =>'./images/users/avatar_13_r2_c3.webp'
        ],[
            'id'=>14,
            'avatar_path' =>'./images/users/avatar_14_r2_c4.webp'
        ],[
            'id'=>15,
            'avatar_path' =>'./images/users/avatar_15_r2_c5.webp'
        ],[
            'id'=>16,
            'avatar_path' =>'./images/users/avatar_16_r2_c6.webp'
        ],[
            'id'=>17,
            'avatar_path' =>'./images/users/avatar_17_r2_c7.webp'
        ],[
            'id'=>18,
            'avatar_path' =>'./images/users/avatar_18_r2_c8.webp'
        ],[
            'id'=>19,
            'avatar_path' =>'./images/users/avatar_19_r2_c9.webp'
        ],[
            'id'=>20,
            'avatar_path' =>'./images/users/avatar_20_r2_c10.webp'
        ],[
            'id'=>21,
            'avatar_path' =>'./images/users/avatar_21_r3_c1.webp'
        ],[
            'id'=>22,
            'avatar_path' =>'./images/users/avatar_22_r3_c2.webp'
        ],[
            'id'=>23,
            'avatar_path' =>'./images/users/avatar_23_r3_c3.webp'
        ],[
            'id'=>24,
            'avatar_path' =>'./images/users/avatar_24_r3_c4.webp'
        ],[
            'id'=>25,
            'avatar_path' =>'./images/users/avatar_25_r3_c5.webp'
        ],[
            'id'=>26,
            'avatar_path' =>'./images/users/avatar_26_r3_c6.webp'
        ],[
            'id'=>27,
            'avatar_path' =>'./images/users/avatar_27_r3_c7.webp'
        ],[
            'id'=>28,
            'avatar_path' =>'./images/users/avatar_28_r3_c8.webp'
        ],[
            'id'=>29,
            'avatar_path' =>'./images/users/avatar_29_r3_c9.webp'
        ],[
            'id'=>30,
            'avatar_path' =>'./images/users/avatar_30_r3_c10.webp'
        ],[
                        'id'=>31,
            'avatar_path' =>'./images/users/avatar_31_r4_c1.webp'
        ],[
                        'id'=>32,
            'avatar_path' =>'./images/users/avatar_32_r4_c2.webp'
        ],[
                        'id'=>33,
            'avatar_path' =>'./images/users/avatar_33_r4_c3.webp'
        ],[
                        'id'=>34,
            'avatar_path' =>'./images/users/avatar_34_r4_c4.webp'
        ],[
                        'id'=>35,
            'avatar_path' =>'./images/users/avatar_35_r4_c5.webp'
        ],[
                        'id'=>36,
            'avatar_path' =>'./images/users/avatar_36_r4_c6.webp'
        ],[
                        'id'=>37,
            'avatar_path' =>'./images/users/avatar_37_r4_c7.webp'
        ],[
                        'id'=>38,
            'avatar_path' =>'./images/users/avatar_38_r4_c8.webp'
        ],[
                        'id'=>39,
            'avatar_path' =>'./images/users/avatar_39_r4_c9.webp'
        ],[
                        'id'=>40,
            'avatar_path' =>'./images/users/avatar_40_r4_c10.webp'
        ],[
                        'id'=>41,
            'avatar_path' =>'./images/users/avatar_41_r5_c1.webp'
        ],[
                        'id'=>42,
            'avatar_path' =>'./images/users/avatar_42_r5_c2.webp'
        ],[
                        'id'=>43,
            'avatar_path' =>'./images/users/avatar_43_r5_c3.webp'
        ],[
                        'id'=>44,
            'avatar_path' =>'./images/users/avatar_44_r5_c4.webp'
        ],[
                        'id'=>45,
            'avatar_path' =>'./images/users/avatar_45_r5_c5.webp'
        ],[
                        'id'=>46,
            'avatar_path' =>'./images/users/avatar_46_r5_c6.webp'
        ],[
                        'id'=>47,
            'avatar_path' =>'./images/users/avatar_47_r5_c7.webp'
        ],[
                        'id'=>48,
            'avatar_path' =>'./images/users/avatar_48_r5_c8.webp'
        ],[
                        'id'=>49,
            'avatar_path' =>'./images/users/avatar_49_r5_c9.webp'
        ],[
                        'id'=>50,
            'avatar_path' =>'./images/users/avatar_50_r5_c10.webp'
        ]]);
    }
}
