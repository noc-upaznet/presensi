<?php

namespace App\Livewire;

use Livewire\Component;

class MapFix extends Component
{
    public $latitude;
    public $longitude;

    // protected $listeners = ['global:updateCoordinates' => 'setCoordinates'];

    public function setCoordinates($data)
    {
        if (!isset($data['lat']) || !isset($data['lng'])) {
            logger('Data koordinat tidak valid', $data);
            return;
        }
        // dd($data);
        $this->latitude = $data['lat'];
        $this->longitude = $data['lng'];
    }

    public function searchCoordinates()
    {
        if (is_numeric($this->latitude) && is_numeric($this->longitude)) {
            $this->dispatch('map:move-to-coordinates', (object) [
                'lat' => (float) $this->latitude,
                'lng' => (float) $this->longitude,
            ]);
        } else {
            logger('Latitude dan longitude tidak valid', [
                'lat' => $this->latitude,
                'lng' => $this->longitude,
            ]);
        }
    }  

    public function render()
    {
        return view('livewire.map-fix');
    }
}