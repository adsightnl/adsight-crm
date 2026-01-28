<?php

use Livewire\Component;

new class extends Component
{
    public $open_send;
    public $open;

    public function mount()
    {
        $this->open_send = App\Models\Leads::where('status', 'new')
            ->whereNotNull('email')
            ->count();
        $this->open = App\Models\Leads::where('status', 'new')->count();
    }
};
?>

<div>
    <h2 class="text-lg font-semibold">Leads Summary</h2>
    <div class="mt-4 space-y-2">
        <div class="flex items-center justify-between">
            <span>Open Leads with Email:</span>
            <span class="font-bold">{{ $open_send }}</span>
        </div>
        <div class="flex items-center justify-between">
            <span>Total Open Leads:</span>
            <span class="font-bold">{{ $open }}</span>
        </div>
    </div>
</div>
