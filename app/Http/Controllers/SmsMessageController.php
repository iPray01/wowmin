<?php

namespace App\Http\Controllers;

use App\Models\SmsMessage;
use App\Models\SmsTemplate;
use App\Models\SmsGroup;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Jobs\ProcessSmsMessage;

class SmsMessageController extends Controller
{
    /**
     * Display a listing of SMS messages.
     */
    public function index(Request $request)
    {
        $query = SmsMessage::with(['sender', 'template', 'group'])
            ->withCount('recipients');

        // Filter by status if provided
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by group if provided
        if ($request->has('group') && $request->group) {
            $query->where('group_id', $request->group);
        }

        $messages = $query->orderBy('created_at', 'desc')
            ->paginate(10);

        // Get groups for the filter dropdown
        $groups = SmsGroup::orderBy('name')->get();

        return view('sms.messages.index', compact('messages', 'groups'));
    }

    /**
     * Show the form for creating a new SMS message.
     */
    public function create()
    {
        $templates = SmsTemplate::orderBy('name')->get();
        $groups = SmsGroup::withCount('members')->orderBy('name')->get();
        $members = Member::whereNotNull('phone')
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        return view('sms.messages.create', compact('templates', 'groups', 'members'));
    }

    /**
     * Store a newly created SMS message.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
            'groups' => 'nullable|array',
            'groups.*' => 'exists:sms_groups,id',
            'members' => 'nullable|array',
            'members.*' => 'exists:members,id',
            'manual_numbers' => 'nullable|string',
            'send_time' => 'required|in:now,scheduled',
            'scheduled_at' => 'required_if:send_time,scheduled|nullable|date|after:now',
            'template_id' => 'nullable|exists:sms_templates,id',
        ]);

        try {
            DB::beginTransaction();

            // Create the message
            $message = new SmsMessage([
                'content' => $validated['content'],
                'template_id' => $validated['template_id'],
                'status' => $validated['send_time'] === 'scheduled' ? 'scheduled' : 'pending',
                'scheduled_at' => $validated['send_time'] === 'scheduled' ? $validated['scheduled_at'] : null,
                'sender_id' => Auth::id(),
            ]);
            $message->save();

            // Process manual phone numbers
            if (!empty($validated['manual_numbers'])) {
                $phoneNumbers = array_filter(
                    explode("\n", str_replace("\r", "", $validated['manual_numbers'])),
                    function($number) {
                        return !empty(trim($number));
                    }
                );

                foreach ($phoneNumbers as $number) {
                    $number = trim($number);
                    if (preg_match('/^\+?[1-9]\d{1,14}$/', $number)) {
                        $message->recipients()->create([
                            'phone_number' => $number,
                            'status' => 'pending'
                        ]);
                    }
                }
            }

            // Add group recipients
            if (!empty($validated['groups'])) {
                foreach ($validated['groups'] as $groupId) {
                    $group = SmsGroup::find($groupId);
                    if ($group) {
                        foreach ($group->members as $member) {
                            if ($member->phone) {
                                $message->recipients()->create([
                                    'member_id' => $member->id,
                                    'phone_number' => $member->phone,
                                    'status' => 'pending'
                                ]);
                            }
                        }
                    }
                }
            }

            // Add individual recipients
            if (!empty($validated['members'])) {
                foreach ($validated['members'] as $memberId) {
                    $member = Member::find($memberId);
                    if ($member && $member->phone) {
                        $message->recipients()->create([
                            'member_id' => $member->id,
                            'phone_number' => $member->phone,
                            'status' => 'pending'
                        ]);
                    }
                }
            }

            DB::commit();

            // Send immediately if not scheduled
            if ($validated['send_time'] === 'now') {
                dispatch(new ProcessSmsMessage($message));
            }

            return redirect()->route('sms.messages.show', $message)
                ->with('success', 'SMS message ' . ($validated['send_time'] === 'now' ? 'sent' : 'scheduled') . ' successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to create SMS message: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified SMS message.
     */
    public function show(SmsMessage $message)
    {
        $message->load(['sender', 'template', 'group', 'groups', 'recipients.member']);
        
        // Get delivery statistics
        $deliveryStats = [
            'total' => $message->recipients()->count(),
            'delivered' => $message->recipients()->where('status', 'delivered')->count(),
            'failed' => $message->recipients()->where('status', 'failed')->count(),
            'pending' => $message->recipients()->whereIn('status', ['pending', 'processing'])->count(),
        ];
        
        return view('sms.messages.show', compact('message', 'deliveryStats'));
    }

    /**
     * Cancel a scheduled SMS message.
     */
    public function cancel(SmsMessage $message)
    {
        if ($message->status !== 'scheduled') {
            return back()->with('error', 'Only scheduled messages can be cancelled.');
        }

        $message->update(['status' => 'cancelled']);
        $message->recipients()->update(['status' => 'cancelled']);

        return back()->with('success', 'SMS message cancelled successfully.');
    }

    /**
     * Send a draft SMS message immediately.
     */
    public function send(SmsMessage $message)
    {
        if ($message->status !== 'draft') {
            return back()->with('error', 'Only draft messages can be sent.');
        }

        // Here you would integrate with your SMS service provider
        // For now, we'll just update the status
        $message->update([
            'status' => 'sent',
            'sent_at' => now(),
        ]);

        $message->recipients()->update([
            'status' => 'delivered',
            'sent_at' => now(),
            'delivered_at' => now(),
        ]);

        return back()->with('success', 'SMS message sent successfully.');
    }

    /**
     * Get message delivery status and statistics.
     */
    public function status(SmsMessage $message)
    {
        $stats = $message->recipients()
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        return response()->json([
            'message_status' => $message->status,
            'recipient_stats' => $stats,
        ]);
    }

    /**
     * Handle bulk actions for multiple SMS messages.
     */
    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'bulk_action' => 'required|in:cancel,delete',
            'selected_messages' => 'required|array',
            'selected_messages.*' => 'exists:sms_messages,id'
        ]);

        $action = $validated['bulk_action'];
        $messageIds = $validated['selected_messages'];
        $count = 0;

        try {
            DB::beginTransaction();

            foreach ($messageIds as $messageId) {
                $message = SmsMessage::find($messageId);

                if (!$message) {
                    continue;
                }

                if ($action === 'cancel' && $message->status === 'scheduled') {
                    $message->update(['status' => 'cancelled']);
                    $message->recipients()->update(['status' => 'cancelled']);
                    $count++;
                } elseif ($action === 'delete' && in_array($message->status, ['draft', 'cancelled', 'failed'])) {
                    $message->recipients()->delete();
                    $message->delete();
                    $count++;
                }
            }

            DB::commit();

            $actionText = $action === 'cancel' ? 'cancelled' : 'deleted';
            return redirect()->route('sms.messages.index')
                ->with('success', "{$count} message(s) {$actionText} successfully.");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('sms.messages.index')
                ->with('error', "Failed to {$action} messages: " . $e->getMessage());
        }
    }
}
