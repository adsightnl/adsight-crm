<?php

use Livewire\Component;
use Flux\Flux;

new class extends Component
{

    public $sortBy = 'email_send';
    public $sortDirection = 'asc';
    public $page = 0;

    public $email;
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

     public function sort($column) {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }

    public function createLead(){

        $this->validate([
            'email' => 'required|email|unique:leads,email',
            'website' => 'required|max:255|url',
            'template_id' => 'nullable|exists:templates,id',
            'notes' => 'nullable|string',
        ]);

        \App\Models\Leads::create([
            'email' => $this->email,
            'website' => $this->website,
            'template_id' => $this->template_id,
            'notes' => $this->notes,
        ]);

        $this->reset(['email', 'website', 'template_id', 'notes']);
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
                <flux:table.column sortable :sorted="$sortBy === 'email'" :direction="$sortDirection" wire:click="sort('email')">{{ __('Email') }} </flux:table.column>
                <flux:table.column sortable :sorted="$sortBy === 'website'" :direction="$sortDirection" wire:click="sort('website')">{{ __('Website') }}</flux:table.column>
                <flux:table.column sortable :sorted="$sortBy === 'status'" :direction="$sortDirection" wire:click="sort('status')">{{ __('Status') }}</flux:table.column>
                <flux:table.column sortable :sorted="$sortBy === 'email_send'" :direction="$sortDirection" wire:click="sort('email_send')">{{ __('Email send') }}</flux:table.column>
                <flux:table.column></flux:table.column>
            </flux:table.row>
        </flux:table.columns>
         <flux:table.rows>
            @foreach ($this->leads as $lead)
                <flux:table.row>
                    <flux:table.cell>{{ $lead->email }}</flux:table.cell>
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
        <flux:input wire:model="email" label="{{ __('Email') }}" class="mb-4" required />
        <flux:input wire:model="website" label="{{ __('Website') }}" class="mb-4" required />
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
