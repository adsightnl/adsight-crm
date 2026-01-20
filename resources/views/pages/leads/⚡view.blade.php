<?php

use Livewire\Component;

new class extends Component
{
    public $name;
    public $company;
    public $email;
    public $phone;
    public $website;
    public $template;
    public $notes;

    public function mount($id)
    {
        $lead = \App\Models\Leads::find($id);
        $this->name = $lead->name;
        $this->company = $lead->company;
        $this->email = $lead->email;
        $this->phone = $lead->phone;
        $this->website = $lead->website;
        $this->template = $lead->template_id;
        $this->notes = $lead->notes;
    }

    public function updateLead()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:leads,email',
            'company' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'website' => 'nullable|url|max:255',
            'template' => 'nullable|exists:templates,id',
            'notes' => 'nullable|string',
        ]);

        $lead = \App\Models\Leads::where('email', $this->email)->first();
        $lead->update([
            'name' => $this->name,
            'company' => $this->company,
            'phone' => $this->phone,
            'website' => $this->website,
            'template_id' => $this->template,
            'notes' => $this->notes,
        ]);

        Flux::toast('Lead has been updated.');
    }

};
?>

<div>
    <flux:toast />
    <flux:heading size="xl" class="mt-8">{{ __('Lead :company / :website', ['company' => $this->company, 'website' => str_replace(['https://', 'http://'], '', $this->website)]) }}</flux:heading>
    <flux:separator class="mb-4" />
    <form wire:submit.prevent="updateLead">
        <flux:input wire:model="name" label="{{ __('Name') }}" class="mb-4" required />
        <flux:input wire:model="company" label="{{ __('Company') }}" class="mb-4" />
        <flux:input wire:model="email" label="{{ __('Email') }}" class="mb-4" required />
        <flux:input wire:model="phone" label="{{ __('Phone') }}" class="mb-4" />
        <flux:input wire:model="website" label="{{ __('Website') }}" class="mb-4" />
        <flux:select wire:model="status" label="{{ __('Status')}}" class="mb-4" required>
            <flux:select.option value="new">{{ __('New') }}</flux:select.option>
            <flux:select.option value="contacted">{{ __('Contacted') }}</flux:select.option>
            <flux:select.option value="qualified">{{ __('Qualified') }}</flux:select.option>
            <flux:select.option value="lost">{{ __('Lost') }}</flux:select.option>
        </flux:select>
        <flux:textarea wire:model="notes" label="{{ __('Notes') }}" class="mb-4" />
        <flux:button type="submit" variant="primary">{{ __('Update Lead') }}</flux:button>
    </form>
</div>
