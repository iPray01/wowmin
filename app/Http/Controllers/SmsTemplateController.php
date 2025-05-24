<?php

namespace App\Http\Controllers;

use App\Models\SmsTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SmsTemplateController extends Controller
{
    /**
     * Display a listing of SMS templates.
     */
    public function index()
    {
        $templates = SmsTemplate::with('creator')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('sms.templates.index', compact('templates'));
    }

    /**
     * Show the form for creating a new SMS template.
     */
    public function create()
    {
        return view('sms.templates.create');
    }

    /**
     * Store a newly created SMS template.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'content' => 'required|string|max:1000',
            'description' => 'nullable|string|max:1000',
        ]);

        $template = new SmsTemplate($validated);
        $template->created_by = Auth::id();
        $template->save();

        return redirect()->route('sms.templates.index')
            ->with('success', 'SMS template created successfully.');
    }

    /**
     * Show the form for editing the specified SMS template.
     */
    public function edit(SmsTemplate $template)
    {
        return view('sms.templates.edit', compact('template'));
    }

    /**
     * Update the specified SMS template.
     */
    public function update(Request $request, SmsTemplate $template)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'content' => 'required|string|max:1000',
            'description' => 'nullable|string|max:1000',
        ]);

        $template->update($validated);

        return redirect()->route('sms.templates.index')
            ->with('success', 'SMS template updated successfully.');
    }

    /**
     * Remove the specified SMS template.
     */
    public function destroy(SmsTemplate $template)
    {
        $template->delete();

        return redirect()->route('sms.templates.index')
            ->with('success', 'SMS template deleted successfully.');
    }

    /**
     * Preview the template with sample data.
     */
    public function preview(SmsTemplate $template)
    {
        // Sample data for preview
        $sampleData = [
            'member_name' => 'John Doe',
            'event_name' => 'Sunday Service',
            'event_date' => now()->format('F j, Y'),
            'event_time' => '10:00 AM',
            'church_name' => 'Grace Community Church',
        ];

        $previewContent = $template->renderContent($sampleData);

        return response()->json([
            'preview' => $previewContent,
        ]);
    }
}
