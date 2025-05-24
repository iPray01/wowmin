<?php

namespace App\Http\Controllers;

use App\Models\SmsMessage;
use App\Models\SmsTemplate;
use App\Models\SmsGroup;
use App\Models\SmsMessageRecipient;
use App\Models\Member;
use App\Services\SmsService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SmsController extends Controller
{
    protected $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        $query = SmsMessage::with(['sender', 'template', 'group']);
        
        // Filter by status if provided
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        
        // Filter by sender if provided
        if ($request->has('sender_id') && $request->sender_id) {
            $query->where('sender_id', $request->sender_id);
        }
        
        // Filter by group if provided
        if ($request->has('group_id') && $request->group_id) {
            $query->where('group_id', $request->group_id);
        }
        
        // Filter by date range if provided
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        } elseif ($request->has('start_date')) {
            $query->where('created_at', '>=', $request->start_date);
        } elseif ($request->has('end_date')) {
            $query->where('created_at', '<=', $request->end_date);
        }
        
        // Search by message content
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('content', 'like', '%' . $search . '%')
                  ->orWhere('subject', 'like', '%' . $search . '%');
            });
        }
        
        $messages = $query->orderBy('created_at', 'desc')
                         ->paginate(20);
        
        $groups = SmsGroup::orderBy('name')->get();
        $statuses = [
            'all' => 'All Messages',
            'draft' => 'Draft',
            'scheduled' => 'Scheduled',
            'sending' => 'Sending',
            'sent' => 'Sent',
            'failed' => 'Failed'
        ];
        
        return view('sms.index', compact('messages', 'groups', 'statuses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $templates = SmsTemplate::orderBy('name')->get();
        $groups = SmsGroup::orderBy('name')->get();
        $members = Member::where('is_active', true)
                        ->whereNotNull('phone')
                        ->orderBy('last_name')
                        ->get();
        
        return view('sms.create', compact('templates', 'groups', 'members'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'recipient_type' => 'required|in:group,individual,all',
            'group_id' => 'required_if:recipient_type,group|nullable|exists:sms_groups,id',
            'member_ids' => 'required_if:recipient_type,individual|nullable|array',
            'member_ids.*' => 'exists:members,id',
            'scheduled_at' => 'nullable|date|after_or_equal:now',
            'template_id' => 'nullable|exists:sms_templates,id',
            'status' => 'required|in:draft,scheduled,send',
        ]);

        DB::beginTransaction();
        
        try {
            // Create the SMS message
            $smsMessage = SmsMessage::create([
                'subject' => $validated['subject'],
                'content' => $validated['content'],
                'sender_id' => auth()->id(),
                'group_id' => $validated['recipient_type'] === 'group' ? $validated['group_id'] : null,
                'scheduled_at' => $validated['scheduled_at'] ?? null,
                'template_id' => $validated['template_id'] ?? null,
                'status' => $validated['status'] === 'send' ? 'pending' : $validated['status'],
            ]);
            
            // Add recipients based on recipient type
            if ($validated['status'] !== 'draft') {
                $this->addRecipients($smsMessage, $validated['recipient_type'], $validated['member_ids'] ?? null);
            }
            
            DB::commit();

            // If status is 'send', process the message immediately
            if ($validated['status'] === 'send') {
                $this->smsService->sendMessage($smsMessage);
            }
            
            return redirect()->route('sms.show', $smsMessage)
                             ->with('success', 'SMS message ' . ($validated['status'] === 'send' ? 'sent' : 'created') . ' successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Failed to create SMS message: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SmsMessage  $smsMessage
     * @return \Illuminate\Http\Response
     */
    public function show(SmsMessage $smsMessage)
    {   
        $smsMessage->load(['sender', 'template', 'group', 'recipients.member']);
        
        // Get delivery statistics
        $deliveryStats = [
            'total' => $smsMessage->recipients()->count(),
            'delivered' => $smsMessage->recipients()->where('status', 'delivered')->count(),
            'failed' => $smsMessage->recipients()->where('status', 'failed')->count(),
            'pending' => $smsMessage->recipients()->whereIn('status', ['pending', 'processing'])->count(),
        ];
        
        return view('sms.show', compact('smsMessage', 'deliveryStats'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SmsMessage  $smsMessage
     * @return \Illuminate\Http\Response
     */
    public function edit(SmsMessage $smsMessage)
    {   
        // Only allow editing of draft messages
        if ($smsMessage->status !== 'draft') {
            return redirect()->route('sms.show', $smsMessage)
                             ->with('error', 'Only draft messages can be edited.');
        }
        
        $templates = SmsTemplate::orderBy('name')->get();
        $groups = SmsGroup::orderBy('name')->get();
        $members = Member::where('is_active', true)
                        ->whereNotNull('phone')
                        ->orderBy('last_name')
                        ->get();
        
        return view('sms.edit', compact('smsMessage', 'templates', 'groups', 'members'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SmsMessage  $smsMessage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SmsMessage $smsMessage)
    {   
        // Only allow updating of draft messages
        if ($smsMessage->status !== 'draft') {
            return redirect()->route('sms.show', $smsMessage)
                             ->with('error', 'Only draft messages can be updated.');
        }
        
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'recipient_type' => 'required|in:group,individual,all',
            'group_id' => 'required_if:recipient_type,group|nullable|exists:sms_groups,id',
            'member_ids' => 'required_if:recipient_type,individual|nullable|array',
            'member_ids.*' => 'exists:members,id',
            'scheduled_at' => 'nullable|date|after_or_equal:now',
            'template_id' => 'nullable|exists:sms_templates,id',
            'status' => 'required|in:draft,scheduled,send',
        ]);
        
        // Update the SMS message
        $smsMessage->update([
            'subject' => $validated['subject'],
            'content' => $validated['content'],
            'group_id' => $validated['recipient_type'] === 'group' ? $validated['group_id'] : null,
            'scheduled_at' => $validated['scheduled_at'] ?? null,
            'template_id' => $validated['template_id'] ?? null,
            'status' => $validated['status'] === 'send' ? 'sending' : $validated['status'],
        ]);
        
        // Add recipients based on recipient type if status is not draft
        if ($validated['status'] !== 'draft') {
            // Remove any existing recipients
            $smsMessage->recipients()->delete();
            
            // Add new recipients
            $this->addRecipients($smsMessage, $validated['recipient_type'], $validated['member_ids'] ?? null);
        }
        
        // If status is 'send', process the message immediately
        if ($validated['status'] === 'send') {
            $this->processSmsMessage($smsMessage);
        }
        
        return redirect()->route('sms.show', $smsMessage)
                         ->with('success', 'SMS message ' . ($validated['status'] === 'send' ? 'sent' : 'updated') . ' successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SmsMessage  $smsMessage
     * @return \Illuminate\Http\Response
     */
    public function destroy(SmsMessage $smsMessage)
    {   
        // Only allow deleting of draft or failed messages
        if (!in_array($smsMessage->status, ['draft', 'failed'])) {
            return redirect()->route('sms.show', $smsMessage)
                             ->with('error', 'Only draft or failed messages can be deleted.');
        }
        
        // Delete all recipients
        $smsMessage->recipients()->delete();
        
        // Delete the message
        $smsMessage->delete();
        
        return redirect()->route('sms.index')
                         ->with('success', 'SMS message deleted successfully.');
    }
    
    /**
     * Send a draft or scheduled message immediately.
     *
     * @param  \App\Models\SmsMessage  $smsMessage
     * @return \Illuminate\Http\Response
     */
    public function send(SmsMessage $smsMessage)
    {   
        // Only allow sending of draft or scheduled messages
        if (!in_array($smsMessage->status, ['draft', 'scheduled'])) {
            return redirect()->route('sms.show', $smsMessage)
                             ->with('error', 'Only draft or scheduled messages can be sent.');
        }
        
        // If it's a draft, we need to add recipients first
        if ($smsMessage->status === 'draft') {
            return redirect()->route('sms.edit', $smsMessage)
                             ->with('info', 'Please select recipients before sending this message.');
        }
        
        try {
            $this->smsService->sendMessage($smsMessage);
            return redirect()->route('sms.show', $smsMessage)
                           ->with('success', 'SMS message is being sent.');
        } catch (\Exception $e) {
            return redirect()->route('sms.show', $smsMessage)
                           ->with('error', 'Failed to send SMS message: ' . $e->getMessage());
        }
    }
    
    /**
     * Cancel a scheduled message.
     *
     * @param  \App\Models\SmsMessage  $smsMessage
     * @return \Illuminate\Http\Response
     */
    public function cancel(SmsMessage $smsMessage)
    {   
        // Only allow canceling of scheduled messages
        if ($smsMessage->status !== 'scheduled') {
            return redirect()->route('sms.show', $smsMessage)
                             ->with('error', 'Only scheduled messages can be canceled.');
        }
        
        // Update status to draft
        $smsMessage->update(['status' => 'draft']);
        
        return redirect()->route('sms.show', $smsMessage)
                         ->with('success', 'Scheduled SMS message has been canceled.');
    }
    
    /**
     * Add recipients to an SMS message.
     *
     * @param  \App\Models\SmsMessage  $smsMessage
     * @param  string  $recipientType
     * @param  array|null  $memberIds
     * @return void
     */
    private function addRecipients(SmsMessage $smsMessage, $recipientType, $memberIds = null)
    {   
        $recipients = [];
        
        switch ($recipientType) {
            case 'group':
                // Get all members in the group who haven't opted out
                if ($smsMessage->group_id) {
                    $groupMembers = DB::table('sms_group_member')
                        ->where('sms_group_id', $smsMessage->group_id)
                        ->where('opt_out', false)
                        ->pluck('member_id');
                    
                    $members = Member::whereIn('id', $groupMembers)
                                    ->whereNotNull('phone')
                                    ->get();
                    
                    foreach ($members as $member) {
                        $recipients[] = [
                            'sms_message_id' => $smsMessage->id,
                            'member_id' => $member->id,
                            'phone_number' => $member->phone,
                            'status' => 'pending',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }
                break;
                
            case 'individual':
                // Add specified members
                if ($memberIds) {
                    $members = Member::whereIn('id', $memberIds)
                                    ->whereNotNull('phone')
                                    ->get();
                    
                    foreach ($members as $member) {
                        $recipients[] = [
                            'sms_message_id' => $smsMessage->id,
                            'member_id' => $member->id,
                            'phone_number' => $member->phone,
                            'status' => 'pending',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }
                break;
                
            case 'all':
                // Add all active members with phone numbers
                $members = Member::where('is_active', true)
                                ->whereNotNull('phone')
                                ->get();
                
                foreach ($members as $member) {
                    $recipients[] = [
                        'sms_message_id' => $smsMessage->id,
                        'member_id' => $member->id,
                        'phone_number' => $member->phone,
                        'status' => 'pending',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                break;
        }
        
        // Insert all recipients in chunks to avoid memory issues
        foreach (array_chunk($recipients, 100) as $chunk) {
            SmsMessageRecipient::insert($chunk);
        }
    }
    
    /**
     * Process an SMS message for sending.
     *
     * @param  \App\Models\SmsMessage  $smsMessage
     * @return void
     */
    private function processSmsMessage(SmsMessage $smsMessage)
    {   
        // In a real application, this would integrate with an SMS gateway API
        // For now, we'll simulate the process by updating the status
        
        // Update message status to sent
        $smsMessage->update(['status' => 'sent']);
        
        // Update all recipients to delivered (in a real app, this would happen asynchronously)
        $smsMessage->recipients()->update([
            'status' => 'delivered',
            'sent_at' => now(),
            'delivered_at' => now(),
        ]);
        
        // In a real application, you would:
        // 1. Queue the message for sending
        // 2. Process it in the background
        // 3. Update statuses as delivery reports come in
    }

    /**
     * Handle Twilio status callback webhook
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function handleTwilioWebhook(Request $request)
    {
        $messageId = $request->input('MessageSid');
        $status = $request->input('MessageStatus');

        // Find the recipient by delivery_id (MessageSid)
        $recipient = SmsMessageRecipient::where('delivery_id', $messageId)->first();

        if ($recipient) {
            $mappedStatus = $this->smsService->mapDeliveryStatus($status);
            
            $recipient->update([
                'status' => $mappedStatus,
                'delivered_at' => $mappedStatus === 'delivered' ? now() : null,
            ]);

            // Update parent message status
            $this->smsService->updateMessageStatus($recipient->message);
        }

        return response()->json(['success' => true]);
    }
}

