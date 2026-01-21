<?php

use Livewire\Component;

new class extends Component
{
    public $email;
    public $website;
    public $status;
    public $notes;

    public function mount($id)
    {
        $lead = \App\Models\Leads::find($id);
        $this->email = $lead->email;
        $this->website = $lead->website;
        $this -> status = $lead->status;
        $this->notes = $lead->notes;
    }

    public function updateLead()
    {
        $this->validate([
            'email' => 'nullable|email',
            'website' => 'max:255|required|url',
            'status' => 'required|in:new,broken,inactive,contacted,qualified,lost,traffic',
            'notes' => 'nullable|string',
        ]);

        $lead = \App\Models\Leads::where('email', $this->email)->first();
        $lead->update([
            'email' => $this->email,
            'website' => $this->website,
            'status' => $this->status,
            'notes' => $this->notes,
        ]);

        Flux::toast('Lead has been updated.');
    }

};
?>

<div>
    <flux:toast />
    <flux:heading size="xl" class="mt-8">{{ __('Lead :website', ['website' => str_replace(['https://', 'http://', '/', 'www.'], '', $this->website)]) }}</flux:heading>
    <flux:separator class="mb-4" />
    <form wire:submit.prevent="updateLead">
        <flux:button href="{{ $website }}" variant="primary" class="mb-6" target="_blank">Open Website</flux:button>

        <flux:input wire:model="email" label="{{ __('Email') }}" class="mb-4" />
        <flux:input wire:model="website" label="{{ __('Website') }}" class="mb-4" required />
        <flux:select wire:model="status" label="{{ __('Status')}}" class="mb-4" required>
            <flux:select.option value="new">{{ __('New') }}</flux:select.option>
            <flux:select.option value="traffic">{{ __('Low Traffic') }}</flux:select.option>
            <flux:select.option value="broken">{{ __('Broken') }}</flux:select.option>
            <flux:select.option value="inactive">{{ __('Inactive') }}</flux:select.option>
            <flux:select.option value="lost">{{ __('No interest') }}</flux:select.option>
            <flux:select.option value="contacted">{{ __('Contacted') }}</flux:select.option>
            <flux:select.option value="qualified">{{ __('Interested') }}</flux:select.option>


        </flux:select>
        <flux:textarea wire:model="notes" label="{{ __('Notes') }}" class="mb-4" />
        <flux:button type="submit" variant="primary">{{ __('Update Lead') }}</flux:button>
    </form>
</div>
