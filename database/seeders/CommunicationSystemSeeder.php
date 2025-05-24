<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Member;
use App\Models\SmsTemplate;
use App\Models\SmsGroup;
use App\Models\SmsMessage;
use App\Models\SmsMessageRecipient;
use App\Models\PrayerRequest;
use App\Models\PrayerResponse;
use App\Models\MessageThread;
use App\Models\Message;

class CommunicationSystemSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin users
        $admin = User::factory()->create([
            'name' => 'Church Admin',
            'email' => 'admin@church.com',
            'password' => bcrypt('password'),
        ]);

        // Create regular users
        $users = User::factory()->count(5)->create();
        $allUsers = $users->push($admin);

        // Create members
        $members = Member::factory()->count(20)->create();

        // Create SMS Templates
        $templates = SmsTemplate::factory()
            ->count(5)
            ->state(fn (array $attributes) => ['created_by' => $admin->id])
            ->create();

        // Create SMS Groups
        $groups = SmsGroup::factory()
            ->count(3)
            ->state(fn (array $attributes) => ['created_by' => $admin->id])
            ->create();

        // Assign members to groups
        foreach ($groups as $group) {
            $groupMembers = $members->random(rand(5, 10));
            foreach ($groupMembers as $member) {
                $group->members()->attach($member->id, ['created_at' => now(), 'updated_at' => now()]);
            }
        }

        // Create SMS Messages
        $messages = collect();
        foreach ($allUsers as $user) {
            // Draft messages
            $messages->push(
                SmsMessage::factory()
                    ->draft()
                    ->count(2)
                    ->state(fn (array $attributes) => [
                        'sender_id' => $user->id,
                        'template_id' => $templates->random()->id,
                        'group_id' => $groups->random()->id,
                    ])
                    ->create()
            );

            // Sent messages
            $messages->push(
                SmsMessage::factory()
                    ->sent()
                    ->count(3)
                    ->state(fn (array $attributes) => [
                        'sender_id' => $user->id,
                        'template_id' => $templates->random()->id,
                        'group_id' => $groups->random()->id,
                    ])
                    ->create()
            );

            // Scheduled messages
            $messages->push(
                SmsMessage::factory()
                    ->scheduled()
                    ->count(2)
                    ->state(fn (array $attributes) => [
                        'sender_id' => $user->id,
                        'template_id' => $templates->random()->id,
                        'group_id' => $groups->random()->id,
                    ])
                    ->create()
            );
        }

        // Create message recipients
        foreach ($messages->flatten() as $message) {
            if ($message->group_id) {
                $recipients = $message->group->members;
            } else {
                $recipients = $members->random(rand(1, 5));
            }

            foreach ($recipients as $recipient) {
                if ($message->status === 'sent') {
                    SmsMessageRecipient::factory()
                        ->delivered()
                        ->state(fn (array $attributes) => [
                            'message_id' => $message->id,
                            'member_id' => $recipient->id,
                            'phone_number' => $recipient->phone,
                        ])
                        ->create();
                } else {
                    SmsMessageRecipient::factory()
                        ->pending()
                        ->state(fn (array $attributes) => [
                            'message_id' => $message->id,
                            'member_id' => $recipient->id,
                            'phone_number' => $recipient->phone,
                        ])
                        ->create();
                }
            }
        }

        // Create Prayer Requests
        foreach ($members as $member) {
            // Create some prayer requests
            $requests = PrayerRequest::factory()
                ->count(rand(1, 3))
                ->state(fn (array $attributes) => ['member_id' => $member->id])
                ->create();

            // Add responses to each request
            foreach ($requests as $request) {
                PrayerResponse::factory()
                    ->count(rand(2, 5))
                    ->state(fn (array $attributes) => [
                        'prayer_request_id' => $request->id,
                        'member_id' => $members->except($member->id)->random()->id,
                    ])
                    ->create();
            }
        }

        // Create some anonymous prayer requests
        PrayerRequest::factory()
            ->count(5)
            ->anonymous()
            ->create();

        // Create Message Threads
        foreach ($allUsers as $user) {
            // Create threads started by this user
            $threads = MessageThread::factory()
                ->count(3)
                ->state(fn (array $attributes) => ['created_by' => $user->id])
                ->create();

            // Add participants and messages to each thread
            foreach ($threads as $thread) {
                // Add 2-4 participants
                $participants = $allUsers->except($user->id)->random(rand(1, 3));
                foreach ($participants as $participant) {
                    $thread->participants()->attach($participant->id, [
                        'last_read_at' => now(),
                        'is_muted' => false,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                // Add the thread creator as participant
                $thread->participants()->attach($user->id, [
                    'last_read_at' => now(),
                    'is_muted' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Add messages to the thread
                Message::factory()
                    ->count(rand(5, 15))
                    ->state(fn (array $attributes) => [
                        'thread_id' => $thread->id,
                        'sender_id' => $participants->push($user)->random()->id,
                    ])
                    ->create();

                // Add some system messages
                Message::factory()
                    ->system()
                    ->count(rand(1, 3))
                    ->state(fn (array $attributes) => [
                        'thread_id' => $thread->id,
                        'sender_id' => $user->id,
                    ])
                    ->create();
            }
        }
    }
}
