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

    public $showingItemsModal = false;
    public $itemsText = '';


    public $modalTitle = 'New ChapaItem';

    protected $filteredItemsObj;


    protected $rules = [
        // 'chapaItem.id' => ['required', 'max:255'],
        'chapaItem.largura' => ['required', 'max:255', 'string'],
        'chapaItem.comprimento' => ['required', 'max:255', 'string'],
    ];

    protected $listeners = ['updated'];


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

        // Dispatch the event to set focus to the 'Largura' input
        $this->dispatchBrowserEvent('focusLargura');
    }

    public function hideModal(): void
    {
        $this->showingModal = false;
    }

    public function save(): void
    {
        $this->validate();

        // Convertendo e arredondando largura e comprimento
        $this->chapaItem->largura =  $this->arredondar(floatval($this->chapaItem->largura));
        $this->chapaItem->comprimento =  $this->arredondar(floatval($this->chapaItem->comprimento));


        if (!$this->chapaItem->chapa_id) {
            $this->authorize('create', ChapaItem::class);

            $this->chapaItem->chapa_id = $this->chapa->id;
        } else {
            $this->authorize('update', $this->chapaItem);
        }

        $this->chapaItem->save();

        $this->hideModal();

        $this->newChapaItem();
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
    public function focusComprimento()
    {
        $this->dispatchBrowserEvent('focusComprimento');
    }

    public function updated($propertyName)
    {
        if ($propertyName === 'chapaItem.comprimento') {
            // Aqui, você pode chamar o método para salvar o registro.
            // Por exemplo:
            $this->save();
        }
    }

    public function arredondar($numero)
    {
        // Convertendo a string para um float
        $numero = floatval($numero);

        // Primeiro, arredonde o número para o inteiro mais próximo
        $numeroArredondado = round($numero);

        // Então, modifique o número arredondado para terminar em 0 ou 5
        $ultimoDigito = $numeroArredondado % 10;
        if ($ultimoDigito >= 0 && $ultimoDigito < 5) {
            return $numeroArredondado - $ultimoDigito;
        } elseif ($ultimoDigito >= 5 && $ultimoDigito < 10) {
            return $numeroArredondado - $ultimoDigito + 5;
        }
        return $numeroArredondado;
    }

    public function showAllDimensions(): void
    {
        $items = $this->chapa->chapaItems;
        $dimensions = '';

        foreach ($items as $item) {
            $dimensions .= "{$item->largura} mm x {$item->comprimento} mm; ";
        }

        $this->dispatchBrowserEvent('show-dimensions', ['dimensions' => $dimensions]);
    }



    public function filterItems()
    {
        $lines = explode(PHP_EOL, $this->itemsText);
        $ids = []; // Inicializando um array para armazenar os IDs dos itens filtrados

        foreach ($lines as $line) {
            $tabs = explode("\t", $line);

            if (isset($tabs[2]) && isset($tabs[3])) {
                $largura  = intval($tabs[2]);
                $comprimento = intval($tabs[3]);

                // Obtendo IDs dos itens que correspondem aos critérios
                $filteredIds = $this->chapa->chapaItems()->where('comprimento', $comprimento)
                    ->where('largura', $largura)
                    ->pluck('id')
                    ->toArray(); // Convertendo a coleção para um array

                // Adicionando os IDs dos itens filtrados ao array de IDs
                $ids = array_merge($ids, $filteredIds);
            }
        }

        if (!empty($ids)) {
            $items = $this->chapa->chapaItems()->whereIn('id', $ids)->paginate(20);
            $this->filteredItemsObj = $items; // Armazenando o objeto paginado
            $this->selected = $ids; // Atualizando a propriedade $selected com os IDs dos itens filtrados
            $this->dispatchBrowserEvent('closeItemsModal');
        } else {
            // Handle the case where no items were found
        }
    }






    public function showItemsModal()
    {
        $this->dispatchBrowserEvent('show-items-modal');
    }

    public function render(): View
    {
        return view('livewire.chapa-chapa-items-detail', [
            'chapaItems' => $this->chapa->chapaItems()->paginate(20),
            'filteredItems' => $this->filteredItemsObj, // Adicione esta linha
        ]);
    }
}
