@extends('layouts.app')
@section('title', 'FIIT a PIXEL')
@section('content')
<div class="container">
    <div id="home">
        <div id="header-main">
            <h1>FIITAPIXEL</h1>
            <br\>
            <div class="mySlides fade">
                <div class="numbertext">1 / 3</div>
                <p>Súťaž trvá:<wbr> od 30.3.2021<wbr> do 12.10.2021</p>
            </div>
            <div class="mySlides fade">
                <div class="numbertext">2 / 3</div>
                <p>Súťaž trvá: do 12.10.2021</p>
            </div>
            <div class="mySlides fade">
                <div class="numbertext">3 / 3</div>
                <p>Súťaž trvá: do 12.10.2021</p>
            </div>
            <div style="text-align:center">
                <span class="dot" onclick="resetSlides(1)"></span> 
                <span class="dot" onclick="resetSlides(2)"></span> 
                <span class="dot" onclick="resetSlides(3)"></span> 
            </div>
            <!-- <p>Súťaž trvá:<wbr> od 30.3.2021<wbr> do 12.10.2021</p> -->
        </div>
        <div class="main-button">
            <button class="main-button yellow-background last">
                Zapojiť sa do súťaže
            </button>
        </div>
    </div>
    <div id="about" class="info-panel-left col-2">
        <div>
            <img class="info-image" src="../images/environment.jpeg"></img>         
        </div>
        <div class="pad-left-5p">
            <div>
                <h2>Čo je FIITAPIXEL?</h2>
                <div class="underline"></div>
            </div>
            <p>
                Nulla porttitor accumsan tincidunt. Quisque velit nisi, pretium ut lacinia in, elementum id enim. Nulla quis lorem ut libero malesuada feugiat. Curabitur aliquet quam id dui posuere blandit. Vivamus suscipit tortor eget felis porttitor volutpat. Pellentesque in ipsum id orci porta dapibus. Proin eget tortor risus. Nulla quis lorem ut libero malesuada feugiat. Vestibulum ac diam sit amet quam vehicula elementum sed sit amet dui. Nulla porttitor accumsan tincidunt.
            </p>
        </div>
    </div>
    <div id="history" class="info-panel col-2">
        <div class="pad-right-5p">
            <div>
                <h2>Ako vznikol FIITAPIXEL?</h2>
                <div class="underline"></div>
            </div>
            <p>
                Nulla porttitor accumsan tincidunt. Quisque velit nisi, pretium ut lacinia in, elementum id enim. Nulla quis lorem ut libero malesuada feugiat. Curabitur aliquet quam id dui posuere blandit. Vivamus suscipit tortor eget felis porttitor volutpat. Pellentesque in ipsum id orci porta dapibus. Proin eget tortor risus. Nulla quis lorem ut libero malesuada feugiat. Vestibulum ac diam sit amet quam vehicula elementum sed sit amet dui. Nulla porttitor accumsan tincidunt.
            </p>
        </div>
        <div>
            <img class="info-image" src="../images/environment.jpeg"></img>         
        </div>
    </div>
    <div id="price">
        <br\>
    </div>

</div>
@endsection
