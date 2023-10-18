<?php

use Illuminate\Support\Facades\Broadcast;

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

Broadcast::routes(['middleware' => ['web', 'auth']]);

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    $profile = $user->getProfile();
    return !empty($profile) && (int) $profile->id === (int) $id;
});

Broadcast::channel('contacts.{id}', function ($user, $id) {
    $profile = $user->getProfile();
    return !empty($profile) && (int) $profile->id === (int) $id;
});

Broadcast::channel('channel.{channelId}', function ($user, $channelId) {
    $profile = $user->getProfile();

    if(!empty($profile) && $profile->id == $channelId){
        return [
            'id' => $profile->id,
            'name' => $profile->full_name,
            'company' => $profile->organization_name,
            'role' => $profile->role,
            'contacts' => $profile->contacts_list
        ];
    }

    return false;
});

Broadcast::channel('online', function ($user) {
    $profile = $user->getProfile();

    return $profile->id;
});