<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TaskController extends Controller
{
    public function index(){
        $tasks = Task::get();
        return response()->json([
            "status_code" => 200,
            "data" => $tasks
        ]);
    }

    public function create(Request $request){
        $rules = [
            'task' => 'required',
            'description' => 'required',
        ];
        $messages = [
            'task.required' => 'Le champ task est requis.',
            'description.required' => 'Le champ descritpion est requis.',
        ];
        try {
            $request->validate($rules, $messages);
            $task = Task::create([
                "task" => $request->input("task"),
                "description" => $request->input("description"),
            ]);

            return response()->json($task,200);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }

    }

    public function update(Request $request, $id){
        $rules = [
            'task' => 'required',
            'description' => 'required',
        ];
        $messages = [
            'task.required' => 'Le champ task est requis.',
            'description.required' => 'Le champ descritpion est requis.',
        ];
         try {
            $request->validate($rules, $messages);
            $task = Task::find($id);
            if ($task) {
                $task->task = $request->input("task");
                $task->description = $request->input("description");
                $task->update();
            }
            else
            {
                return response()->json(["error" => "task not found"]);
            }
         } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
         }
    }

    public function destroy($id){
        $task = Task::find($id);

        if ($task) {
            $task->delete();
            return response()->json(['succes' => "task is deleted"]);
        }
        else
        {
            return response()->json(['error' => "task not found"]);
        }
    }
}
