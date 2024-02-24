@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Itens de Chapa</h1>
    <!-- Formulário de Pesquisa -->
    <form action="{{ route('chapaItems.search') }}" method="GET">
        <div class="form-group">
            <label for="searchId">Pesquisar por ID:</label>
            <input type="text" class="form-control" id="searchId" name="searchId" placeholder="Digite o ID">
            <button type="submit" class="btn btn-primary mt-2">Pesquisar</button>
        </div>
    </form>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Largura</th>
                <th>Comprimento</th>
                <th>Chapa ID</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($chapaItems as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td>{{ $item->largura }}</td>
                <td>{{ $item->comprimento }}</td>
                <td>{{ $item->chapa_id }}</td>
                <td>
                    <!-- Formulário de Deletar -->
                    <form action="{{ route('chapa-items.destroy', $item->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Deletar</button>
                    </form>
                </td>
            </tr>

            @endforeach
        </tbody>
    </table>
</div>
@endsection