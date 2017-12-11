<?php

namespace App\Http\Controllers;
namespace App\Http\Controllers\todo;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use App\todo;
use Session;

class todoController extends Controller
{
    public function index()
    {
        $results =  todo::all();
        //return $results =  todo::where('id',8)->toSql();
        return view('todo.home', compact('results'));
    }
#------------------------------------------------------------------# 
    public function create()
    {
        return view('/todo.create');
    }
#------------------------------------------------------------------# 
    public function insert(Request $request)
    {
     //dd($request);
        $record = new todo;
        $this->validate($request,[
            'degree' =>'required',
            'institute' =>'required|unique:todos',
        ]);
        $record->degree = $request->degree;
        $record->institute = $request->institute;
        $record->year = $request->year;
        $record->save();
        return redirect('/todo');
    }
#------------------------------------------------------------------# 
    public function show($id)
    {
        //
    }
#------------------------------------------------------------------# 
    public function edit($id)
    {
        return $item =  todo::find($id);
       // return view('todo.edit',compact('item'));
    }
#------------------------------------------------------------------# 
    public function update(Request $request, $id)
    {
        $record =  todo::find($id);
        $this->validate($request,[
            'degree' =>'required',
            'institute' =>'required',
        ]);
        $record->degree = $request->degree;
        $record->institute = $request->institute;
        $record->year = $request->year;
        $record->save();
        Session::flash('message', 'Record Updated successfully');
        return redirect('/todo');
    }
#------------------------------------------------------------------# 
    public function destroy($id)
    {
        //
    }
}
