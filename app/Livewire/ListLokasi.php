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

    public function hapusLokasi($id)
    {
        Lokasi::find($id)->delete();
        $this->lokasi_list = Lokasi::all();
    }

    public function editLokasi($id)
    {
        $lokasi = Lokasi::find($id);
        $this->nama_lokasi = $lokasi->nama_lokasi;
        $this->koordinat = $lokasi->koordinat;
        $this->alamat = $lokasi->alamat;
        $this->status = (bool) $lokasi->status;  
    }

    public function render()
    {
        return view('livewire.list-lokasi');
    }
}
