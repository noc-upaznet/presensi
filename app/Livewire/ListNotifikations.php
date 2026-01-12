<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ListNotifikations extends Component
{
    public function markAsRead($id)
    {
        Auth::user()
            ->notifications()
            ->where('id', $id)
            ->first()
            ?->markAsRead();
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
    }

    public function render()
    {
        return view('livewire.list-notifikations', [
            'notifications' => Auth::user()
                ->notifications()
                ->latest()
                ->paginate(10),
        ]);
    }
}
