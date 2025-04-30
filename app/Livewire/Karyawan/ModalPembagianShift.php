<?php

namespace App\Livewire\Karyawan;

use Livewire\Component;
use App\Models\M_JadwalShift;
use Illuminate\Support\Facades\Crypt;

class ModalPembagianShift extends Component
{

    public $shiftId;
    protected $listeners = ['edit-data' => 'loadTicketData'];

    public $nama_shift = '';
    public $jam_masuk = '';
    public $jam_pulang = '';

    public $jadwals = [
        ['nama_shift' => '', 'jam_masuk' => '', 'jam_pulang' => ''],
    ];

    public function loadTicketData($data)
    {
        // dd($data);
        $this->shiftId = $data['id'];
        $this->nama_shift = $data['nama_shift'];
        $this->jam_masuk = $data['jam_masuk'];
        $this->jam_pulang = $data['jam_pulang'];

    }

    public function saveEdit() {

        $ticket = M_JadwalShift::find($this->shiftId);

        // dd($ticket);
        if (!$ticket) {
            session()->flash('error', 'Data karyawan tidak ditemukan!');
            return;
        }
    
        // foreach ($this->jadwals as $jadwal) {
        //     if (isset($jadwal['id'])) {
        //         // Jika ada id, update data
        //         M_JadwalShift::where('id', $jadwal['id'])->update([
        //             'nama_shift' => $jadwal['nama_shift'],
        //             'jam_masuk' => $jadwal['jam_masuk'],
        //             'jam_pulang' => $jadwal['jam_pulang'],
        //         ]);
        //     } else {
        //         // Jika tidak ada id, buat data baru
        //         M_JadwalShift::create([
        //             'nama_shift' => $jadwal['nama_shift'],
        //             'jam_masuk' => $jadwal['jam_masuk'],
        //             'jam_pulang' => $jadwal['jam_pulang'],
        //         ]);
        //     }
        // }
        $data = [
            'nama_shift' => $this->nama_shift,
            'jam_masuk' => $this->jam_masuk,
            'jam_pulang' => $this->jam_pulang,
        ];

        $ticket->update($data);
    
        // $this->reset('jadwals');
    
        $this->dispatch('swal', params: [
            'title' => 'Data Updated',
            'icon' => 'success',
            'text' => 'Data has been updated successfully'
        ]);

        $this->dispatch('modal-edit-shift', action: 'hide');
        $this->dispatch('refresh');
    }

    public function delete($id)
    {
        $jadwal = M_JadwalShift::findOrFail(Crypt::decrypt($id));
        // dd($jadwal);
        $jadwal->delete();
        $this->dispatch('swal', params: [
            'title' => 'Data Deleted',
            'icon' => 'success',
            'text' => 'Data has been deleted successfully'
        ]);
        $this->dispatch('modal-confirm-delete', action: 'show');
    }
    public function render()
    {
        return view('livewire.karyawan.modal-pembagian-shift');
    }
}
