<?php

namespace Database\Seeders;

use App\Models\Conversation;
use App\Models\ConversationMember;
use App\Models\Organization;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrganizationConversationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            DB::beginTransaction();
            $orgs = Organization::with('members')->get();

            foreach ($orgs as $org) {
                $convo = Conversation::create([]);

                // update current organization's conversation_id
                $org->conversation_id = $convo->id;
                $org->save();

                // add all existing org members in the convo
                foreach ($org->members as $member) {
                    // exclude clients
                    if ($member->user->role_id === Role::CLIENT)
                        continue;
                    ConversationMember::create([
                        'conversation_id' => $convo->id,
                        'user_id' => $member->user_id,
                    ]);
                }
            }

            // get all accountants
            $accountants = User::where('role_id', '=', Role::ACCOUNTANT)->get();
            foreach ($accountants as $accountant) {
                $staff_list = [];
                // create conversation for each accountant's staff
                foreach ($accountant->staff as $staff) {
                    $convo = Conversation::create([]);
                    ConversationMember::insert([
                        ['conversation_id' => $convo->id, 'user_id' => $staff->id],
                        ['conversation_id' => $convo->id, 'user_id' => $accountant->id]
                    ]);

                    // create conversation among each staff
                    foreach ($staff_list as $staff_item) {
                        $convo = Conversation::create([]);
                        ConversationMember::insert([
                            ['conversation_id' => $convo->id, 'user_id' => $staff_item->id],
                            ['conversation_id' => $convo->id, 'user_id' => $staff->id]
                        ]);
                    }
                    $staff_list[] = $staff;
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            Log::info('ROLLBACKED MIGRATION');
        }
    }
}
