<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SkillSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $skills = [
            ['name' => 'アリーナ壁から壁まで', 'category' => '基礎', 'required_level' => 1, 'avatar_path' => 'images/skills/level-01.png', 'movie_path' => null, 'description' => '補助なし・途中降車なしで、アリーナの壁から反対側の壁まで走行する。進行方向を見て、サドルに体重を預けて小さくこぐ。', 'point' => 0],
            ['name' => 'アリーナ1周', 'category' => '基礎', 'required_level' => 2, 'avatar_path' => 'images/skills/level-02.png', 'movie_path' => null, 'description' => '補助なし・途中降車なしでアリーナを1周する。直線では一定速度を保ち、角では早めに出口を見る。', 'point' => 0],
            ['name' => 'アリーナ2周', 'category' => '基礎', 'required_level' => 3, 'avatar_path' => 'images/skills/level-03.png', 'movie_path' => null, 'description' => '補助なし・途中降車なしでアリーナを2周する。ペースを上げすぎず、同じ軌道を保つ。', 'point' => 0],
            ['name' => 'アリーナ3周', 'category' => '基礎', 'required_level' => 4, 'avatar_path' => 'images/skills/level-04.png', 'movie_path' => null, 'description' => '補助なし・途中降車なしでアリーナを3周する。呼吸と速度を一定にし、角の前で軽く減速する。', 'point' => 0],
            ['name' => '1周から逆回り1周', 'category' => '基礎', 'required_level' => 5, 'avatar_path' => 'images/skills/level-05.png', 'movie_path' => null, 'description' => '1周後に安全に方向転換し、逆回りを1周する。転換前に減速し、次の進行方向へ視線を送る。', 'point' => 0],
            ['name' => 'ジグザグコース', 'category' => '基礎', 'required_level' => 6, 'avatar_path' => 'images/skills/level-06.png', 'movie_path' => null, 'description' => 'マーカーに触れず、途中降車なしでジグザグコースを走る。次のマーカーを早めに見る。', 'point' => 0],
            ['name' => '右まわり3周', 'category' => '基礎', 'required_level' => 7, 'avatar_path' => 'images/skills/level-07.png', 'movie_path' => null, 'description' => '右回りで同じ円を3周する。少し先の進行方向を見て、一定の半径を保つ。', 'point' => 0],
            ['name' => '左まわり3周', 'category' => '基礎', 'required_level' => 8, 'avatar_path' => 'images/skills/level-08.png', 'movie_path' => null, 'description' => '左回りで同じ円を3周する。ゆっくり入り、視線と肩を円の出口へ向ける。', 'point' => 0],
            ['name' => '8の字ひこうき（大）', 'category' => '基礎', 'required_level' => 9, 'avatar_path' => 'images/skills/level-09.png', 'movie_path' => null, 'description' => '腕を広げ、大きな8の字を途中降車なしで走る。交点で次の円へ視線を切り替える。', 'point' => 0],
            ['name' => '8の字ひこうき（小）', 'category' => '基礎', 'required_level' => 10, 'avatar_path' => 'images/skills/level-10.png', 'movie_path' => null, 'description' => '腕を広げ、小さな8の字を途中降車なしで走る。速度を落とし、腰を車輪の上に保つ。', 'point' => 0],

            ['name' => '空中乗り', 'category' => '基礎', 'required_level' => 11, 'avatar_path' => 'images/skills/level-11.png', 'movie_path' => null, 'description' => '支えを使わずに乗車し、そのまま3m以上走行する。サドルを体に固定し、上体を前へ運ぶ。', 'point' => 0],
            ['name' => 'アイドリング3回', 'category' => '基礎', 'required_level' => 12, 'avatar_path' => 'images/skills/level-12.png', 'movie_path' => null, 'description' => '前→後ろ→前を1回として、アイドリングを3回連続する。車輪の往復幅を小さくする。', 'point' => 0],
            ['name' => 'バック走行5m', 'category' => '基礎', 'required_level' => 13, 'avatar_path' => 'images/skills/level-13.png', 'movie_path' => null, 'description' => '補助なし・途中降車なしで後ろ向きに5m走行する。小さく後方確認し、一定速度を保つ。', 'point' => 0],
            ['name' => 'アイドリング10回', 'category' => '基礎', 'required_level' => 14, 'avatar_path' => 'images/skills/level-14.png', 'movie_path' => null, 'description' => '補助なしでアイドリングを10回連続する。上体を起こし、一定のリズムを保つ。', 'point' => 0],
            ['name' => 'バック走行10m', 'category' => '基礎', 'required_level' => 15, 'avatar_path' => 'images/skills/level-15.png', 'movie_path' => null, 'description' => '補助なし・途中降車なしで後ろ向きに10m走行する。肩越しに進路を確認する。', 'point' => 0],
            ['name' => 'アイドリング20回', 'category' => '基礎', 'required_level' => 16, 'avatar_path' => 'images/skills/level-16.png', 'movie_path' => null, 'description' => '補助なしでアイドリングを20回連続する。往復幅とテンポを一定にする。', 'point' => 0],
            ['name' => 'バック走行20m', 'category' => '基礎', 'required_level' => 17, 'avatar_path' => 'images/skills/level-17.png', 'movie_path' => null, 'description' => '補助なし・途中降車なしで後ろ向きに20m走行する。確認動作を小さくして直進する。', 'point' => 0],
            ['name' => 'あめ玉スピン', 'category' => '基礎', 'required_level' => 18, 'avatar_path' => 'images/skills/level-18.png', 'movie_path' => null, 'description' => '小さな円を描くように回転する。視線と肩をそろえ、回転の中心を保つ。', 'point' => 0],
            ['name' => '3分間乗り続ける（ラン）', 'category' => '基礎', 'required_level' => 19, 'avatar_path' => 'images/skills/level-19.png', 'movie_path' => null, 'description' => '途中降車せず3分間走り続ける。力まず、呼吸とペースを一定に保つ。', 'point' => 0],
            ['name' => 'ホッピング1回', 'category' => '基礎', 'required_level' => 20, 'avatar_path' => 'images/skills/level-20.png', 'movie_path' => null, 'description' => 'その場でホッピングを1回成功し、着地姿勢を保つ。ペダルを水平にして小さく跳ぶ。', 'point' => 0],
            ['name' => 'アイドリング30回', 'category' => '基礎', 'required_level' => 21, 'avatar_path' => 'images/skills/level-21.png', 'movie_path' => null, 'description' => '補助なしでアイドリングを30回連続する。上半身の力を抜き、軸を保つ。', 'point' => 0],
            ['name' => 'バック走行30m', 'category' => '基礎', 'required_level' => 22, 'avatar_path' => 'images/skills/level-22.png', 'movie_path' => null, 'description' => '補助なし・途中降車なしで後ろ向きに30m走行する。速度と直線性を一定に保つ。', 'point' => 0],
            ['name' => '左右旋回と8の字', 'category' => '基礎', 'required_level' => 23, 'avatar_path' => 'images/skills/level-23.png', 'movie_path' => null, 'description' => '右回り3周→左回り3周→8の字1周を、順番どおり途中降車なしで行う。', 'point' => 0],
            ['name' => 'アイドリング50回以上', 'category' => '基礎', 'required_level' => 24, 'avatar_path' => 'images/skills/level-24.png', 'movie_path' => null, 'description' => '補助なしでアイドリングを50回以上連続する。姿勢と往復幅を一定に保つ。', 'point' => 0],
            ['name' => '横乗り', 'category' => '基礎', 'required_level' => 25, 'avatar_path' => 'images/skills/level-25.png', 'movie_path' => null, 'description' => 'クラブで定めた横乗り姿勢で3m以上進む。体と足の向きをそろえる。', 'point' => 0],

            ['name' => 'ホッピング3回', 'category' => 'ソロ中級', 'required_level' => 26, 'avatar_path' => 'images/skills/level-26.png', 'movie_path' => null, 'description' => '補助なしでホッピングを3回連続し、最後の着地を2秒保つ。高さよりリズムをそろえる。', 'point' => 2],
            ['name' => 'ホッピング5回', 'category' => 'ソロ中級', 'required_level' => 27, 'avatar_path' => 'images/skills/level-27.png', 'movie_path' => null, 'description' => '補助なしでホッピングを5回連続し、同じ場所で終了する。膝と足首を小さく使う。', 'point' => 3],
            ['name' => 'ホッピング10回', 'category' => 'ソロ中級', 'required_level' => 28, 'avatar_path' => 'images/skills/level-28.png', 'movie_path' => null, 'description' => '補助なしでホッピングを10回連続し、基準円から出ない。毎回同じ高さで跳ぶ。', 'point' => 4],
            ['name' => '前進ホッピング3m', 'category' => 'ソロ中級', 'required_level' => 29, 'avatar_path' => 'images/skills/level-29.png', 'movie_path' => null, 'description' => '途中降車なしでホッピングしながら3m前進する。真上の跳躍に少し前方向の重心移動を加える。', 'point' => 4],
            ['name' => 'ホッピング90度ターン', 'category' => 'ソロ中級', 'required_level' => 30, 'avatar_path' => 'images/skills/level-30.png', 'movie_path' => null, 'description' => 'ホッピングで90度方向転換し、着地を保つ。体と一輪車を一体にして回す。', 'point' => 4],
            ['name' => '片足走行5m', 'category' => 'ソロ中級', 'required_level' => 31, 'avatar_path' => 'images/skills/level-31.png', 'movie_path' => null, 'description' => '遊脚をフレームへ置き、途中降車なしで片足走行を5m行う。軸足で滑らかにこぐ。', 'point' => 2],
            ['name' => '片足走行10m', 'category' => 'ソロ中級', 'required_level' => 32, 'avatar_path' => 'images/skills/level-32.png', 'movie_path' => null, 'description' => '途中降車なしで片足走行を10m行い、両足走行へ戻る。軸足の踏力を一定にする。', 'point' => 2],
            ['name' => 'タイヤ乗り3m', 'category' => 'ソロ中級', 'required_level' => 33, 'avatar_path' => 'images/skills/level-33.png', 'movie_path' => null, 'description' => '足でタイヤを送り、途中降車なしで3m進む。足裏全体を使い、上体を少し後ろに保つ。', 'point' => 3],
            ['name' => 'タイヤ乗り5m', 'category' => 'ソロ中級', 'required_level' => 34, 'avatar_path' => 'images/skills/level-34.png', 'movie_path' => null, 'description' => 'タイヤ乗りで5m進み、ペダルへ戻る。タイヤを送る幅とテンポを一定にする。', 'point' => 3],
            ['name' => 'タイヤ乗り10m', 'category' => 'ソロ中級', 'required_level' => 35, 'avatar_path' => 'images/skills/level-35.png', 'movie_path' => null, 'description' => 'タイヤ乗りで10m進み、安定してペダルへ戻る。フレームの角度を一定に保つ。', 'point' => 4],
            ['name' => 'サドル前出し5m', 'category' => 'ソロ中級', 'required_level' => 36, 'avatar_path' => 'images/skills/level-36.png', 'movie_path' => null, 'description' => 'サドルを前へ出して5m走行し、サドルを戻す。腰を車軸上に保つ。', 'point' => 3],
            ['name' => 'サドル後ろ出し5m', 'category' => 'ソロ中級', 'required_level' => 37, 'avatar_path' => 'images/skills/level-37.png', 'movie_path' => null, 'description' => 'サドルを後ろへ出して5m走行し、サドルを戻す。胸を起こして腰を車輪上に置く。', 'point' => 3],
            ['name' => '180度ターン左右', 'category' => 'ソロ中級', 'required_level' => 38, 'avatar_path' => 'images/skills/level-38.png', 'movie_path' => null, 'description' => '右回り・左回りの180度ターンを各1回成功し、そのまま走行を続ける。', 'point' => 2],
            ['name' => '右スピン3周', 'category' => 'ソロ中級', 'required_level' => 39, 'avatar_path' => 'images/skills/level-39.png', 'movie_path' => null, 'description' => '決めた円内で右回りに3回転し、正面を向いて終了する。回転の中心を保つ。', 'point' => 2],
            ['name' => '左スピン3周', 'category' => 'ソロ中級', 'required_level' => 40, 'avatar_path' => 'images/skills/level-40.png', 'movie_path' => null, 'description' => '決めた円内で左回りに3回転し、正面を向いて終了する。視線と肩をそろえる。', 'point' => 2],
            ['name' => 'ペア並走1周', 'category' => 'ペア', 'required_level' => 41, 'avatar_path' => 'images/skills/level-41.png', 'movie_path' => null, 'description' => '2人で横に並び、決めた間隔を保って途中降車なしでアリーナを1周する。', 'point' => 2],
            ['name' => '手つなぎ1周', 'category' => 'ペア', 'required_level' => 42, 'avatar_path' => 'images/skills/level-42.png', 'movie_path' => null, 'description' => '2人で手をつなぎ、手に体重を預けず途中降車なしで1周する。', 'point' => 2],
            ['name' => '同時アイドリング10回', 'category' => 'ペア', 'required_level' => 43, 'avatar_path' => 'images/skills/level-43.png', 'movie_path' => null, 'description' => '2人でアイドリングを10回行い、開始・終了と往復のタイミングをそろえる。', 'point' => 2],
            ['name' => '同時バック走行5m', 'category' => 'ペア', 'required_level' => 44, 'avatar_path' => 'images/skills/level-44.png', 'movie_path' => null, 'description' => '2人で並び、決めた間隔を保って途中降車なしで後ろ向きに5m進む。', 'point' => 2],
            ['name' => 'すれ違いクロス', 'category' => 'ペア', 'required_level' => 45, 'avatar_path' => 'images/skills/level-45.png', 'movie_path' => null, 'description' => '2人が向かい合って走り、決めた側を通って接触せず中央ですれ違う。', 'point' => 2],
            ['name' => '位置交換クロス', 'category' => 'ペア', 'required_level' => 46, 'avatar_path' => 'images/skills/level-46.png', 'movie_path' => null, 'description' => '2人が接触せずに交差し、左右の位置を交換して基準位置まで走る。', 'point' => 2],
            ['name' => '同時ホッピング5回', 'category' => 'ペア', 'required_level' => 47, 'avatar_path' => 'images/skills/level-47.png', 'movie_path' => null, 'description' => '2人でホッピングを5回行い、開始・着地・終了姿勢のタイミングをそろえる。', 'point' => 3],
            ['name' => 'ペア8の字走行', 'category' => 'ペア', 'required_level' => 48, 'avatar_path' => 'images/skills/level-48.png', 'movie_path' => null, 'description' => '2人で同じ大きさの8の字を、決めた配置と間隔を保って接触せず走る。', 'point' => 2],
            ['name' => 'カノン走行', 'category' => 'ペア', 'required_level' => 49, 'avatar_path' => 'images/skills/level-49.png', 'movie_path' => null, 'description' => '2人が同じ走行を4拍ずらして行い、開始から終了まで時間差を保つ。', 'point' => 2],
            ['name' => '30秒ペア演技', 'category' => 'ペア', 'required_level' => 50, 'avatar_path' => 'images/skills/level-50.png', 'movie_path' => null, 'description' => '走り出し・2つ以上のペア技・終了姿勢を含む30〜60秒の演技を、2人でそろえて行う。', 'point' => 3],
        ];

        $skills = array_map(function (array $skill) use ($now): array {
            return array_merge($skill, [
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }, $skills);

        DB::table('skills')->upsert(
            $skills,
            ['name'],
            [
                'category',
                'required_level',
                'avatar_path',
                'movie_path',
                'description',
                'point',
                'updated_at',
            ]
        );
    }
}
