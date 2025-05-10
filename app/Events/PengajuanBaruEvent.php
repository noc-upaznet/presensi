<?php

namespace App\Events;

use App\Models\M_Pengajuan;
use Illuminate\Support\Facades\Log;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PengajuanBaruEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */

    public $pengajuan;

    public function __construct(M_Pengajuan $pengajuan)
    {
        $this->pengajuan = [
            'karyawan_id' => $pengajuan->getKaryawan->nama_karyawan,
            'shift_id' => $pengajuan->getShift->nama_shift,
            'created_at' => $pengajuan->created_at,
            'socket' => request()->socketId,
        ];
        // dd($this->pengajuan);
        
        // Log::info('Pengajuan Baru Event Dipancarkan:', $pengajuan->toArray());
    }

    public function broadcastOn()
    {
        return new Channel('pengajuan');
    }

    public function broadcastAs()
    {
        return 'pengajuan-baru';
    }

    public function broadcastWith()
    {
        return $this->pengajuan;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    // public function broadcastOn(): array
    // {
    //     return [
    //         new PrivateChannel('channel-name'),
    //     ];
    // }
}
