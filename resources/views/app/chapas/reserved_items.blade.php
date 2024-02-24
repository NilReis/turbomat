@extends('layouts.app') {{-- Assumindo que você tem um layout principal --}}

@section('title', 'Itens Reservados')

@section('content')
<div class="container">
    <h1>Itens Reservados</h1>
    <table class="table">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Detalhes</th>
                <th>Quantidade</th>
                <!-- Outras colunas conforme necessário -->
            </tr>
        </thead>
        <tbody>
            @foreach ($reservedItems as $item)
            <tr>
                <td>{{ $item->nome }}</td>
                <td>{{ $item->chapaItem ? $item->chapaItem->detalhes : 'N/A' }}</td>
                <td>{{ $item->quantidade }}</td> {{-- Supondo que você tenha uma coluna quantidade --}}
                <td>
                    <a href="{{ route('reserved-items.show', $item->id) }}" class="btn btn-info">Ver Detalhes</a>
                </td>
                <td>
                    <form action="{{ route('reserved-items.destroy', $item->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja deletar este item?')">Deletar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection