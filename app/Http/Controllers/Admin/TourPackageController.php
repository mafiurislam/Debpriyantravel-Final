<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TourPackage;
use Illuminate\Http\Request;

class TourPackageController extends Controller
{
    public function index()
    {
        $packages = TourPackage::orderBy('sort_order', 'asc')->get();
        return view('admin.packages.index', compact('packages'));
    }

    public function create()
    {
        return view('admin.packages.form', ['package' => new TourPackage()]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'bengali_name' => 'nullable|string|max:255',
            'destination' => 'nullable|string|max:255',
            'duration' => 'nullable|string|max:100',
            'price' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'highlights' => 'nullable|string',
            'inclusions' => 'nullable|string',
            'image' => 'nullable|string|max:255',
            'image_file' => 'nullable|image|max:5120',
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

        TourPackage::create($validated);

        return redirect()->route('admin.packages.index')->with('success', 'Tour Package created successfully!');
    }

    public function edit(TourPackage $package)
    {
        return view('admin.packages.form', compact('package'));
    }

    public function update(Request $request, TourPackage $package)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'bengali_name' => 'nullable|string|max:255',
            'destination' => 'nullable|string|max:255',
            'duration' => 'nullable|string|max:100',
            'price' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'highlights' => 'nullable|string',
            'inclusions' => 'nullable|string',
            'image' => 'nullable|string|max:255',
            'image_file' => 'nullable|image|max:5120',
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

        $package->update($validated);

        return redirect()->route('admin.packages.index')->with('success', 'Tour Package updated successfully!');
    }

    public function destroy(TourPackage $package)
    {
        $package->delete();
        return redirect()->route('admin.packages.index')->with('success', 'Tour Package deleted successfully!');
    }
}
