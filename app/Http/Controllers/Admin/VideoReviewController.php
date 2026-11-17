<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VideoReview;
use Illuminate\Http\Request;

class VideoReviewController extends Controller
{
    public function index()
    {
        $videos = VideoReview::orderBy('sort_order', 'asc')->get();
        return view('admin.videos.index', compact('videos'));
    }

    public function create()
    {
        return view('admin.videos.form', ['video' => new VideoReview()]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'youtube_id' => 'required|string|max:100',
            'thumbnail' => 'nullable|string|max:255',
            'thumbnail_file' => 'nullable|image|max:5120',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        // If user enters a full YouTube URL, extract the ID
        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/', $validated['youtube_id'], $matches)) {
            $validated['youtube_id'] = $matches[1];
        }

        if ($request->hasFile('thumbnail_file')) {
            $file = $request->file('thumbnail_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('assets/images'), $filename);
            $validated['thumbnail'] = 'assets/images/' . $filename;
        }

        unset($validated['thumbnail_file']);

        if (empty($validated['thumbnail'])) {
            $validated['thumbnail'] = 'https://img.youtube.com/vi/' . $validated['youtube_id'] . '/hqdefault.jpg';
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        VideoReview::create($validated);

        return redirect()->route('admin.videos.index')->with('success', 'Customer video review added successfully!');
    }

    public function edit(VideoReview $video)
    {
        return view('admin.videos.form', compact('video'));
    }

    public function update(Request $request, VideoReview $video)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'youtube_id' => 'required|string|max:100',
            'thumbnail' => 'nullable|string|max:255',
            'thumbnail_file' => 'nullable|image|max:5120',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        // If user enters a full YouTube URL, extract the ID
        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/', $validated['youtube_id'], $matches)) {
            $validated['youtube_id'] = $matches[1];
        }

        if ($request->hasFile('thumbnail_file')) {
            $file = $request->file('thumbnail_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('assets/images'), $filename);
            $validated['thumbnail'] = 'assets/images/' . $filename;
        }

        unset($validated['thumbnail_file']);

        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $video->update($validated);

        return redirect()->route('admin.videos.index')->with('success', 'Customer video review updated successfully!');
    }

    public function destroy(VideoReview $video)
    {
        $video->delete();
        return redirect()->route('admin.videos.index')->with('success', 'Customer video review deleted successfully!');
    }
}
