<?php

use Livewire\Component;
use Flux\Flux;

new class extends Component
{
    public $name;

    public function mount($id)
    {
        $template = \App\Models\Templates::find($id);
        $this->name = $template->name;
    }

    public function editTemplate()
    {
        $this->validate([
            'name' => 'required|string|max:255',
        ]);

        $template = \App\Models\Templates::where('name', $this->name)->first();
        $template->update([
            'name' => $this->name,
        ]);

         Flux::toast('Your changes have been saved.');
    }
};
?>
<div>
    <flux:toast />
    <flux:heading size="xl" class="mt-8">{{ __('Edit Template') }}</flux:heading>
    <flux:separator class="mb-4" />
    <form wire:submit.prevent="editTemplate">
        <flux:input wire:model="name" label="{{ __('Template Name') }}" class="mb-4" required />
        <flux:button type="submit" variant="primary">{{ __('Update Template') }}</flux:button>
    </form>
</div>
