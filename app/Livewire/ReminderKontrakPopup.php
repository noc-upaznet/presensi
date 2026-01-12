<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\M_DataKaryawan;
use App\Models\User;
use App\Notifications\NotifKontrak;
use Carbon\Carbon;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;

class ReminderKontrakPopup extends Component
{
    public $reminderHtml = '';
    // public function mount()
    // {
    //     if (auth()->user()->hasRole('admin')) {
    //         $this->showReminderHR();
    //     } elseif (auth()->user()->hasRole('user')) {
    //         $this->showReminderKaryawan();
    //     }
    // }

    public function initReminder()
    {
        $user = auth()->user();

        // ADMIN → HR reminder
        if ($user->hasRole('admin')) {
            $this->showReminderHR();
            return;
        }

        // Ambil divisi dari data_karyawan
        $divisi = M_DataKaryawan::where('user_id', $user->id)
            ->value('divisi');

        // DIVISI HR → HR reminder
        if (strtolower($divisi ?? '') === 'hr') {
            $this->showReminderHR();
        } else {
            // USER BIASA → reminder personal
            $this->showReminderKaryawan();
        }
    }

    public function showReminderHR()
    {
        if (session()->has('kontrak_reminder_shown')) {
            return;
        }
        session()->put('kontrak_reminder_shown', true);

        $today = today();
        $oneMonthLater = today()->addMonth();

        $karyawans = M_DataKaryawan::whereIn('status_karyawan', ['Probation', 'PKWT Kontrak'])
            ->whereBetween('tgl_keluar', [$today, $oneMonthLater])
            ->orderBy('tgl_keluar')
            ->get();

        if ($karyawans->isEmpty()) {
            return;
        }

        foreach ($karyawans as $k) {

            $exists = DatabaseNotification::where('notifiable_id', auth()->id())
                ->where('type', NotifKontrak::class)
                ->whereJsonContains('data->karyawan_id', $k->id)
                ->exists();

            if ($exists) {
                continue;
            }

            auth()->user()->notify(new NotifKontrak([
                'type'        => 'kontrak_reminder',
                'karyawan_id' => $k->id,
                'nama'        => $k->nama_karyawan,
                'entitas'     => $k->entitas,
                'status'      => $k->status_karyawan,
                'tgl_keluar'  => $k->tgl_keluar,
                'sisa_hari'   => today()->diffInDays($k->tgl_keluar),
            ]));
        }

        $html  = '<div style="text-align:left;font-size:14px">';
        $html .= '<table style="width:100%;border-collapse:collapse">';
        $html .= '
            <thead>
                <tr>
                    <th style="border-bottom:1px solid #ddd;padding:6px">Nama</th>
                    <th style="border-bottom:1px solid #ddd;padding:6px">Entitas</th>
                    <th style="border-bottom:1px solid #ddd;padding:6px">Status</th>
                    <th style="border-bottom:1px solid #ddd;padding:6px">Habis Kontrak</th>
                </tr>
            </thead>
            <tbody>
        ';

        foreach ($karyawans as $k) {
            $color = strtolower($k->status_karyawan) === 'probation'
                ? '#f59e0b'
                : '#ef4444';

            $html .= '
            <tr>
                <td style="padding:6px 0">' . e($k->nama_karyawan) . '</td>
                <td>' . e($k->entitas) . '</td>
                <td>
                    <span style="
                        background:' . $color . ';
                        color:#fff;
                        padding:2px 8px;
                        border-radius:6px;
                        font-size:12px
                    ">
                        ' . e($k->status_karyawan) . '
                    </span>
                </td>
                <td>' . \Carbon\Carbon::parse($k->tgl_keluar)->format('d M Y') . '</td>
            </tr>
        ';
        }

        $html .= '</tbody></table></div>';

        $this->reminderHtml = $html;

        $this->dispatch('kontrak-reminder-hr');
    }

    public function showReminderKaryawan()
    {
        // if (session()->has('kontrak_reminder_shown')) {
        //     return;
        // }
        // session()->put('kontrak_reminder_shown', true);

        $userId = Auth::id();

        $karyawan = M_DataKaryawan::where('user_id', $userId)
            ->whereIn('status_karyawan', ['Probation', 'PKWT Kontrak'])
            ->whereBetween('tgl_keluar', [today(), today()->addMonth()])
            ->first();

        if (!$karyawan) {
            return;
        }

        $lastNotif = DatabaseNotification::where('notifiable_id', $userId)
            ->where('type', NotifKontrak::class)
            ->whereJsonContains('data->karyawan_id', $karyawan->id)
            ->latest('created_at')
            ->first();

        if ($lastNotif && Carbon::parse($lastNotif->created_at)->diffInDays(now()) < 7) {
            return;
        }

        $exists = DatabaseNotification::where('notifiable_id', $userId)
            ->where('type', NotifKontrak::class)
            ->whereJsonContains('data->karyawan_id', $karyawan->id)
            ->exists();

        if (!$exists) {
            auth()->user()->notify(new NotifKontrak([
                'karyawan_id' => $karyawan->id,
                'nama'        => $karyawan->nama_karyawan,
                'status'      => $karyawan->status_karyawan,
                'tgl_keluar'  => $karyawan->tgl_keluar,
                'sisa_hari'   => today()->diffInDays($karyawan->tgl_keluar),
            ]));
        }

        $this->dispatch('kontrak-reminder');
    }




    public function render()
    {
        return view('livewire.reminder-kontrak-popup');
    }
}
