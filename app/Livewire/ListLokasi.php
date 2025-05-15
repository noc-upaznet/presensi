<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Lokasi;

class ListLokasi extends Component
{
    public $nama_lokasi;
    public $koordinat;
    public $alamat;
    public $status = true;
    public $lokasi_list;
    public $lokasi_id;

    public function mount()
    {
        // Load lokasi saat komponen pertama kali di-mount
        $this->lokasi_list = Lokasi::all();
    }

    public function simpanLokasi()
    {
        // Simpan data lokasi ke database
        Lokasi::create([
            'nama_lokasi' => $this->nama_lokasi,
            'koordinat' => $this->koordinat,
            'alamat' => $this->alamat,
            'status' => $this->status,
        ]);

        // Reset input
        $this->reset(['nama_lokasi', 'koordinat', 'alamat', 'status']);

        // Refresh data lokasi
        $this->lokasi_list = Lokasi::all();

        // Kirim notifikasi (opsional)
        session()->flash('message', 'Lokasi berhasil ditambahkan.');
    }

    public function confirmHapusLokasi($id)
    {
        $lokasi = Lokasi::find($id);
        $this->lokasi_id = $lokasi->id;
        $this->nama_lokasi = $lokasi->nama;
    }

    public function deleteLokasi()
    {
        Lokasi::find($this->lokasi_id)->delete();
        $this->lokasi_list = Lokasi::all();
        // Notifikasi
        $this->dispatch('lokasiTerhapus');

        // Reset data setelah penghapusan
        $this->lokasi_id = null;
        $this->nama_lokasi = null;
    }

    public function editLokasi($id)
    {
        $lokasi = Lokasi::findOrFail($id);
        $this->lokasi_id = $lokasi->id;
        $this->nama_lokasi = $lokasi->nama_lokasi;
        $this->koordinat = $lokasi->koordinat;
        $this->alamat = $lokasi->alamat;
        $this->status = (bool) $lokasi->status;
    }

    public function updateLokasi()
    {
        // Validasi data
        $this->validate([
            'nama_lokasi' => 'required|string',
            'koordinat' => 'required|string',
            'alamat' => 'required|string',
            'status' => 'boolean',
        ]);

        // Update lokasi
        $lokasi = Lokasi::findOrFail($this->lokasi_id);
        $lokasi->nama_lokasi = $this->nama_lokasi;
        $lokasi->koordinat = $this->koordinat;
        $lokasi->alamat = $this->alamat;
        $lokasi->status = (bool) $this->status;
        $lokasi->save();

        // Reset form
        $this->resetInput();
        // Refresh tabel secara otomatis

        $this->render();

        // Emit event untuk menutup modal dan refresh data tabel
        $this->dispatch('lokasiTerupdate', ['message' => 'Lokasi berhasil diperbarui!']);
        $this->dispatch('closeModal');
    }



    private function resetInput()
    {
        $this->nama_lokasi = '';
        $this->koordinat = '';
        $this->alamat = '';
        $this->status = false;
        $this->lokasi_id = null;
    }

    public function render()
    {
        $lokasis = Lokasi::all();
        return view('livewire.list-lokasi', compact('lokasis'));
    }
}
