<?php

namespace App\Http\Controllers;

use App\tl_member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;

class ClientController extends Controller
{

    public function index()
    {
        return tl_member ::all();
    }

    public function show(tl_member $tl_member)
    {
        return $tl_member;
    }

    public function store(Request $request)
    {
        $this -> validate($request, [
            'title' => 'required|unique:tl_membersSeeder|max:255',
            'description' => 'required',
            'price' => 'integer',
            'availability' => 'boolean',
        ]);
        $tl_member = tl_member ::create($request -> all());
        return response() -> json($tl_member, 201);
    }

    public function update(Request $request, tl_member $tl_member)
    {
        $tl_member -> update($request -> all());
        return response() -> json($tl_member, 200);
    }

    public function delete(tl_member $tl_member)
    {
        $tl_member -> delete();
        return response() -> json(null, 204);
    }

    public static function getClients($type,bool $all=null)
    {
        switch ($type) {
            case 'new':
                $items= self::get_newClients();
                break;
            case 'old' :
                $items= self::get_oldClients();
                break;
            case 'disabled' :
            default :
            $items= self::get_disabledClients();
                break;
        }
        if(!$all)
            return $items->paginate(50)->withPath("");
        return $items->get();
    }


    private static function get_newClients(){
        $items=new tl_member();
        $items=$items->where(env('DB_FEE'),'>=',2.50)->where(env('DB_DISABLE'),'!=',1);
        $items=$items->has('transactions', '<', 1);
        return $items;
    }
    private static function get_oldClients(){
        $items=new tl_member();

        $items=$items->where(env('DB_FEE'),'>=',2.50)->where(env('DB_DISABLE'),'!=',1);
        $items=$items->where(function ($sub_query){
            $sub_query->whereHas('transactions');
        });
        return $items;
    }
    private static function get_disabledClients(){
        $items=new tl_member();
        $items=$items->where(env('DB_FEE'),'<',2.50)->orwhere(env('DB_DISABLE'),'=',1);
        return $items;
    }
}
