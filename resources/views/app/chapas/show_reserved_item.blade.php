@extends('layouts.app')

@section('title', 'Detalhes do Item Reservado')

@section('content')
<div class="container">
    <h1>{{ $reservedItem->nome }}</h1>
    <p>Número de Itens de Chapa: {{ $reservedItem->chapaItems->count() }}</p>

    @if($reservedItem->chapaItems->isNotEmpty())
    <table class="table" id="chapaItemsTable">
        <thead>
            <tr>
                <th>Id</th>
                <th>Largura</th>
                <th>Comprimento</th>
                <!-- Adicione outras colunas conforme necessário -->
            </tr>
        </thead>
        <tbody>
            @foreach ($reservedItem->chapaItems as $chapaItem)
            <tr>
                <td>{{ $chapaItem->id }}</td>
                <td>{{ $chapaItem->largura }}</td>
                <td>{{ $chapaItem->comprimento }}</td>
                <td><button class="btn btn-primary selectButton">Selecionar</button></td>

            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p>Não há itens de chapa associados a este item reservado.</p>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const table = document.getElementById('chapaItemsTable');
        table.addEventListener('click', function(e) {
            if (e.target.classList.contains('selectButton')) {
                const tr = e.target.closest('tr'); // Encontra a linha <tr> mais próxima
                tr.style.backgroundColor = tr.style.backgroundColor ? '' : '#ffd700'; // Muda a cor da linha
            }
        });
    });
</script>

@endsection