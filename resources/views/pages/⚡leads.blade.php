<?php

use Livewire\Component;
use Flux\Flux;

new class extends Component
{

    public $sortBy = 'created_at';
    public $sortDirection = 'desc';
    public $page = 0;

    public $name;
    public $email;
    public $company;
    public $phone;
    public $website;
    public $template_id = 1;
    public $notes;

    //list of templates for select
    public $templates;

    public function mount()
    {
        $this -> templates = App\Models\Templates::all();
    }

    #[\Livewire\Attributes\Computed]
    public function leads()
    {
        return \App\Models\Leads::orderBy($this->sortBy, $this->sortDirection)
            ->paginate(20, ['*'], 'page', $this->page);
    }

    public function gotoPage($page)
    {
        $this->page = $page;
    }
    public function nextPage()
    {
        $this->page++;
    }
    public function previousPage()
    {
        $this->page--;
    }

    public function createLead(){

        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:leads,email',
            'company' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'website' => 'nullable|max:255',
            'template_id' => 'nullable|exists:templates,id',
            'notes' => 'nullable|string',
        ]);

        \App\Models\Leads::create([
            'name' => $this->name,
            'email' => $this->email,
            'company' => $this->company,
            'phone' => $this->phone,
            'website' => $this->website,
            'template_id' => $this->template_id,
            'notes' => $this->notes,
        ]);

        $this->reset(['name', 'email', 'company', 'phone', 'website', 'template_id', 'notes']);
        Flux::toast('New lead has been created.');
    }
}
?>
<div>
    <flux:heading size="xl" class="mt-8">{{ __('Leads') }}</flux:heading>
    <flux:separator class="mb-4" />

    <flux:table class="w-full" :paginate="$this->leads">
        <flux:table.columns>
            <flux:table.row>
                <flux:table.column>{{ __('Name') }}</flux:table.column>
                <flux:table.column>{{ __('Email') }}</flux:table.column>
                <flux:table.column>{{ __('Company') }}</flux:table.column>
                <flux:table.column>{{ __('Phone') }}</flux:table.column>
                <flux:table.column>{{ __('Website') }}</flux:table.column>
                <flux:table.column>{{ __('Status') }}</flux:table.column>
                <flux:table.column>{{ __('Email send') }}</flux:table.column>
                <flux:table.column></flux:table.column>
            </flux:table.row>
        </flux:table.columns>
         <flux:table.rows>
            @foreach ($this->leads as $lead)
                <flux:table.row>
                    <flux:table.cell>{{ $lead->name }}</flux:table.cell>
                    <flux:table.cell>{{ $lead->email }}</flux:table.cell>
                    <flux:table.cell>{{ $lead->company }}</flux:table.cell>
                    <flux:table.cell>{{ $lead->phone }}</flux:table.cell>
                    <flux:table.cell>{{ $lead->website }}</flux:table.cell>
                    <flux:table.cell>{{ $lead->status }}</flux:table.cell>
                    <flux:table.cell>{{ $lead->email_send }}</flux:table.cell>
                    <flux:table.cell>
                        <flux:button :href="route('leads.view', ['id' => $lead->id])" variant="primary" size="sm" wire:navigate>
                            {{ __('View') }}
                        </flux:button>
                    </flux:table.cell>
                </flux:table.row>
            @endforeach
         </flux:table.rows>
    </flux:table>

    <flux:heading size="xl" class="mt-8">{{ __('Add new lead') }}</flux:heading>
    <flux:separator class="mb-4" />
    <form wire:submit.prevent="createLead">
        <flux:input wire:model="company" label="{{ __('Company') }}" class="mb-4" />
        <flux:input wire:model="name" label="{{ __('Name') }}" class="mb-4" />
        <flux:input wire:model="email" label="{{ __('Email') }}" class="mb-4" required />
        <flux:input wire:model="phone" label="{{ __('Phone') }}" class="mb-4" />
        <flux:input wire:model="website" label="{{ __('Website') }}" class="mb-4" />
        <flux:select wire:model="template_id" label="{{ __('Template') }}" class="mb-4">
            @foreach($this->templates as $temp)
                <flux:select.option :value="$temp->id">{{ $temp->name }}</flux:select.option>
            @endforeach
        </flux:select>
        <flux:textarea wire:model="notes" label="{{ __('Notes') }}" class="mb-4" />
        <flux:button type="submit" variant="primary">{{ __('Create Lead') }}</flux:button>
    </form>
    <flux:toast />
</div>
