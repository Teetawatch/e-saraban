<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Folder;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class FolderController extends Controller
{
    /**
     * Display a listing of the folders.
     */
    public function index(): View
    {
        $user = auth()->user();
        $folders = Folder::where('department_id', $user->department_id)
            ->withCount('documents')
            ->latest()
            ->get();

        return view('folders.index', compact('folders'));
    }

    /**
     * Store a newly created folder in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'year' => 'nullable|string|max:4',
        ]);

        Folder::create([
            'name' => $request->name,
            'description' => $request->description,
            'year' => $request->year ?? (date('Y') + 543),
            'department_id' => auth()->user()->department_id,
            'user_id' => auth()->id(),
        ]);

        return back()->with('success', 'สร้างแฟ้มเรียบร้อยแล้ว');
    }

    /**
     * Display the specified folder.
     */
    public function show(Folder $folder): View
    {
        // Check access permission (same department)
        if ($folder->department_id !== auth()->user()->department_id) {
            abort(403);
        }

        $documents = $folder->documents()->latest()->get();
        return view('folders.show', compact('folder', 'documents'));
    }

    /**
     * File a document into a folder.
     */
    public function fileDocument(Request $request, Document $document): RedirectResponse
    {
        $request->validate([
            'folder_id' => 'required|exists:folders,id'
        ]);

        // Validation: Folder must belong to same department
        $folder = Folder::findOrFail($request->folder_id);

        if ($folder->department_id !== auth()->user()->department_id) {
            abort(403, 'Unauthorized access to folder');
        }

        $document->update(['folder_id' => $request->folder_id]);

        return back()->with('success', 'จัดเก็บเข้าแฟ้มเรียบร้อยแล้ว');
    }
}
