<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\PersonalJob;
/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
}, ['guards' => ['api']]);

Broadcast::channel('video-share-chat.{personalJobId}', function ($user, $personalJobId) {
    $personalJob = PersonalJob::find($personalJobId);

    if (!$personalJob) {
        return false;
    }

    return [
        'name' => $user->full_name,
        'email' => $user->email,
    ];
}, ['guards' => ['api']]);
