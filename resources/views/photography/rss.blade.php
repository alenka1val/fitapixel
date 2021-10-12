@extends('layouts.app')
@section('title', 'FIITAPIXEL - RSA kanály')
@section('content')
    <div class="content margin_div">
        <div class="article">
            <h2>RSS kanál - najnovšie fotografie</h2>
            <ul>
                <li>
                    <a href="http://foto.fiit.stuba.sk/rss/feed/">RSS 2.0 kanál - najnovšie fotografie</a> - vhodný pre väčšinu RSS čítačiek
                    <img src="/images/rss_logo.png" alt="RSS 2.0 kanál, najnovšie fotografie">
                </li>
                <li>
                    <a href="http://foto.fiit.stuba.sk/rss/photos/">media RSS - najnovšie fotografie</a> - vhodný pre aplikácie s podporou médií,
                    napr. Flock, <a href="/pages/cooliris_wall" title="príklad aplikácie používajúcej media RSS">Cooliris</a>,
                    ... <img src="/images/rss_logo_media.png" alt="media RSS - najnovšie fotografie">
                </li>
            </ul>
        </div>
    </div>
@endsection
