<div id="home">
    <div id="header-main">
        @foreach($events as $event)
            <div class="mySlides fade">
                <h1>{{ $event->name }}</h1>
                <br>
                <div class="numbertext">{{ $loop->index + 1 }} / count($carusel_events)</div>
                <p>Súťaž trvá:
                    <wbr>
                    od {{ (new DateTime($event->started_at))->format('d.m.Y')}}
                    <wbr>
                    do {{ (new DateTime($event->finished_at))->format('d.m.Y')}}
                </p>
            </div>
        @endforeach
        <div style="text-align:center">
            @foreach($events as $event)
                <span class="dot" onclick="resetSlides({{ $loop->index + 1 }})"></span>
            @endforeach
        </div>
    </div>
    <div class="main-button">
        <button class="main-button yellow-background last" onclick="redirect_self('#competitions')">
            Zapojiť sa do súťaže
        </button>
    </div>
</div>
