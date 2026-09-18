<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Point;

class ItemPurchaseController
{
    public function store(Request $request){
        $data = $request->validate([
            'item_id' =>['required','integer','exists:items,id'],
        ]);

        $user = $request->user();

        try{
            DB::transaction(function()use($data,$user){
                $user =$user->lockForUpdate()->firstOrFail();
                $item = Item::findOrFail($data['item_id']);

                $price = $item->required_point;

                $alreadyOwned = $user->userItems()
                ->where('item_id',$item->id)
                ->exists();

                if($alreadyOwned){
                    abort(422,'already owned');
                }

                if($user->earned_points < $price){
                    abort(422, 'lack of point');
                }

                Point::create([
                    'user_id'=>$user->id,
                    'points'=>-$price,
                ]);
                
                $user->userItems()->create([
                    'item_id'=>$item->id,
                ]);

                return response()->json([
                    'message'=>'get the item',
                ]);
            });
        }catch(\Throwable $e){
            return response()->json([
                'message'=> $e->getMessage(),
            ],$e->getCode()===422 ? 422 : 500);
        }
    }
}
