<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource;
use App\Http\Resources\UsersResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index()
    {
        $users=User::all();    
        return UserResource::collection($users);
    }

    public function show(User $user)
    {
        return new UserResource($user);
        
    }

    public function store(Request $request)
    {
        $validated = $request ->validate([
            'name'=>['string','max:6'],
            'password'=>['string','min:5','confirmed'],
            'user_avatar_id'=>['integer','nullable'],
            'color_id'=>['integer','nullable'],
            'login_id'=>['string','min:6','max:8'],
        ]);
        
        $user = new User();

        $user->name = $validated['name'];
        $user->password = Hash::make($validated['password']);
        $user->login_id = $validated['login_id'];
        $user->user_avatar_id = $validated['user_avatar_id'];
        $user->color_id = $validated['color_id'];

       $user->save();

       return response()->json([
       'user'=>new UserResource($user),],201);
    }

    public function update(Request $request)
    {
        $user = $request->user();

       $validate = $request ->validate([
        'name'=>['sometimes','string','max:6'],
        'current_password'=>['nullable','string','min:8','required_with:password','current_password:web'],
        'password'=>['nullable','string',Password::min(8),'required_with:current_password','confirmed'],
        'user_avatar_id'=>['sometimes','integer','nullable'],
        'color_id'=>['sometimes','integer','nullable','exists:colors,id'],
       ],[
        'name.max'=>'名前は6文字以内で入力してください',
        'current_password.current_password'=>'現在のパスワードが正しくありません',
        'current_password.required_with'=>'現在のパスワードを入力してください',
        'password.min'=>'パスワードは8文字以上で入力してください',
        'password.confirmed'=>'パスワードが一致しません',
        'password.required_with'=>'新しいパスワードを入力してください',
       ]);

       if(array_key_exists('name', $validate)){
        $user->name = $validate['name'];
       }

       if(array_key_exists('user_avatar_id', $validate)){
        $user->user_avatar_id = $validate['user_avatar_id'];
       }

       if(array_key_exists('password', $validate)){
        $user->password = Hash::make($validate['password']);
       }

       if(array_key_exists('color_id', $validate)){
        $user->color_id = $validate['color_id'];
       }

       $user->save();

       return new UserResource($user->fresh());
    }

    public function updateAnimalSeen(Request $request ,User $user){
        $request->validate([
            'last_seen_animal_id'=>[
                'nullable',
                'exists:animals,id'
            ],]);

        $user->last_seen_animal_id = $request->last_seen_animal_id;
        $user->save();

        $user->refresh();

        return response()->json($user);
    }

    public function resetPassword(User $user){
        $user->update([
            'password'=>Hash::make('unicycle1234')
        ]);

        return response()->json([
            'message'=>'パスワードを初期化しました',
        ]);
    }

    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([
            'message'=>'ユーザーを削除しました'
        ]);
    }
}
