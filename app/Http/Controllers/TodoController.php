<?php

namespace App\Http\Controllers;

use App\Models\ProjectTask;
use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends BaseController
{
    public function store($action, Request $request)
    {



        switch ($action)
        {
            case 'change-status':

                $request->validate([
                    'id' => 'required|integer',
                    'status' => 'required|string',
                ]);

                $todo = Todo::find($request->id);



                if($todo)
                {
                    if($request->status === 'Completed')
                    {
                        $todo->completed = 1;
                    }
                    else{
                        $todo->completed = 0;
                    }

                    $todo->save();
                }

                break;

            case 'get-todo':

                $request->validate([
                    'object_id' => 'required|integer',
                ]);

                $todo = ProjectTask::where('workspace_id',$this->workspace_id)->where('id',$request->object_id)->first();

                if($todo)
                {
                    return response()->json([
                        'status' => 'success',
                        'todo' => $todo,
                    ]);
                }

                break;
            case 'change-project-todo-status':

                $request->validate([
                    'id' => 'required|integer',
                    'status' => 'required|string',
                ]);

                $todo = ProjectTask::find($request->id);



                if($todo)
                {
                    if($request->status === 'Completed')
                    {
                        $todo->completed = 1;
                    }
                    else{
                        $todo->completed = 0;
                    }

                    $todo->save();
                }

                break;

        }
    }
}
