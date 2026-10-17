# 技のロードマップAPI

`feature/beta-onboarding-profile` から作成した `feature/skill-roadmap` で、基礎の一本道とLv26〜のジャンル別の技を扱います。先行コミットにこれまでのローカル修正を保存し、続くコミットにロードマップを実装しています。

## 適用

既存環境では、データベースをバックアップして `php artisan migrate --force` を実行してください。`2026_10_17_000001_add_skill_roadmap.php` が既存50技のID・履歴・ポイントを保持し、新しいジャンルと前提条件を設定します。新規環境では通常のマイグレーションと `DatabaseSeeder` で同じカタログになります。

追加する構造は`skills.roadmap_order`、`skills.required_pair_skills`、`skill_prerequisites`、`users.target_skill_id`です。分類・前提の定義は`App\Support\SkillRoadmapCatalog`にまとめています。

| ジャンル | 技数 | 進み方 |
| --- | ---: | --- |
| 基礎 | 25 | Lv1からLv25まで順番 |
| スピン・ターン系 | 3 | 180度ターン・右スピン・左スピンを選択 |
| 片足系 | 2 | 5m→10m |
| ジャンプ系 | 5 | 3回→5回→10回・前進3m・90度ターン |
| タイヤ乗り系 | 3 | 3m→5m→10m |
| サドル操作系 | 2 | 前出し・後ろ出しを選択 |
| ペア・演技系 | 10 | 同時ホッピングは単独5回、演技はペア技2つ以上が前提 |

自由コースの`required_level`は既存データとの互換性のため保持します。表示・解放判定に自由コースの通し番号は使いません。

## 認証付きAPI

### `GET /api/skills`

既存の技・コメント情報に、本人の`is_basic`、`is_cleared`、`is_available`、`is_current`、`locked_reason`、`prerequisite_ids`、`prerequisite_names`、`success_score`を付けて配列で返します。別ユーザーの合格履歴は本人の達成状態に混ざりません。未達成の技も全件返し、閲覧できます。

### `PATCH /api/user/skill-target`

メンバー・管理者自身の目標を保存します。`{ "skill_id": 31 }`で設定し、`{ "skill_id": null }`で解除できます。未解放・達成済みの技は422、閲覧者は403です。返却形式は`{ "data": UserResource }`です。

UserResourceには`target_skill_id`、`target_skill`、`basic_cleared_count`、`advanced_cleared_count`を追加します。`remain_skills.next_skill`は、本人が今受検できる未達成の自由コースの技です。未合格の挑戦履歴があっても除外しません。

### `POST /api/challenges`

管理者専用です。`user_id`、`skill_id`、0〜3の`success_score`を必須にし、基礎の順番と自由コースの前提をサーバー側で確認します。

自由コースの初回合格時に、技の`point`を付与します。クライアントが送る`earned_point`は使用しません。ユーザー行のロックと、ポイントの一意な`source_key`で重複付与を防ぎます。過去に合格し旧方式でポイントを得た技の再挑戦でも、追加付与しません。

返却例の構造は`{ "message": "...", "challenge": ChallengeResource, "points_awarded": 2, "user": UserResource }`です。目標の技に合格すると`target_skill_id`を解除します。失敗・再挑戦では基礎レベルを下げません。

## 進捗

基礎の`required_level`と合格済みの技から、未達成の最初の基礎レベルを計算します。DBの技IDとレベルは分離しています。基礎25技達成でLv26となり、以降は自由コースの達成数・ポイントで進みます。プロフィールのレベル履歴も同じ順序で計算し、古い技の再挑戦による逆戻りを防ぎます。

## 検証

既存機能と`SkillRoadmapTest`を含む全33件・321アサーションが成功しています。カタログ、本人別の達成状態、基礎の順番、IDとレベルの分離、目標の保存・解除、分岐、独立した左右スピン、ペア条件、初回ポイント、旧ポイントの保持、再挑戦、権限制御を検証しています。

ローカルDBはバックアップ後に通常のマイグレーションのみ適用し、ユーザー、技、チャレンジ、ポイント、コメント、❤、練習会、出欠の行数が変わらないことを照合しています。ブラウザ検証用の進行データは分離DBで作成します。
