<?php

namespace App\Http\Livewire;

use App\Models\Chapa;
use Livewire\Component;
use Illuminate\View\View;
use App\Models\ChapaItem;
use Livewire\WithPagination;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Exception; // Isso importa a classe Exception global para o arquivo.

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
    public $quantidade = 1;
    public $currentItemId;
    public $reservedItemName;




    public $modalTitle = 'New ChapaItem';

    protected $filteredItemsObj;


    protected $rules = [
        // 'chapaItem.id' => ['required', 'max:255'],
        'chapaItem.largura' => ['required', 'max:255', 'string'],
        'chapaItem.comprimento' => ['required', 'max:255', 'string'],
        'chapaItem.quantidade' => 'required|numeric|min:0', // Exemplo de regra

    ];

    protected $listeners = ['updated'];


    public function mount(Chapa $chapa): void
    {
        $this->chapa = $chapa;
        // Esta linha deve garantir que o novo ChapaItem tenha a quantidade padrão.
        $this->chapaItem = new ChapaItem(['quantidade' => $this->quantidade]);



        // $this->resetChapaItemData();
    }

    public function resetChapaItemData(): void
    {

        $this->dispatchBrowserEvent('refresh');
    }

    public function newChapaItem(): void
    {
        $this->editing = false;
        $this->modalTitle = trans('crud.chapa_chapa_items.new_title');
        // Quando criar um novo ChapaItem, defina a quantidade para o valor padrão.
        $this->chapaItem = new ChapaItem(['quantidade' => $this->quantidade]);
        $this->currentItemId = ChapaItem::max('id') + 1;


        $this->showModal();
    }

    public function editChapaItem(ChapaItem $chapaItem): void
    {
        $this->currentItemId = $chapaItem->id;
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

        // Preparar os dados para impressão
        $data = [
            'largura' => $this->chapaItem->largura,
            'comprimento' => $this->chapaItem->comprimento,
            'quantidade' => $this->chapaItem->quantidade,
        ];

        // Disparar o evento para exibir e imprimir os detalhes antes de salvar
        // $this->dispatchBrowserEvent('print-label', $data);

        // Aqui, você pode opcionalmente adicionar um delay ou esperar por uma confirmação do usuário
        // Isso dependerá de como você deseja gerenciar o fluxo de impressão vs. salvamento


        // Salvamento dos dados
        if (!$this->chapaItem->chapa_id) {
            $this->authorize('create', ChapaItem::class);

            $this->chapaItem->chapa_id = $this->chapa->id;
        } else {
            $this->authorize('update', $this->chapaItem);
        }

        $this->chapaItem->save();

        // Outras ações pós-salvamento
        $this->hideModal();
        $this->newChapaItem();
    }

    public function displayLabel()
    {
        $data = [
            'largura' => $this->chapaItem->largura,
            'comprimento' => $this->chapaItem->comprimento,
            'quantidade' => $this->chapaItem->quantidade,
        ];
        $this->dispatchBrowserEvent('print-label', $data);
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
            $dimensions .= "{$item->largura} mm x {$item->comprimento} mm x {$item->quantidade} ; ";
        }

        $this->dispatchBrowserEvent('show-dimensions', ['dimensions' => $dimensions]);
    }



    public function filterItems()
    {
        // Obter todos os registros de chapaItems antes de processar as linhas
        $TodosRegistros = $this->chapa->chapaItems()->get();
    
        $lines = explode(PHP_EOL, $this->itemsText);
        $foundIds = []; // Inicializando um array para armazenar os IDs encontrados
    
        foreach ($lines as $line) {
            $tabs = explode("\t", $line);
            if (isset($tabs[1]) && isset($tabs[2]) && isset($tabs[3])) {
                $quantidade = intval($tabs[1]);
                $largura = intval($tabs[2]);
                $comprimento = intval($tabs[3]);
    
                for ($i = 0; $i < $quantidade; $i++) { // Loop baseado na quantidade especificada
                    foreach ($TodosRegistros as $key => $registro) {
                        if ($registro->comprimento == $comprimento && $registro->largura == $largura) {
                            $foundIds[] = $registro->id;
                            unset($TodosRegistros[$key]); // Removendo o item da coleção
                            break; // Saindo do loop após encontrar e remover o item correspondente
                        }
                    }
                }
            }
        }
    
        // Reindexando a coleção após modificações
        $TodosRegistros = collect($TodosRegistros)->values();
    
        // Processamento adicional...
        $ids = array_unique($foundIds); // Removendo duplicatas

    
        if (!empty($ids)) {
            $this->filteredItemsObj = $this->chapa->chapaItems()->whereIn('id', $ids)->get();
            $this->selected = $ids; // Atualizando a propriedade $selected com os IDs dos itens filtrados
            $this->dispatchBrowserEvent('closeItemsModal');
        } else {
            // Trate o caso em que nenhum item foi encontrado
        }
    }
    

    public function reserveItems()
    {
        $this->validate([
            'reservedItemName' => 'required|string|max:255',
            'itemsText' => 'required|string'
        ]);
    
        // Criar o registro em reserved_items
        $reservedItem = \App\Models\ReservedItem::create([
            'nome' => $this->reservedItemName,
            // Adicione quaisquer outros campos necessários aqui
        ]);
    
        $lines = explode(PHP_EOL, $this->itemsText);
        $TodosRegistros = $this->chapa->chapaItems()->get();
        $foundIds = [];
    
        foreach ($lines as $line) {
            $tabs = explode("\t", $line);
            if (isset($tabs[1]) && isset($tabs[2]) && isset($tabs[3])) {
                $quantidade = intval($tabs[1]);
                $largura = intval($tabs[2]);
                $comprimento = intval($tabs[3]);
    
                for ($i = 0; $i < $quantidade; $i++) { // Loop baseado na quantidade especificada
                    foreach ($TodosRegistros as $key => $registro) {
                        if ($registro->comprimento == $comprimento && $registro->largura == $largura) {
                            $foundIds[] = $registro->id;
                            unset($TodosRegistros[$key]); // Removendo o item da coleção para evitar duplicatas
    
                            $registro1 = ChapaItem::find($registro->id);
                            if ($registro1) {
                                $registro1->reserved_item_id = $reservedItem->id;
                                $registro1->save();
                            }
                            break; // Saindo do loop após encontrar e marcar o item correspondente
                        }
                    }
                }
            }
        }
    
        // Fechar modal, limpar campos e atualizar a view conforme necessário
        $this->reset(['showingItemsModal', 'reservedItemName', 'itemsText']);
        // $this->emit('refreshItemsList'); // Se você precisar atualizar a lista de itens na view após a reserva
    }


    public function generateDimensionsFile()
    {
        $items = $this->chapa->chapaItems;
        $dimensionsText = '';

        foreach ($items as $item) {
            $dimensionsText .= "{$item->largura} mm X {$item->comprimento} mm X {$item->quantidade};\n";
        }

        $fileName = 'dimensions.txt';
        $filePath = storage_path('app/public/' . $fileName);

        file_put_contents($filePath, $dimensionsText);

        $this->dispatchBrowserEvent('file-generated', ['filePath' => $fileName]);
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
