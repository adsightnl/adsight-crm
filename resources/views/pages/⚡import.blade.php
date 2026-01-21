<?php

use Livewire\Component;
use Livewire\WithFileUploads;

new class extends Component
{
    use WithFileUploads;
    public $import = [];

    #[Validate('max:10240')] // 10MB Max

    function removeFile($index)
    {
        $file = $this->import[$index];
        $file -> delete();
        unset($this->import[$index]);
    }

    public function submit(){
        foreach($this->import as $file){
            // Process each CSV file
            $path = $file->getRealPath();
            if (($handle = fopen($path, "r")) !== FALSE) {
                // Assuming the first row contains headers
                $headers = fgetcsv($handle, 1000, ",");
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

                    \App\Models\Leads::create([
                        'website' => $data[0],
                        'template_id' => 1,
                        'notes' => ''
                    ]);
                }
                fclose($handle);
            }
        }
        Flux::toast('Import completed successfully.');
    }
};
?>

<div>
    <flux:toast/>
    <flux:heading size="xl" class="mb-6">
        ⚡ Import Data
    </flux:heading>
    <flux:separator class="mb-6" />
    <form wire:submit.prevents="submit" class="space-y-6">
        <flux:file-upload wire:model="import" multiple label="Upload .cvs">
            <flux:file-upload.dropzone
                heading="Drop files here or click to browse"
                text=".csv up to 10MB"
            />
        </flux:file-upload>
        <div class="mt-4 flex flex-col gap-2">
            @foreach ($import as $file)
            <flux:file-item
                heading="{{ $file->getClientOriginalName() }}"
            >
                <x-slot name="actions">
                    <flux:file-item.remove wire:click="removeFile({{ $loop->index }})" />
                </x-slot>
            </flux:file-item>
            @endforeach
        </div>
        <flux:button type="submit" variant="primary">
            Start Import
        </flux:button>
    </form>
</div>
