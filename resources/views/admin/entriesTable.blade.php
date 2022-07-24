@extends('layouts.app')
@section('title', 'WebAdmin - ' . $header)
@section($active) nav-link-bold @endsection
@section('content')
    <div class="content">
        <div class="adminGrid margin_div">
            <h2>{{ $header }}</h2>
            @if(count($entries) > 0)
            <table class="adminTable">
                <tr>
                    <th>
                        {{ $tableColumns[0] }}
                    </th>
                    <th style="text-align: center">
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
        <div class="paginationOut">
            <div class="pagination">
                @if($page > 1) <a href="{{ route($indexURL, ['page'=> $page - 1]) }}" class="arrowPage"> < </a> @endif
                @if($page - 2 > 1 ) <a href="{{ route($indexURL, ['page'=> 1]) }}" class="arrowPage"> << </a> @endif
                @if($page - 2 > 2) <a href="#" class="nonPage"><strong>...</strong></a> @endif
                @for($i = $page - 2 > 0 ? $page - 2 : 1; $i <= ($page + 2 <= $maxPage ? $page + 2 : $maxPage); $i++)
                    <a href="{{ route($indexURL, ['page'=> $i]) }}" @if($i == $page) class="active" @endif>{{ $i }}</a>
                @endfor
                @if($i < $maxPage) <a href="#" class="nonPage"><strong>...</strong></a> @endif
                @if($i <= $maxPage) <a href="{{ route($indexURL, ['page'=> $maxPage]) }}" class="arrowPage"> >> </a> @endif
                @if($page < $maxPage) <a href="{{ route($indexURL, ['page'=> $page + 1]) }}" class="arrowPage">
                    > </a> @endif
            </div>
            @else
                <h4 class="photo-not-found left">Nenašli sa žiadne záznamy!</h4>
                @endif
            <form action="{{ route($editURL, ['id' => 'new']) }}" method="GET">
                @csrf
                <button class="newButton">
                    +
                </button>
            </form>
        </div>
    </div>
@endsection
