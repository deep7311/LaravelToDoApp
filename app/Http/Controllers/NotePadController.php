<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\NotePad;

class NotePadController extends Controller
{
    // This method will show the notepad page
    public function index()
    {
        // OLD (default sort):
        // $notepad = Notepad::all();

        // sort by priority first, then by created_at ASC
        $notepad = Notepad::orderByRaw("
        FIELD(priority, 'high', 'medium', 'low')
    ")->orderBy('created_at', 'asc')->get();

        return view('notepad.create', compact('notepad'));
    }


    // This method will store the note in the database
    public function store(Request $request)
    {
        $rules = [
            'task' => 'required|string|max:255',
            'priority' => 'required|in:low,medium,high',  // Add validation for priority
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->route('notepad.create')->withErrors($validator)->withInput();
        }

        // Store the task with priority
        $notepad = new NotePad();
        $notepad->task = $request->input('task');
        $notepad->priority = $request->input('priority');  // Save priority
        $notepad->save();

        return redirect()->route('notepad.create')->with('success', 'Task Added Successfully');
    }


    // This method will handle the task update
    public function update(Request $request, $id)
    {
        // Validate the input
        $rules = [
            'task' => 'required|string|max:255',
            'priority' => 'required|in:low,medium,high',  // Add validation for priority
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->route('notepad.create')->withErrors($validator)->withInput();
        }

        // Find the task and update it
        $notepad = NotePad::find($id);
        if (!$notepad) {
            return redirect()->route('notepad.create')->with('error', 'Task not found.');
        }

        $notepad->task = $request->input('task');
        $notepad->priority = $request->input('priority');  // Update priority
        $notepad->save();

        return redirect()->route('notepad.create')->with('success', 'Task updated successfully.');
    }


    // This method will handle the task deletion
    public function destroy($id)
    {
        // Find the task by ID and delete it
        $notepad = NotePad::find($id);

        if ($notepad) {
            $notepad->delete();
            return redirect()->route('notepad.create')->with('success', 'Task deleted successfully.');
        } else {
            return redirect()->route('notepad.create')->with('error', 'Task not found.');
        }
    }
}
