<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Specialization;
use Illuminate\Http\Request;

class SpecializationController extends Controller
{
    public function index()
    {
        $specializations = Specialization::orderBy('sort_order', 'asc')->get();
        return view('admin.specializations.index', compact('specializations'));
    }

    public function create()
    {
        return view('admin.specializations.form', ['specialization' => new Specialization()]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'bengali_subtitle' => 'nullable|string|max:255',
            'image' => 'nullable|string|max:255',
            'image_file' => 'nullable|image|max:5120',
            'btn_text' => 'nullable|string|max:100',
            'btn_link' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('assets/images'), $filename);
            $validated['image'] = 'assets/images/' . $filename;
        }

        unset($validated['image_file']);

        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        Specialization::create($validated);

        return redirect()->route('admin.specializations.index')->with('success', 'Travel category / specialization created successfully!');
    }

    public function edit(Specialization $specialization)
    {
        return view('admin.specializations.form', compact('specialization'));
    }

    public function update(Request $request, Specialization $specialization)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'bengali_subtitle' => 'nullable|string|max:255',
            'image' => 'nullable|string|max:255',
            'image_file' => 'nullable|image|max:5120',
            'btn_text' => 'nullable|string|max:100',
            'btn_link' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('assets/images'), $filename);
            $validated['image'] = 'assets/images/' . $filename;
        }

        unset($validated['image_file']);

        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $specialization->update($validated);

        return redirect()->route('admin.specializations.index')->with('success', 'Travel category / specialization updated successfully!');
    }

    public function destroy(Specialization $specialization)
    {
        $specialization->delete();
        return redirect()->route('admin.specializations.index')->with('success', 'Travel category / specialization deleted successfully!');
    }
}
