<?php

namespace App\Http\Controllers;

use App\Models\OthersPage;
use App\Models\StoreSetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OthersPageController extends Controller
{
    // ✅ Show all pages
    public function index()
    {
        return Inertia::render('pages/Index', [
            'pages' => OthersPage::latest()->get()
        ]);
    }

    // ✅ Show create form
    public function create()
    {
        return Inertia::render('pages/Create');
    }

    // ✅ Store new page
    public function store(Request $request)
    {
        $validated = $request->validate([
            'slug' => 'required|unique:otherspages',
            'title' => 'required',
            'content' => 'required',
            'meta_title' => 'nullable',
            'meta_description' => 'nullable',
        ]);

        OthersPage::create($validated);

        return redirect()->route('pages.index')
            ->with('message', 'Page created successfully!');
    }

    // ✅ Edit page
    public function edit(OthersPage $page)
    {
        return Inertia::render('pages/Edit', [
            'page' => $page
        ]);
    }

    // ✅ Update page
    public function update(Request $request, OthersPage $page)
    {
        $validated = $request->validate([
            'slug' => 'required|unique:otherspages,slug,' . $page->id,
            'title' => 'required',
            'content' => 'required',
            'meta_title' => 'nullable',
            'meta_description' => 'nullable',
        ]);

        $page->update($validated);

        return redirect()->route('pages.index')
            ->with('message', 'Page updated successfully!');
    }
    public function showPublicPage($slug)
    {
        $page = OthersPage::where('slug', $slug)->firstOrFail();
        $settings = StoreSetting::first();
        return Inertia::render('pages/View', [
            'page' => $page,
            'user' => Auth::user(),
            'settings' => $settings,
        ]);
    }
}
