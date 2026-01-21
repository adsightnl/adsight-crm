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
        return \App\Models\Leads::where('status', 'new')->whereNull('email_send')->orderBy($this->sortBy, $this->sortDirection)
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
    <flux:toast />
</div>
