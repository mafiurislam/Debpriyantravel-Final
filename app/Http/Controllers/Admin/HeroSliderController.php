<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSlider;
use Illuminate\Http\Request;

class HeroSliderController extends Controller
{
    public function index()
    {
        $sliders = HeroSlider::orderBy('sort_order', 'asc')->get();
        return view('admin.sliders.index', compact('sliders'));
    }

    public function create()
    {
        return view('admin.sliders.form', ['slider' => new HeroSlider()]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'bengali_title' => 'nullable|string|max:255',
            'badge' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'image' => 'nullable|string|max:255',
            'image_file' => 'nullable|image|max:5120',
            'button_text' => 'nullable|string|max:100',
            'button_link' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('assets/images/slider'), $filename);
            $validated['image'] = 'assets/images/slider/' . $filename;
        }

        unset($validated['image_file']);

        if (empty($validated['image'])) {
            $validated['image'] = 'assets/images/slider/slide-sujata-tempo.webp';
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        HeroSlider::create($validated);

        return redirect()->route('admin.sliders.index')->with('success', 'Hero slide created successfully!');
    }

    public function edit(HeroSlider $slider)
    {
        return view('admin.sliders.form', compact('slider'));
    }

    public function update(Request $request, HeroSlider $slider)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'bengali_title' => 'nullable|string|max:255',
            'badge' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'image' => 'nullable|string|max:255',
            'image_file' => 'nullable|image|max:5120',
            'button_text' => 'nullable|string|max:100',
            'button_link' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('assets/images/slider'), $filename);
            $validated['image'] = 'assets/images/slider/' . $filename;
        }

        unset($validated['image_file']);

        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $slider->update($validated);

        return redirect()->route('admin.sliders.index')->with('success', 'Hero slide updated successfully!');
    }

    public function destroy(HeroSlider $slider)
    {
        $slider->delete();
        return redirect()->route('admin.sliders.index')->with('success', 'Hero slide deleted successfully!');
    }
}
