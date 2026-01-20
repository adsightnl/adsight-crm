<?php

use Livewire\Component;
use Flux\Flux;

new class extends Component
{
    public $templates;

    public $name;
    public $template;

    public $confirm = null;

    public function createTemplate()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'template' => 'nullable|string',
        ]);

        \App\Models\Templates::create([
            'name' => $this->name,
            'template' => $this->template,
        ]);

        $this->reset(['name', 'template']);
        $this->templates = \App\Models\Templates::all();
        Flux::toast('New template has been created.');
    }

    public function mount()
    {
        $this->templates = \App\Models\Templates::all();
    }

    public function deleteTemplate($id)
    {
        \App\Models\Templates::find($id)->delete();
        $this->templates = \App\Models\Templates::all();
        Flux::toast('Template has been deleted.');
    }

    public function editTemplate($id)
    {
        // Redirect to edit page (not implemented in this snippet)
        return redirect()->route('templates.edit', ['id' => $id]);
    }

};
?>

<div>
    <flux:toast />
    <flux:heading size="xl" class="mt-8">{{ __('Templates') }}</flux:heading>
    <flux:separator class="mb-4" />
    <flux:table class="w-full" >
        <flux:table.columns>
            <flux:table.column>{{__('Name')}}</flux:table.column>
            <flux:table.column>{{__('Date')}}</flux:table.column>
            <flux:table.column>{{__('Actions')}}</flux:table.column>
        </flux:table.columns>
        <flux:table.rows>
            @foreach($this->templates as $template)
                <flux:table.row>
                    <flux:table.cell>{{ $template->name }}</flux:table.cell>
                    <flux:table.cell>{{ $template->created_at->format('Y-m-d') }}</flux:table.cell>
                    <flux:table.cell>
                        <flux:button variant="primary" wire:click="editTemplate({{ $template->id }})">{{ __('Edit') }}</flux:button>
                        <flux:button variant="danger" wire:click="deleteTemplate({{ $template->id }})" onclick="return confirm('{{ __('Are you sure you want to delete this template?') }}')">{{ __('Delete') }}</flux:button>
                    </flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>
    <flux:heading size="xl" class="mt-8">{{ __('Create New Template') }}</flux:heading>
    <flux:separator class="mb-4" />
    <form wire:submit.prevent="createTemplate">
        <flux:input wire:model="name" label="{{ __('Template Name') }}" class="mb-4" required />
        <flux:input wire:model="template" label="{{ __('Template') }}" class="mb-4" />
        <flux:button type="submit" variant="primary">{{ __('Create Template') }}</flux:button>
    </form>
</div>
