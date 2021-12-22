<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Gate;

use App\Http\Controllers\Api\BaseApiController;

use App\Http\Models\Brand;
use App\Http\Models\Category;
use App\Http\Models\Country;
use App\Http\Models\Game;
use DB;

class CasinoController extends BaseApiController
{
    public function store(Request $request){

    }
 
    public function update(Student $user, Request $request) {
       
    }

    public function getCountries(Request $request) {

        $result = Country::get();

        return response()->json($result, 200);
        // return $this->sendResponse($result, 'Countries', 200);
    }


    public function getBrands(Request $request) {

        $result = Brand::where('enabled', 1)
        ->where('id', '<>', 0)        
        ->get();

        return response()->json($result, 200);
        // return $this->sendResponse($result, 'Brands', 200);
    }
    
    public function getCategories(Request $request) {
        request()->validate([
            'brandid' => 'required',
        ]);
        $brandid = $request->brandid;

        $result = Category::where('brandid', $brandid)
        ->where('active', 1)        
        ->where('name', '<>', '')        
        ->where('category', '<>', 'all')        
        ->get();

        return response()->json($result, 200);
        // return $this->sendResponse($result, 'Categories', 200);
    }
    
    public function getGames(Request $request) {
        request()->validate([
            'country' => 'required',
            'category' => 'required',
        ]);

        $country = $request->country;
        $brandid = $request->brandid;
        $category = $request->category;

        $condition = $brandid ? " brandid=$brandid " : "true";
        $condition .= $category!='all' ? " AND category='$category' " : "";

        $sql =  "SELECT t1.id, t1.name, t1.launchcode, t1.active, t1.game_provider_id, t1.game_provider_name, t1.rtp, t0.hot, t0.new FROM
        (
          SELECT * FROM (SELECT DISTINCT launchcode, hot, new FROM brand_games WHERE $condition) AS t0 WHERE t0.launchcode NOT IN 
          (
            SELECT launchcode FROM game_brand_block WHERE brandid=$brandid
            UNION
            SELECT launchcode FROM game_country_block WHERE country='$country'
          )
        ) AS t0
        LEFT JOIN
        (
          SELECT t0.*, t1.name AS game_provider_name FROM game AS t0
          LEFT JOIN game_providers AS t1
          ON t0.game_provider_id = t1.id
        ) AS t1
        ON t0.launchcode = t1.launchcode";

        $result = DB::select($sql);
        return response()->json($result, 200);
    }

}
