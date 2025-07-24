<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Todo;
use Illuminate\Support\Facades\DB;

class ToDOController extends Controller
{
    public function getToDo(Request $request){
        try{
            $user = $request->user();
            if($request->filter_date){
                $todos = Todo::with('user')->where('user_id',$user->id)->where('todo_date_time',$request->filter_date)->get();
            }else{
                $todos = Todo::with('user')->where('user_id',$user->id)->get();
            }

            return response()->json($todos,200);
        }catch(\Throwable $th){
            \Log::info($th);
            return response()->json("ToDo data fetch error",400);
        }
    }

    public function store(Request $request){
        try{
            $request->validate([
                'todo_name'=>'required',
                'todo_description'=>'required',
                'todo_date_time'=>'required'
            ]);
            \Log::info($request);

            DB::beginTransaction();
            $todo = new Todo();
            $todo->user_id = $request->User()->id;
            $todo->todo_name = $request->todo_name;
            $todo->todo_description = $request->todo_description;
            $todo->todo_date_time = $request->todo_date_time;
            $todo->is_remind_me = false;
            $todo->is_completed = false;
            $todo ->Save();
            DB::commit();
            return response()->json($todo,200);

        }catch(\Throwable $th){
            \Log::info($th);
            DB::rollback();
            return response()->json("ToDo data fetch error",400);
        }
    }

    public function updateStatus(Request $request){
        try{
            $selectedTodo = Todo::where('id', $request->todo_id)->first();
            DB::beginTransaction();
            $selectedTodo->is_completed = 1;
            $selectedTodo->save();
            DB::commit();
            return response()->json("Success updated",200);
        }
        catch(\Throwable $th){
            DB::rollback();
            \Log::error($th);
            return response()->json("Failed updated",400);
        }


    }

    public function deleteTodo(Request $request){
        try{
            $todo_id = $request->todo_id;
            Todo::destroy($todo_id);
            return response()->json("Success deleted",200);
        }catch(\Throwable $th){
            \Log::error($th);
            return response()->json("Failed delete",400);
        }
    }


}
