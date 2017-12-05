<?php

namespace App\Http\Controllers;

use App\User;

class ProfilesController extends Controller
{
    public function show(User $user)
    {
        $profileUser = $user;

        $activitiesByDate = $this->getActivity($user);

        return view('profiles.show', compact('profileUser', 'activitiesByDate'));
    }

    /**
     * @param User $user
     * @return static
     */
    protected function getActivity(User $user)
    {
        return $user->activity()->latest()
            ->with('subject')
            ->take(50)
            ->get()
            ->groupBy(function ($activity) {
                return $activity->created_at->format('Y-m-d');
            });
    }
}
