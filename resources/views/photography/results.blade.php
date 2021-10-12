@extends('layouts.app')
@section('title', 'FIITAPIXEL - Výsledky')
@section('resultsActive') active @endsection
@section('content')
    <div class="content margin_div">
        @foreach($resultCategoryList as $result)
            <div id="resultDiv{{ $loop->index }}" class="resultDiv">
                <div class="resultHeader" onclick="collapseResult({{ $loop->index }})" title="kategoria: {{ $result }}">
                    <p class="resultP">
                        {{ $result }}
                    </p>
                    <a>
                        <i id="arrow-down-{{ $loop->index }}" class="arrow-down fa fa-angle-down"
                           title="rozbalit kategoriu"></i>
                        <i id="arrow-up-{{ $loop->index }}" class="arrow-up fa fa-angle-up"
                           title="zbalit kategoriu"></i>
                    </a>
                </div>
                <hr>
                <div class="resultTable">
                    <table>
                        <tr>
                            <th style="width: 5%">
                                Poradie
                            </th>
                            <th>
                                Hodnotenie odbornej poroty
                            </th>
                        </tr>
                        @foreach($juryResultList as $result)
                            <tr>
                                <td style="width: 5%">
                                    {{ $loop->index + 1 }}.
                                </td>
                                <td>
                                    <p>
                                        <b>
                                            {{ $result->photograph }}
                                        </b>
                                    </p>
                                    <p>
                                        {{ $result->photo->description }}
                                    </p>
                                    <p>
                                        {{ $result->photo->theme }}
                                    </p>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                    <table>
                        <tr>
                            <th style="width: 5%">
                                Poradie
                            </th>
                            <th>
                                Hodnotenie návštevníkmi
                            </th>
                        </tr>
                        @foreach($usersResultList as $result)
                            <tr>
                                <td style="width: 5%">
                                    {{ $loop->index + 1 }}.
                                </td>
                                <td>
                                    <p>
                                        <b>
                                            {{ $result->photograph }}
                                        </b>
                                    </p>
                                    <p>
                                        {{ $result->photo->description }}
                                    </p>
                                    <p>
                                        {{ $result->photo->theme }}
                                    </p>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        @endforeach
    </div>
@endsection
