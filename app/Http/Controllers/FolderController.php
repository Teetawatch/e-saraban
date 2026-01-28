<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FolderController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $folders = \App\Models\Folder::where('department_id', $user->department_id)
                    ->withCount('documents')
                    ->latest()
                    ->get();
                    
        return view('folders.index', compact('folders'));
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'year' => 'nullable|string|max:4',
        ]);

        \App\Models\Folder::create([
            'name' => $request->name,
            'description' => $request->description,
            'year' => $request->year ?? (date('Y') + 543),
            'department_id' => auth()->user()->department_id,
            'user_id' => auth()->id(),
        ]);

        return back()->with('success', 'สร้างแฟ้มเรียบร้อยแล้ว');
    }

    public function show(\App\Models\Folder $folder)
    {
        // Check access permission (same department)
        if ($folder->department_id !== auth()->user()->department_id) {
            abort(403);
        }

        $documents = $folder->documents()->latest()->get();
        return view('folders.show', compact('folder', 'documents'));
    }
    
    public function fileDocument(\Illuminate\Http\Request $request, \App\Models\Document $document)
    {
        $request->validate([
            'folder_id' => 'required|exists:folders,id'
        ]);
        
        // Validation: Folder must belong to same department
        $folder = \App\Models\Folder::findOrFail($request->folder_id);
        if ($folder->department_id !== auth()->user()->department_id) {
            abort(403, 'Unauthorized access to folder');
        }

        $document->update(['folder_id' => $request->folder_id]);

        return back()->with('success', 'จัดเก็บเข้าแฟ้มเรียบร้อยแล้ว');
    }
}
