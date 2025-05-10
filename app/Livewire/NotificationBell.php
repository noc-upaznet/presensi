<?php

namespace App\Livewire;

use Livewire\Component;
use App\Events\NewNotificationEvent;

class NotificationBell extends Component
{
    public $notifications = [];

    protected $listeners = ['echo:notifications,new-notification' => 'handleNotification'];

    public function sendNotification()
    {
        broadcast(new NewNotificationEvent("Notifikasi dari Livewire!"));
    }

    public function handleNotification($payload)
    {
        $this->notifications[] = $payload['message'];
    }

    public function render()
    {
        return view('livewire.notification-bell');
    }
}
