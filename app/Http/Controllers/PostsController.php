<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            // Getting All objects
            $Get_Obj = Post::all();

            // Getting All objects with foreign key
            $Get_Obj_with_fk = Post::with('user')->get();

            // ordering objects
            $Get_Obj_by_order = Post::with('user')->orderby('id','desc')->get();

            // objects by where clue
            $Get_Obj_by_where = Post::with('user')->orderby('id','desc')->where('title','=','def')->get();

            // objects by multiple where clues
            $Get_Obj_by_multi_where = Post::with('user')->orderby('id','desc')->where([['title','=','abc'],['id','=',10]])->get();

            // Finding object using like
            $Find_string = 'AB';
            $Get_Obj_by_like_where = Post::where('title','like',"%{$Find_string}%")->get();

            // taking first 3 objects
            $Get_Obj_take_top_3 = Post::take(3)->with('user')->get();

            return response()->json([
                'Get_Obj_take_top_3' => $Get_Obj_take_top_3,
                'Get_Obj_by_like_where' => $Get_Obj_by_like_where,
                'Get_Obj_by_multi_where' => $Get_Obj_by_multi_where,
                'Get_Obj_by_where' => $Get_Obj_by_where,
                'Get_Obj_by_order' => $Get_Obj_by_order,
                'Get_Obj_with_fk' => $Get_Obj_with_fk,
                'Get_all_posts' => $Get_Obj
            ],200);
        }
        catch(\Exception $e)
        {
            return response()->json([
                'success' => false,
                'message' => 'Please try again later.',
            ],500);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        try{
            $FormObj = $this->GetForm($request);
            $FormObj['user_id'] = Auth::user()->id;
    
            if (isset($FormObj['tags'])) {
                $FormObj['tags'] = json_encode($FormObj['tags']);
            }
            
            $storeObj =  Post::create($FormObj);
    
            return response()->json([
                'success' => true,
                'message' => 'Post created successfully',
                'data'=> $storeObj
            ]);
        }
        catch(\Exception $e)
        {
            return response()->json([
                'success' => false,
                'message' => 'Please try again later.',
            ],500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        try{
            $Find_string = $id;
            $Get_Obj_by_search = Post::where('title','like',"%{$Find_string}%")->get();

            return response()->json([
                'success' => true, 
                'data'=> $Get_Obj_by_search
            ]);
        }
        catch(\Exception $e)
        {
            return response()->json([
                'success' => false,
                'message' => 'Please try again later.',
            ],500);
        }
        
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        try{
            $obj_find = Post::find($id); 
            
            if (isset($obj_find)) {
                $FormObj = $this->GetForm($request);

                $obj_find->title = $FormObj['title'];
                $obj_find->body = $FormObj['body'];
                $obj_find->tags = json_encode($FormObj['tags']); 
                $obj_find->Save();

                return response()->json([
                    'success' => true, 
                    'req'=> $FormObj,
                    'data'=> $obj_find,
                    'message'=> 'post has been updated',
                ]);
            }
            else{
                return response()->json([
                    'success' => false, 
                ]);
            } 
        }
        catch(\Exception $e)
        {
            return response()->json([
                'success' => false,
                'message' => 'Please try again later.',
            ],500);
        }
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        try{
            $obj_find = Post::find($id);
            $obj_find->delete();
    
            return response()->json([
                'success' => true,
                'message' => 'Post has been deleted successfully',
                'data' => $obj_find,
            ],200);

        }
        catch(\Exception $e)
        {
            return response()->json([
                'success' => false,
                'message' => 'Please try again later.',
            ],500);
        }
    }

    // 
    // Custom Request Validation 
    // 
    public function GetForm(Request $request)
    {        
        return $this->validate($request, [
            'title' => ['required','max:150'],
            'body' =>  ['required','max:1400'],
            'tags' =>  ['bail'],
        ],
        [
            'title.required' => 'Posts Title is required',
            'title.max' => 'The title is too long. please reduce the title to less than 150 characters',
            'body.required' => 'Posts Body is required',
        ]);
    }
}
