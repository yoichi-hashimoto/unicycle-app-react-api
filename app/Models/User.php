<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserRole;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\UserAvatar;
use App\Models\Animal;
use App\Models\UserItem;
use App\Models\Challenge;
use App\Models\Skill;
use App\Models\SkillTip;
use App\Models\Point;

#[Fillable([
    'name',
    'login_id',
    'password',
    'user_avatar_id',
    'color_id',
    'role',
    'beginner_mode',
    'beginner_step',
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'name' => 'string',
            'color_id' => 'integer',
            'login_id'=>'string',
            'user_avatar_id' =>'integer',
            'password' => 'hashed',
            'role' => UserRole::class,
            'beginner_mode' => 'boolean',
            'beginner_step' => 'integer',
            'equipped_item_id'=>'boolean',
            'last_seen_animal_id' =>'integer',
        ];
    }

    public function challenges()
    {
        return $this->hasMany(Challenge::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRole::Admin;
    }

    public function isGuardian(): bool
    {
        return $this->role === UserRole::Gurdian;
    }

    public function hasMemberProfile(): bool
    {
        return ! $this->isGuardian();
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function colors(){
        return $this->hasMany(Color::class);
    }

    public function avatar(){
        return $this->belongsTo(UserAvatar::class,'user_avatar_id');
    }

    public function getColorPathAttribute(){
        $color = Color::where('id',$this->color_id)->first();
        return $color ? $color->color_path:null;
    }

    public function getAvatarPathAttribute(){
        $avatar = UserAvatar::where('id',$this->user_avatar_id)->first(); 
        return $avatar ? $avatar->avatar_path:null;
    }

    public function getBeginnerAvatarPathAttribute(): ?string
    {
        if (! $this->beginner_mode || $this->beginner_step >= 6) {
            return null;
        }

        $paths = [
            1 => './images/animals/beginner/stage-01-egg.webp',
            2 => './images/animals/beginner/stage-02-small-crack.webp',
            3 => './images/animals/beginner/stage-03-large-crack.webp',
            4 => './images/animals/beginner/stage-04-peeking.webp',
            5 => './images/animals/beginner/stage-05-limbs.webp',
        ];

        return $paths[$this->beginner_step] ?? $paths[1];
    }

    public function getDisplayAnimalAttribute(): ?array
    {
        if ($this->beginner_avatar_path) {
            return [
                'id' => null,
                'name' => 'たまご',
                'avatar_path' => $this->beginner_avatar_path,
                'avatar_path_walk' => $this->beginner_avatar_path,
                'required_level' => 0,
                'beginner_step' => $this->beginner_step,
            ];
        }

        $animal = $this->current_animal;

        return $animal ? $animal->toArray() : null;
    }

    public function getDisplayAnimalPathAttribute(): ?string
    {
        return $this->beginner_avatar_path ?: $this->current_animal?->avatar_path;
    }

    public function getCurrentLevelAttribute()
    {
        $challenge = Challenge::where('user_id',$this->id)
        ->where('skill_id','<',26)
        ->orderByDesc('id')->first();


        if(!$challenge){
            return 1;
        }

        if($challenge->success_score >= 3){
            return min($challenge->skill_id + 1, 26);
        }

        return $challenge->skill_id;
    }

    public function getCurrentAnimalAttribute()
    {
        $animal = Animal::where('required_level','<=',$this->current_level)
        ->orderByDesc('required_level')->first();

        return $animal ? $animal: null;
    }

    public function lastSeenAnimal(){
    return $this->belongsTo(Animal::class,'last_seen_animal_id');}

    public function getReceivedLikesAttribute()
    {
        return $this->likes()->count();
    }

    public function getRemainLevelAttribute()
    {
            return 6 - ( $this->current_level % 5);
        }


    public function getSkillNameAttribute(){
        return Skill::where('required_level',$this->current_level)->first()->name ?? null;
    }

    public function getSuccessScoreAttribute(){
        $challenge = Challenge::where('user_id',$this->id)
        ->latest()->first();

        if(!$challenge){
            return 0;
        }

        return $challenge->success_score >= 3
        ? $challenge->success_score=0
        : $challenge->success_score;
    }

    public function getEarnedPointsAttribute(){
        return Point::where('user_id',$this->id)->sum('points') ;
    }

    public function userItems()
    {
        return $this->hasMany(UserItem::class,'user_id');
    }

    public function getUserItemAttribute(){
        return $this->userItems()->with('item')->get();
    }

    public function getEquippedItemPathAttribute(){
        return $this->userItems()->where('is_equipped',true)?->first()?->item?->avatar_path;
    }

    public function getRemainSkillsAttribute(){
        $basicSkill = $this->current_level <= 25 ?
            Skill::where('required_level','=',$this->current_level)
            ->first() :collect();

        $nextSkill = Skill::whereIn('category',['ソロ中級','ペア'])->whereDoesntHave('challenges',function($query){
            $query->where('user_id',$this->id);
        })->get();

        return [
            'basic_skill' =>$basicSkill,
            'next_skill'=>$nextSkill,
        ];
    }

    public function points(){
        return $this->hasMany(Point::class,'user_id');
    }
    
    public function skillTips(){
        return $this->hasMany(SkillTip::class,'user_id');
    }

    public function skillTipReads()
    {
        return $this->hasMany(SkillTipRead::class, 'user_id');
    }

}
