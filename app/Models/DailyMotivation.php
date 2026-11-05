<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class DailyMotivation extends Model
{
    use HasFactory;



    public function getDailyRandomMotivation($period){
        return DailyMotivation::where('time', $period)->inRandomOrder()->first();
    }

    public function getAllDailyMotivations(){
        return DailyMotivation::orderBy('id', 'DESC')->get();
    }

    public function setNewDailyMotivation(Request $request){
        $daily = new DailyMotivation();
        $daily->time = $request->time ?? '';
        $daily->motivation = $request->motivation ?? '';
        $daily->author = $request->author ?? '';
        $daily->save();
    }
    public function editDailyMotivation(Request $request){
        $daily = DailyMotivation::find($request->daily);
        $daily->time = $request->time ?? '';
        $daily->motivation = $request->motivation ?? '';
        $daily->author = $request->author ?? '';
        $daily->save();
    }
}
