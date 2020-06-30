<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Task;

class TasksController extends Controller
{
    
    public function index()
    {
        $data = [];
        if (\Auth::check()) {
            $user = \Auth::user();
            $tasks = $user->tasks()->get();
            
            $data = [
                'user' => $user,
                'tasks' => $tasks
            ];
            return view('tasks.index', $data);
        }
        else return redirect('login');
        
    }

    public function create()
    {
        $task = new Task;
        
        if (\Auth::check()) {
            return view('tasks.create', [
                'task' => $task,
            ]);
        }
        else return redirect('/');
    }

   
    public function store(Request $request)
    {
        $this->validate($request,[
            'status' => 'required|max:10',
            'content' => 'required|max:255',
        ]);
        
        $request->user()->tasks()->create([
            'status' => $request->status,
            'content' => $request->content,
        ]);
        
        return redirect('/');
    }

    
    public function show($id)
    {
        $task = Task::findOrFail($id);
        if (\Auth::id() === $task->user_id) {
            return view('tasks.show', [
                'task' => $task,
            ]);
        }
        else return redirect('/');
    }

    
    public function edit($id)
    {
        $task = Task::findOrFail($id);
        if (\Auth::id() === $task->user_id) {
            return view('tasks.edit', [
                'task' => $task,
            ]);
        }
        else return redirect('/');
    }    

    
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|max:10',
            'content' => 'required|max:255',
        ]);
        
        $task =Task::findOrFail($id);
        $task->status = $request->status;
        $task->content = $request->content;
        $task->save();
        
        return redirect('/');
    }

    
    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        
        if (\Auth::id() === $task->user_id) {
            $task->delete();
        }
        
        return redirect('/');
    }
}
