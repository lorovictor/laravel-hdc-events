@extends('layouts.main')

@section('title', 'HDC Events')

@section('content')

    <div class="text-center" style="margin-top:10px;">
        <h1>HORA DE CODAR</h1>

        <img src="/img/eventos.jpg" alt="Eventos">

        @if(10 > 5)
            <p>A condição é true</p>
        @endif

        <p>{{ $nome }}</p>

        @if($nome == "Pedro")
            <p>O nome é Pedro</p>
        @elseif ($nome == "Matheus")
            <p>O nome é {{$nome}} e ele tem {{$idade}} anos e ele é um {{$profissao}}</p>
        @else
            <p>O nome não é Pedro</p>
        @endif

        @for($i=0; $i < count($arr); $i++)
            <p>{{ $arr[$i] }} - {{$i}}</p>
            @if ($i == 2)
                <p>O i é igual a 2</p>
            @endif
        @endfor

        @foreach($nomes as $nome)
            <p>{{ $nome }}</p>
            <p>{{ $loop->index }}</p>
        @endforeach

        @php
            $name = "Lucas";
            echo $name;
        @endphp

        <!-- O comentário do HTML -->
        {{-- Comentário no Blade --}}
    </div>

@endsection
