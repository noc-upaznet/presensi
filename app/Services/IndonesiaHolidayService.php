<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class IndonesiaHolidayService
{
    protected $baseUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('services.api_co_id.base_url', env('API_CO_ID_BASE_URL'));
        $this->apiKey  = config('services.api_co_id.key', env('API_CO_ID_KEY'));
    }

    /**
     * Ambil daftar libur nasional Indonesia untuk tahun tertentu.
     */
    public function getHolidaysByYear(int $year): array
    {
        if (!$this->apiKey) {
            return [];
        }

        $response = Http::withHeaders([
            'x-api-co-id' => env('API_CO_ID_KEY'),
        ])->withOptions([
            'verify' => false,
        ])->get('https://use.api.co.id/holidays/indonesia', [
            'year' => $year,
        ]);

        if ($response->failed()) {
            return [];
        }

        // Sesuaikan dengan struktur respons API mereka
        $data = $response->json();

        // Misal responsnya: { "is_success": true, "data": [ { "date": "2025-01-01", "name": "Tahun Baru" }, ... ] }
        $holidays = [];

        if (isset($data['data']) && is_array($data['data'])) {
            foreach ($data['data'] as $item) {
                if (!empty($item['date'])) {
                    $holidays[$item['date']] = $item;
                }
            }
        }

        return $holidays; // key: 'Y-m-d'
    }

    /**
     * Ambil libur satu bulan (filter dari data satu tahun).
     */
    public function getHolidaysByMonth(string $bulanTahun): array
    {
        [$tahun, $bulan] = explode('-', $bulanTahun);

        $all = $this->getHolidaysByYear((int) $tahun);

        $monthly = [];
        foreach ($all as $date => $item) {
            $carbon = Carbon::parse($date);
            if ((int) $carbon->format('m') === (int) $bulan) {
                // key: tanggal (1–31)
                $day = (int) $carbon->format('d');
                $monthly[$day] = $item;
            }
        }

        return $monthly;
    }
}
