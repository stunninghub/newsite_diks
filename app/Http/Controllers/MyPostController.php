<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MyPostController extends BaseController
{

    public function create(Request $request)
    {
        $edit_id = $request->input('select');
        $name = $request->input('name');
        $description = $request->input('description');
        if (empty($edit_id)) {
            if ($post_id = DB::table('post')
                ->insertGetId([
                    'name' => $name,
                    'description' => $description,
                    'created_at' => date('Y-m-d h:i:s'),
                    'updated_at' => date('Y-m-d h:i:s'),
                ])
            ) {
                return array(
                    "status" => 200,
                    "message" => 'Post published',
                    "post_id"   => $post_id,
                    "name"   => $name,
                    "desc"   => $description
                );
            } else {
                return array(
                    "status" => 500,
                    "message" => 'Post not published',
                );
            }
        } else {
            if (DB::table('post')->where('id', '=', $edit_id)
                ->update([
                    'name' => $name,
                    'description' => $description,
                    'updated_at' => date('Y-m-d h:i:s'),
                ])
            ) {
                return array(
                    "status" => 200,
                    "message" => 'Post published',
                );
            } else {
                return array(
                    "status" => 500,
                    "message" => 'Post not published',
                );
            }
        }
    }


    public static function get_posts()
    {
        return DB::table('post')->select('*')->get();
    }

    public function getpost_data(Request $request)
    {
        $post_id = $request->input('post_id');
        if (!empty($post_id)) {
            return (DB::table('post')->select('*')->where('id', '=', $post_id)->get())[0];
        } else {
            return [];
        }
    }

    public function delete(Request $request){
        $post_id = $request->input('post_id');
        if(DB::table('post')->where('id', '=', $post_id)->delete()){
            return true;
        }else{
            return false;
        }
    }
}
