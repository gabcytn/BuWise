<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('task.{taskId}', function ($user, $taskId) {
    return true;
});

Broadcast::channel('App.Models.User.{userId}', function ($user, $userId) {
    return $user->id === $userId;
});
