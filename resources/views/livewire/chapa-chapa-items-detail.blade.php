<div>
    <div class="mb-4">
        @can('create', App\Models\ChapaItem::class)
        <button class="btn btn-primary" wire:click="newChapaItem">
            <i class="icon ion-md-add"></i>
            @lang('crud.common.new')
        </button>
        @endcan @can('delete-any', App\Models\ChapaItem::class)
        <button class="btn btn-danger" {{ empty($selected) ? 'disabled' : '' }} onclick="confirm('Are you sure?') || event.stopImmediatePropagation()" wire:click="destroySelected">
            <i class="icon ion-md-trash"></i>
            @lang('crud.common.delete_selected')
        </button>
        @endcan
        <button class="btn btn-info" wire:click="showAllDimensions">
            Mostrar Todas as Dimensões
        </button>
        <button class="btn btn-secondary" wire:click="showItemsModal">
            Mostrar Itens de Chapa
        </button>
        <button class="btn btn-success" wire:click="generateDimensionsFile">
            Gerar Arquivo de Dimensões
        </button>



    </div>

    <x-modal id="chapa-chapa-items-modal" wire:model="showingModal">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ $modalTitle }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div>
                    <x-inputs.group class="col-sm-12">
                        <x-inputs.text name="chapaItem.largura" label="Largura" wire:model="chapaItem.largura" maxlength="255" placeholder="Largura" wire:keydown.debounce.2000ms="focusComprimento"></x-inputs.text>

                    </x-inputs.group>

                    <x-inputs.group class="col-sm-12">
                        <x-inputs.text name="chapaItem.comprimento" label="Comprimento" wire:model.debounce.2000ms="chapaItem.comprimento" maxlength="255" placeholder="Comprimento"></x-inputs.text>

                    </x-inputs.group>
                    <x-inputs.group class="col-sm-12">
                        <x-inputs.text name="chapaItem.quantidade" label="Quantidade" wire:model.defer="chapaItem.quantidade" maxlength="255" placeholder="Quantidade">
                        </x-inputs.text>
                    </x-inputs.group>
                    <x-inputs.group class="col-sm-12">
                        <x-inputs.text name="currentItemId" label="ID Atual" wire:model.defer="currentItemId" maxlength="255" placeholder="ID" readonly>
                        </x-inputs.text>
                    </x-inputs.group>

                </div>
            </div>

            @if($editing) @endif

            <div class="modal-footer">
                <button type="button" class="btn btn-light float-left" wire:click="$toggle('showingModal')">
                    <i class="icon ion-md-close"></i>
                    @lang('crud.common.cancel')
                </button>

                <button type="button" class="btn btn-primary" wire:click="save">
                    <i class="icon ion-md-save"></i>
                    @lang('crud.common.save')
                </button>
            </div>
        </div>
        <!-- Conteúdo invisível para impressão -->
        <div id="printableArea" style="display:none;">
            <div style="font-family: 'Arial', sans-serif; font-size: 10px;">
                Largura: <span id="printLargura">--</span> mm<br>
                <!-- Comprimento: <span id="printComprimento">--</span> mm<br>
                Quantidade: <span id="printQuantidade">--</span><br> -->
            </div>
        </div>

    </x-modal>

    <x-modal id="items-modal" wire:model="showingItemsModal">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reservar Itens de Chapa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="text" wire:model="reservedItemName" class="form-control mb-3" placeholder="Nome da Reserva">
                <textarea wire:model="itemsText" class="form-control" rows="5" placeholder="Insira os detalhes dos itens aqui..."></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" wire:click="$toggle('showingItemsModal')">Cancelar</button>
                <button type="button" class="btn btn-primary" wire:click="reserveItems">Reservar Itens</button>
            </div>
        </div>
    </x-modal>


    <div class="table-responsive">
        <table class="table table-borderless table-hover">
            <thead>
                <tr>
                    <th>
                        <input type="checkbox" wire:model="allSelected" wire:click="toggleFullSelection" title="{{ trans('crud.common.select_all') }}" />
                    </th>
                    <th class="text-right">
                        @lang('crud.chapa_chapa_items.inputs.id')
                    </th>
                    <th class="text-left">
                        @lang('crud.chapa_chapa_items.inputs.largura')
                    </th>
                    <th class="text-left">
                        @lang('crud.chapa_chapa_items.inputs.comprimento')
                    </th>
                    <th class="text-left">
                        Quantidade
                    </th>
                    <th></th>
                </tr>
            </thead>
            <tbody class="text-gray-600">

                @php

                $itemsToDisplay = (is_array($filteredItems) || $filteredItems instanceof Countable) && count($filteredItems) > 0 ? $filteredItems : $chapaItems;

                @endphp
                @foreach ($itemsToDisplay as $chapaItem)
                <tr class="hover:bg-gray-100">
                    <td class="text-left">
                        <input type="checkbox" value="{{ $chapaItem->id }}" wire:model="selected" />
                    </td>
                    <td class="text-right">{{ $chapaItem->id ?? '-' }}</td>
                    <td class="text-left">{{ $chapaItem->largura ?? '-' }}</td>
                    <td class="text-left">
                        {{ $chapaItem->comprimento ?? '-' }}
                    </td>
                    <td class="text-left">
                        {{ $chapaItem->quantidade ?? '-' }}
                    </td>
                    <td class="text-right" style="width: 134px;">
                        <div role="group" aria-label="Row Actions" class="relative inline-flex align-middle">
                            @can('update', $chapaItem)
                            <button type="button" class="btn btn-light" wire:click="editChapaItem({{ $chapaItem->id }})">
                                <i class="icon ion-md-create"></i>
                            </button>
                            @endcan
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4">{{ $chapaItems->render() }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    <div wire:dispatch="print-label" wire:print-label="data => console.log('Dados recebidos para impressão:', data)">
        <!-- Your HTML content here -->
    </div>
