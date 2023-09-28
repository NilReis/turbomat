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
                    <th></th>
                </tr>
            </thead>
            <tbody class="text-gray-600">
                @foreach ($chapaItems as $chapaItem)
                <tr class="hover:bg-gray-100">
                    <td class="text-left">
                        <input type="checkbox" value="{{ $chapaItem->id }}" wire:model="selected" />
                    </td>
                    <td class="text-right">{{ $chapaItem->id ?? '-' }}</td>
                    <td class="text-left">{{ $chapaItem->largura ?? '-' }}</td>
                    <td class="text-left">
                        {{ $chapaItem->comprimento ?? '-' }}
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
</script>