@extends('layouts.app')
@section('title', 'WebAdmin - ' . $title)
@section($active) nav-link-bold @endsection
@section('content')
    <div class="content">
        <div class="adminGrid margin_div">
            <h2>{{ $header }}</h2>
            <table class="adminTable">
                <tr>
                    <th>
                        {{ $tableColumns[0] }}
                    </th>
                    <th>
                        {{ $tableColumns[1] }}
                    </th>
                    <th></th>
                </tr>
                @foreach($entries as $entry)
                    <tr>
                        <td>
                            {{ $entry[$entryColumns[0]] }}
                        </td>
                        <td>
                            {{ $entry[$entryColumns[1]] }}
                        </td>
                        <td>
                            <form action="{{ route($editURL, ['id' => $entry['id']]) }}" method="GET">
                                @csrf
                                <button type="submit" class="adminButton">
                                    <i class="fa fa-edit"></i>
                                </button>
                            </form>
                            <form name="deleteForm" action="{{ route( $deleteURL, ['id' => $entry['id']]) }}"
                                  method="POST">
                                @method('DELETE')
                                @csrf
                                <button type="submit" class="adminButton"
                                        onclick="return confirm('{{ $confirm }}:\n\'{{ $entry[$confirmAttr] }}\'?')">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
        <form action="{{ route($editURL, ['id' => 'new']) }}" method="GET">
            @csrf
            <button class="newButton">
                +
            </button>
        </form>
        <div class="pagination" @if($page <= 1 or $page >= $maxPage) style="grid-template-columns: 100%;" @endif>
            @if($page > 1)
                <a href="{{ route($indexURL, ['page'=> $page - 1]) }}">
                    <button class="pageButton">
                        < Späť
                    </button>
                </a>
            @endif
            @if($page < $maxPage)
                <a href="{{ route($indexURL, ['page'=> $page + 1]) }}">
                    <button class="pageButton">
                        Ďalej >
                    </button>
                </a>
            @endif
        </div>
    </div>
@endsection
