<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the settings page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        $settings = [
            'notifications' => [
                'email' => $user->notification_preferences['email'] ?? true,
                'sms' => $user->notification_preferences['sms'] ?? false,
                'browser' => $user->notification_preferences['browser'] ?? true,
            ],
            'display' => [
                'theme' => $user->display_preferences['theme'] ?? 'light',
                'language' => $user->display_preferences['language'] ?? 'en',
            ],
            'privacy' => [
                'profile_visibility' => $user->privacy_settings['profile_visibility'] ?? 'public',
                'show_online_status' => $user->privacy_settings['show_online_status'] ?? true,
            ],
        ];

        return view('settings.index', compact('settings'));
    }

    /**
     * Update the user's settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'notifications.email' => 'boolean',
            'notifications.sms' => 'boolean',
            'notifications.browser' => 'boolean',
            'display.theme' => 'string|in:light,dark',
            'display.language' => 'string|in:en',
            'privacy.profile_visibility' => 'string|in:public,private,members',
            'privacy.show_online_status' => 'boolean',
        ]);

        $user = Auth::user();
        
        // Update notification preferences
        $user->notification_preferences = [
            'email' => $validated['notifications']['email'],
            'sms' => $validated['notifications']['sms'],
            'browser' => $validated['notifications']['browser'],
        ];

        // Update display preferences
        $user->display_preferences = [
            'theme' => $validated['display']['theme'],
            'language' => $validated['display']['language'],
        ];

        // Update privacy settings
        $user->privacy_settings = [
            'profile_visibility' => $validated['privacy']['profile_visibility'],
            'show_online_status' => $validated['privacy']['show_online_status'],
        ];

        $user->save();

        return redirect()->route('settings.index')
            ->with('status', 'Settings updated successfully.');
    }
} 