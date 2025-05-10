<div>
    <button wire:click="sendNotification"
        class="bg-blue-500 text-white px-4 py-2 rounded">Kirim Notifikasi</button>

    <div class="mt-4">
        <h2 class="font-bold">Notifikasi Masuk:</h2>
        <ul>
            @foreach($notifications as $note)
                <li>- {{ $note }}</li>
            @endforeach
        </ul>
    </div>
</div>
