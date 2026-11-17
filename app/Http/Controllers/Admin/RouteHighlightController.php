<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RouteHighlight;
use Illuminate\Http\Request;

class RouteHighlightController extends Controller
{
    public function edit()
    {
        $highlight = RouteHighlight::firstOrCreate([], [
            'badge' => 'PAN-INDIA CONNECTIVITY',
            'title' => 'All India Bus Booking Ticket Available',
            'bengali_tagline' => 'সহজ বুকিং, নিশ্চিন্ত যাত্রা, সেরা পরিষেবা',
            'description' => 'Connect seamlessly from Kolkata to destinations all over the nation. Sleeper, AC Deluxe, Seater, and Volvo buses with comfortable pushback seats.',
            'image' => 'assets/images/banners/banner-routes.jpg',
            'routes' => 'SILIGURI, DIGHA, ASANSOL, VARANASI, DELHI, MUMBAI, CHENNAI, HYDERABAD',
            'phone' => '9432430360',
            'email' => 'debpriyan2021@gmail.com',
            'address' => 'KOLKATA-700161',
        ]);

        return view('admin.route-highlight.edit', compact('highlight'));
    }

    public function update(Request $request)
    {
        $highlight = RouteHighlight::firstOrFail();

        $validated = $request->validate([
            'badge' => 'nullable|string|max:100',
            'title' => 'required|string|max:255',
            'bengali_tagline' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'routes' => 'nullable|string',
            'image' => 'nullable|string|max:255',
            'image_file' => 'nullable|image|max:5120',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:150',
        ]);

        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('assets/images/banners'), $filename);
            $validated['image'] = 'assets/images/banners/' . $filename;
        }

        unset($validated['image_file']);

        $highlight->update($validated);

        return back()->with('success', 'Route highlight banner section updated successfully!');
    }
}
