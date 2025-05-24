<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\MessageThread;
use App\Models\MessageRecipient;
use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Get all threads where the current user is a participant
        $userId = Auth::id();
        
        $query = MessageThread::whereHas('participants', function($q) use ($userId) {
            $q->where('user_id', $userId);
        })->with(['latestMessage', 'participants']);
        
        // Filter by status if provided
        if ($request->has('status') && $request->status !== 'all') {
            $status = $request->status;
            $query->whereHas('recipients', function($q) use ($userId, $status) {
                $q->where('user_id', $userId)
                  ->where('status', $status);
            });
        }
        
        // Search by subject or content
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('subject', 'like', '%' . $search . '%')
                  ->orWhereHas('messages', function($q2) use ($search) {
                      $q2->where('content', 'like', '%' . $search . '%');
                  });
            });
        }
        
        $threads = $query->orderBy('updated_at', 'desc')
                         ->paginate(20);
        
        // Get unread message count
        $unreadCount = MessageRecipient::where('user_id', $userId)
                                     ->where('status', 'unread')
                                     ->count();
        
        $statuses = [
            'all' => 'All Messages',
            'unread' => 'Unread',
            'read' => 'Read',
            'archived' => 'Archived'
        ];
        
        return view('messages.index', compact('threads', 'unreadCount', 'statuses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $members = Member::where('is_active', true)
                        ->orderBy('last_name')
                        ->get();
        
        // If replying to a thread, get the thread details
        $replyToThread = null;
        if ($request->has('reply_to') && $request->reply_to) {
            $replyToThread = MessageThread::with(['participants'])
                                        ->findOrFail($request->reply_to);
            
            // Check if the current user is a participant in the thread
            $isParticipant = $replyToThread->participants->contains('id', Auth::id());
            if (!$isParticipant) {
                return redirect()->route('messages.index')
                                 ->with('error', 'You do not have permission to reply to this thread.');
            }
        }
        
        return view('messages.create', compact('members', 'replyToThread'));
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
            'recipient_ids' => 'required|array',
            'recipient_ids.*' => 'exists:members,id',
            'thread_id' => 'nullable|exists:message_threads,id',
        ]);
        
        // Start a database transaction
        DB::beginTransaction();
        
        try {
            // If thread_id is provided, add a message to an existing thread
            if ($request->has('thread_id') && $request->thread_id) {
                $thread = MessageThread::findOrFail($validated['thread_id']);
                
                // Check if the current user is a participant in the thread
                $isParticipant = $thread->participants->contains('id', Auth::id());
                if (!$isParticipant) {
                    return redirect()->route('messages.index')
                                     ->with('error', 'You do not have permission to reply to this thread.');
                }
                
                // Create the new message
                $message = Message::create([
                    'thread_id' => $thread->id,
                    'sender_id' => Auth::id(),
                    'content' => $validated['content'],
                ]);
                
                // Update the thread's updated_at timestamp
                $thread->touch();
                
                // Mark the thread as unread for all participants except the sender
                foreach ($thread->participants as $participant) {
                    if ($participant->id !== Auth::id()) {
                        MessageRecipient::updateOrCreate(
                            ['thread_id' => $thread->id, 'user_id' => $participant->id],
                            ['status' => 'unread', 'updated_at' => now()]
                        );
                    }
                }
            } else {
                // Create a new thread
                $thread = MessageThread::create([
                    'subject' => $validated['subject'],
                    'created_by' => Auth::id(),
                ]);
                
                // Create the first message in the thread
                $message = Message::create([
                    'thread_id' => $thread->id,
                    'sender_id' => Auth::id(),
                    'content' => $validated['content'],
                ]);
                
                // Add recipients to the thread
                $recipientIds = array_unique(array_merge($validated['recipient_ids'], [Auth::id()]));
                
                foreach ($recipientIds as $recipientId) {
                    // Add the recipient to the thread participants
                    $thread->participants()->attach($recipientId);
                    
                    // Create a message recipient record
                    MessageRecipient::create([
                        'thread_id' => $thread->id,
                        'user_id' => $recipientId,
                        'status' => $recipientId === Auth::id() ? 'read' : 'unread',
                    ]);
                }
            }
            
            // Commit the transaction
            DB::commit();
            
            return redirect()->route('messages.show', $thread->id)
                             ->with('success', 'Message sent successfully.');
        } catch (\Exception $e) {
            // Roll back the transaction in case of an error
            DB::rollBack();
            
            return redirect()->back()
                             ->withInput()
                             ->with('error', 'Failed to send message: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $thread = MessageThread::with(['messages.sender', 'participants'])
                              ->findOrFail($id);
        
        // Check if the current user is a participant in the thread
        $isParticipant = $thread->participants->contains('id', Auth::id());
        if (!$isParticipant) {
            return redirect()->route('messages.index')
                             ->with('error', 'You do not have permission to view this thread.');
        }
        
        // Mark the thread as read for the current user
        MessageRecipient::updateOrCreate(
            ['thread_id' => $thread->id, 'user_id' => Auth::id()],
            ['status' => 'read', 'updated_at' => now()]
        );
        
        return view('messages.show', compact('thread'));
    }

    /**
     * Mark a message thread as archived.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function archive($id)
    {
        $thread = MessageThread::findOrFail($id);
        
        // Check if the current user is a participant in the thread
        $isParticipant = $thread->participants->contains('id', Auth::id());
        if (!$isParticipant) {
            return redirect()->route('messages.index')
                             ->with('error', 'You do not have permission to archive this thread.');
        }
        
        // Mark the thread as archived for the current user
        MessageRecipient::updateOrCreate(
            ['thread_id' => $thread->id, 'user_id' => Auth::id()],
            ['status' => 'archived', 'updated_at' => now()]
        );
        
        return redirect()->route('messages.index')
                         ->with('success', 'Message thread archived successfully.');
    }

    /**
     * Mark a message thread as unarchived (move back to inbox).
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function unarchive($id)
    {
        $thread = MessageThread::findOrFail($id);
        
        // Check if the current user is a participant in the thread
        $isParticipant = $thread->participants->contains('id', Auth::id());
        if (!$isParticipant) {
            return redirect()->route('messages.index')
                             ->with('error', 'You do not have permission to unarchive this thread.');
        }
        
        // Mark the thread as read for the current user
        MessageRecipient::updateOrCreate(
            ['thread_id' => $thread->id, 'user_id' => Auth::id()],
            ['status' => 'read', 'updated_at' => now()]
        );
        
        return redirect()->route('messages.index')
                         ->with('success', 'Message thread moved to inbox.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $thread = MessageThread::findOrFail($id);
        
        // Check if the current user is a participant in the thread
        $isParticipant = $thread->participants->contains('id', Auth::id());
        if (!$isParticipant) {
            return redirect()->route('messages.index')
                             ->with('error', 'You do not have permission to delete this thread.');
        }
        
        // Remove the current user from the thread participants
        $thread->participants()->detach(Auth::id());
        
        // Delete the recipient record for the current user
        MessageRecipient::where('thread_id', $thread->id)
                       ->where('user_id', Auth::id())
                       ->delete();
        
        // If there are no more participants, delete the thread and its messages
        if ($thread->participants()->count() === 0) {
            $thread->messages()->delete();
            $thread->delete();
        }
        
        return redirect()->route('messages.index')
                         ->with('success', 'Message thread deleted successfully.');
    }
    
    /**
     * Display the user's archived messages.
     *
     * @return \Illuminate\Http\Response
     */
    public function archived()
    {
        $userId = Auth::id();
        
        $threads = MessageThread::whereHas('recipients', function($q) use ($userId) {
                                $q->where('user_id', $userId)
                                  ->where('status', 'archived');
                            })
                            ->with(['latestMessage', 'participants'])
                            ->orderBy('updated_at', 'desc')
                            ->paginate(20);
        
        return view('messages.archived', compact('threads'));
    }
    
    /**
     * Add a participant to a thread.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function addParticipant(Request $request, $id)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:members,id',
        ]);
        
        $thread = MessageThread::findOrFail($id);
        
        // Check if the current user is a participant in the thread
        $isParticipant = $thread->participants->contains('id', Auth::id());
        if (!$isParticipant) {
            return redirect()->route('messages.index')
                             ->with('error', 'You do not have permission to add participants to this thread.');
        }
        
        // Check if the member is already a participant
        $alreadyParticipant = $thread->participants->contains('id', $validated['user_id']);
        if ($alreadyParticipant) {
            return redirect()->route('messages.show', $thread->id)
                             ->with('info', 'This member is already a participant in the conversation.');
        }
        
        // Add the new participant
        $thread->participants()->attach($validated['user_id']);
        
        // Create a message recipient record
        MessageRecipient::create([
            'thread_id' => $thread->id,
            'user_id' => $validated['user_id'],
            'status' => 'unread',
        ]);
        
        // Add a system message to the thread
        $newMember = Member::find($validated['user_id']);
        $message = Message::create([
            'thread_id' => $thread->id,
            'sender_id' => Auth::id(),
            'content' => Auth::user()->name . ' added ' . $newMember->full_name . ' to the conversation.',
            'is_system_message' => true,
        ]);
        
        return redirect()->route('messages.show', $thread->id)
                         ->with('success', 'Participant added successfully.');
    }
    
    /**
     * Leave a thread (remove self as participant).
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function leave($id)
    {
        $thread = MessageThread::findOrFail($id);
        
        // Check if the current user is a participant in the thread
        $isParticipant = $thread->participants->contains('id', Auth::id());
        if (!$isParticipant) {
            return redirect()->route('messages.index')
                             ->with('error', 'You are not a participant in this thread.');
        }
        
        // Remove the current user from the thread participants
        $thread->participants()->detach(Auth::id());
        
        // Delete the recipient record for the current user
        MessageRecipient::where('thread_id', $thread->id)
                       ->where('user_id', Auth::id())
                       ->delete();
        
        // Add a system message to the thread
        $message = Message::create([
            'thread_id' => $thread->id,
            'sender_id' => Auth::id(),
            'content' => Auth::user()->name . ' left the conversation.',
            'is_system_message' => true,
        ]);
        
        // If there are no more participants, delete the thread and its messages
        if ($thread->participants()->count() === 0) {
            $thread->messages()->delete();
            $thread->delete();
        }
        
        return redirect()->route('messages.index')
                         ->with('success', 'You have left the conversation.');
    }
}
