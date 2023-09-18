<?php

namespace App\Http\Livewire;

use App\Models\Chapa;
use Livewire\Component;
use Illuminate\View\View;
use App\Models\ChapaItem;
use Livewire\WithPagination;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ChapaChapaItemsDetail extends Component
{
    use WithPagination;
    use AuthorizesRequests;

    public Chapa $chapa;
    public ChapaItem $chapaItem;

    public $selected = [];
    public $editing = false;
    public $allSelected = false;
    public $showingModal = false;

    public $modalTitle = 'New ChapaItem';

    protected $rules = [
        'chapaItem.largura' => ['required', 'max:255', 'string'],
        'chapaItem.comprimento' => ['required', 'max:255', 'string'],
    ];

    public function mount(Chapa $chapa): void
    {
        $this->chapa = $chapa;
        $this->resetChapaItemData();
    }

    public function resetChapaItemData(): void
    {
        $this->chapaItem = new ChapaItem();

        $this->dispatchBrowserEvent('refresh');
    }

    public function newChapaItem(): void
    {
        $this->editing = false;
        $this->modalTitle = trans('crud.chapa_chapa_items.new_title');
        $this->resetChapaItemData();

        $this->showModal();
    }

    public function editChapaItem(ChapaItem $chapaItem): void
    {
        $this->editing = true;
        $this->modalTitle = trans('crud.chapa_chapa_items.edit_title');
        $this->chapaItem = $chapaItem;

        $this->dispatchBrowserEvent('refresh');

        $this->showModal();
    }

    public function showModal(): void
    {
        $this->resetErrorBag();
        $this->showingModal = true;
    }

    public function hideModal(): void
    {
        $this->showingModal = false;
    }

    public function save(): void
    {
        $this->validate();

        if (!$this->chapaItem->chapa_id) {
            $this->authorize('create', ChapaItem::class);

            $this->chapaItem->chapa_id = $this->chapa->id;
        } else {
            $this->authorize('update', $this->chapaItem);
        }

        $this->chapaItem->save();

        $this->hideModal();
    }

    public function destroySelected(): void
    {
        $this->authorize('delete-any', ChapaItem::class);

        ChapaItem::whereIn('id', $this->selected)->delete();

        $this->selected = [];
        $this->allSelected = false;

        $this->resetChapaItemData();
    }

    public function toggleFullSelection(): void
    {
        if (!$this->allSelected) {
            $this->selected = [];
            return;
        }

        foreach ($this->chapa->chapaItems as $chapaItem) {
            array_push($this->selected, $chapaItem->id);
        }
    }

    public function render(): View
    {
        return view('livewire.chapa-chapa-items-detail', [
            'chapaItems' => $this->chapa->chapaItems()->paginate(20),
        ]);
    }
}