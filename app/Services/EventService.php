<?php

namespace App\Services;

use App\Models\EventMemberRole;

class EventService
{
    public function inviteToEvent($eventId, $userId, $role, $divisiId, $assignedBy)
    {
        $exists = EventMemberRole::where([
            'id_event' => $eventId,
            'id_user' => $userId
        ])->exists();

        if ($exists) {
            throw new \Exception('User already in event');
        }

        return EventMemberRole::create([
            'id_event' => $eventId,
            'id_user' => $userId,
            'role_event' => $role,
            'id_divisi' => $divisiId,
            'assigned_by' => $assignedBy,
            'assigned_at' => now()
        ]);
    }
}