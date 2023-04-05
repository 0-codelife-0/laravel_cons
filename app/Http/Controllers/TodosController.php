<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Todo;

class TodosController extends Controller
{
    public function index(Request $request){
        if($request->wantsJson()){
            $todos = Todo::get();
            return response()->json(['data' => $todos]);
        }
        return view('Todos.index');
    }

    public function add(Request $request){
        if($request->isMethod('post')){
            $todo = Todo::create($request->all());
            if($todo){
                return response()->json(['title' => 'Todo has been save', 'icon' => 'success']);
            }else{
                return response()->json(['title' => 'Todo could not be save', 'icon' => 'error']);
            }
        }
    }

    public function edit($id = null, Request $request){
        $todo = Todo::findOrFail($id);
        if($request->isMethod('post')){
            if($todo->update($request->all())){
                return response()->json(['title' => 'Todo has been update', 'icon' => 'success']);
            }else{
                return response()->json(['title' => 'Todo could not be update', 'icon' => 'error']);
            }
        }
        return response()->json($todo);
    }

    public function delete($id = null, Request $request){
        $todo = Todo::findOrFail($id);
        if($todo->delete()){
            return response()->json(['title' => 'Todo has been deleted', 'icon' => 'success']);
        }else{
            return response()->json(['title' => 'Todo could not be deleted', 'icon' => 'error']);
        }
    }
}
