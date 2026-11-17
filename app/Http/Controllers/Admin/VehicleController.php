<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function index()
    {
        $vehicles = Vehicle::orderBy('sort_order', 'asc')->get();
        return view('admin.vehicles.index', compact('vehicles'));
    }

    public function create()
    {
        return view('admin.vehicles.form', ['vehicle' => new Vehicle()]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'capacity' => 'nullable|string|max:100',
            'rate_hourly' => 'nullable|string|max:100',
            'rate_km' => 'nullable|string|max:100',
            'rate_package' => 'nullable|string|max:255',
            'features' => 'nullable|string',
            'image' => 'nullable|string|max:255',
            'image_file' => 'nullable|image|max:5120',
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

        Vehicle::create($validated);

        return redirect()->route('admin.vehicles.index')->with('success', 'Vehicle / Cab added successfully!');
    }

    public function edit(Vehicle $vehicle)
    {
        return view('admin.vehicles.form', compact('vehicle'));
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'capacity' => 'nullable|string|max:100',
            'rate_hourly' => 'nullable|string|max:100',
            'rate_km' => 'nullable|string|max:100',
            'rate_package' => 'nullable|string|max:255',
            'features' => 'nullable|string',
            'image' => 'nullable|string|max:255',
            'image_file' => 'nullable|image|max:5120',
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

        $vehicle->update($validated);

        return redirect()->route('admin.vehicles.index')->with('success', 'Vehicle / Cab updated successfully!');
    }

    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();
        return redirect()->route('admin.vehicles.index')->with('success', 'Vehicle deleted successfully!');
    }
}
