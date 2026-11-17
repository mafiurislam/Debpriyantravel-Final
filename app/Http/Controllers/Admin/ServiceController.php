<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::orderBy('sort_order', 'asc')->get();
        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        return view('admin.services.form', ['service' => new Service()]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'bengali_subtitle' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:100',
            'image' => 'nullable|string|max:255',
            'image_file' => 'nullable|image|max:5120',
            'btn_text' => 'nullable|string|max:100',
            'whatsapp_message' => 'nullable|string',
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

        Service::create($validated);

        return redirect()->route('admin.services.index')->with('success', 'Service created successfully!');
    }

    public function edit(Service $service)
    {
        return view('admin.services.form', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'bengali_subtitle' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:100',
            'image' => 'nullable|string|max:255',
            'image_file' => 'nullable|image|max:5120',
            'btn_text' => 'nullable|string|max:100',
            'whatsapp_message' => 'nullable|string',
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

        $service->update($validated);

        return redirect()->route('admin.services.index')->with('success', 'Service updated successfully!');
    }

    public function destroy(Service $service)
    {
        $service->delete();
        return redirect()->route('admin.services.index')->with('success', 'Service deleted successfully!');
    }
}
