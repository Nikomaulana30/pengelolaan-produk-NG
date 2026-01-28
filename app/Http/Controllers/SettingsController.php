<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    /**
     * Show settings page
     */
    public function index()
    {
        $user = Auth::user();
        return view('settings.index', compact('user'));
    }

    /**
     * Update display preferences (theme, language, timezone)
     */
    public function updatePreferences(Request $request)
    {
        $validated = $request->validate([
            'theme' => 'required|in:light,dark',
        ]);

        // Update user theme
        Auth::user()->update($validated);
        
        // Refresh user from database
        Auth::user()->refresh();

        return redirect()->route('settings.index')
            ->with('success', 'Tema berhasil diubah!');
    }

    /**
     * Update notification preferences
     */
    public function updateNotifications(Request $request)
    {
        $user = Auth::user();
        
        // Checkbox yang tidak di-check tidak akan terkirim, jadi set false untuk semua dulu
        $notifications = [
            'email_notifications' => false,
            'approval_notifications' => false,
            'activity_notifications' => false,
        ];
        
        // Update dengan nilai yang dikirim (jika di-check akan bernilai '1')
        if ($request->has('email_notifications')) {
            $notifications['email_notifications'] = true;
        }
        if ($request->has('approval_notifications')) {
            $notifications['approval_notifications'] = true;
        }
        if ($request->has('activity_notifications')) {
            $notifications['activity_notifications'] = true;
        }

        // Save to database
        $user->update($notifications);

        return back()->with('success', 'Pengaturan notifikasi berhasil diperbarui!');
    }
}
