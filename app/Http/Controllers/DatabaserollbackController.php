<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Department;
use App\Models\Brand;



class DatabaserollbackController extends Controller
{
    //
    public function store(Request $request){

        DB::beginTransaction();

        try{
            $this->validate($request, [
                'name' =>  ['required'],
                'color' =>  ['required'],

                'deparment_name' =>  ['required'],
                'deparment_location' =>  ['required'],

                'brand_name' =>  ['required'],
                'brand_code' =>  ['required'],


            ],
            [
                'name' => 'Product name is required',
                'color' => 'Product color is required',

                'deparment_name' => 'Department name is required',
                'deparment_location' => 'Department location is required',


                'brand_name' => 'Brand name is required',
                'brand_code' => 'Brand code is required',
            ]);

            $Product = new Product;
            $Product->name = $request->name;
            $Product->color = $request->color;
            $Product->save();

            $Product_get_id = Product::take(1)->orderby('id', 'desc')->get();

            $Department = new Department;
            $Department->deparment_name = $request->deparment_name;
            $Department->deparment_location = $request->deparment_location;
            $Department->product_id = $Product_get_id[0]->id;
            $Department->save();

            $Brand = new Brand;
            $Brand->brand_name = $request->brand_name;
            $Brand->brand_code = $request->brand_code;
            $Brand->product_idWW = $Product_get_id[0]->id;
            $Brand->save();

            DB::commit();


            return response()->json([
                'success'=>true,
                'storedProduct' => $Product,
                'storedDepartment' => $Department,
                'storedBrand' => $Brand,

            ]);
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            $MakeLog = [ Auth::user(), url()->current(), $e->getMessage() ];
            return DatabaseRollbackLog($MakeLog);
        }
    }


    public function GetForm(Request $request)
    {
        return $this->validate($request, [
            'name' =>  ['required'],
            'color' =>  ['required'],

            'deparment_name' =>  ['required'],
            'deparment_location' =>  ['required'],

            'brand_name' =>  ['required'],
            'brand_code' =>  ['required'],


        ],
        [
            'name' => 'Product name is required',
            'color' => 'Product color is required',

            'deparment_name' => 'Department name is required',
            'deparment_location' => 'Department location is required',


            'brand_name' => 'Brand name is required',
            'brand_code' => 'Brand code is required',
        ]);
    }
}