</div>

<script>
    window.addEventListener('focusLargura', event => {
        setTimeout(() => {
            document.querySelector('input[name="chapaItem.largura"]').focus();
        }, 500); // delay of 500ms
    });

    window.addEventListener('focusComprimento', event => {
        document.querySelector('input[name="chapaItem.comprimento"]').focus();
    });

    window.addEventListener('show-dimensions', event => {
        alert(event.detail.dimensions);
    });

    window.addEventListener('show-items-modal', event => {
        $('#items-modal').modal('show');
    });

    window.addEventListener('closeItemsModal', event => {
        $('#items-modal').modal('hide');
    });
</script>


<script>
    window.addEventListener('print-label', event => {
        ajustarMargensDeImpressaoDiferentes();

        const data = event.detail;

        // Atualizar o conteúdo do HTML com os dados recebidos
        document.getElementById('printLargura').textContent = data.largura ?? 'N/A';
        // document.getElementById('printComprimento').textContent = data.comprimento ?? 'N/A';
        // document.getElementById('printQuantidade').textContent = data.quantidade ?? 'N/A';

        // Exibir o elemento printableArea antes de imprimir
        document.getElementById('printableArea').style.display = 'block';


        // Chamar printJS passando o ID do elemento HTML
        printJS({
            printable: 'printableArea',
            type: 'html',
            scanStyles: false // Desativar a leitura de estilos internos se necessário
        });
    });

    function ajustarMargensDeImpressaoDiferentes() {
        var css = `@media print {
                @page {
                  margin-top: 1cm; /* Margem superior */
                  margin-bottom: 2cm; /* Margem inferior */
                  margin-left: 3cm; /* Margem esquerda */
                  margin-right: 4cm; /* Margem direita */
                }
             }`,
            head = document.head || document.getElementsByTagName('head')[0],
            style = document.createElement('style');

        style.type = 'text/css';
        style.media = 'print';

        if (style.styleSheet) {
            style.styleSheet.cssText = css;
        } else {
            style.appendChild(document.createTextNode(css));
        }

        head.appendChild(style);
    }

    document.addEventListener('DOMContentLoaded', () => {
        window.addEventListener('file-generated', event => {
            const fileName = event.detail.filePath;
            const downloadUrl = `/storage/${fileName}`;
            window.open(downloadUrl, '_blank');
        });
    });
</script>
<script src="https://printjs-4de6.kxcdn.com/print.min.js"></script>
<link rel="stylesheet" href="https://printjs-4de6.kxcdn.com/print.min.css">