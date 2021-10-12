<footer>
    <div class="footer">
        <div class="footer_1">
            <p>Organizátor fotosúťaže FIITAPIXEL:</p>
            <p>Fakulta informatiky a informačných technológií STU v Bratislave</p>
            <br>
            <address>Ilkovičova 3</address>
            <address>842 16 Bratislava 4</address>
            <address>mail: foto@fiit.stuba.sk</address>
{{--            <p><a href="http://www.fiit.stuba.sk">web: www.fiit.stuba.sk</a></p>--}}
            <br>
            <p>IČO: 397687</p>
            <p>DIČ: 2020845255</p>
            <p>IČ DPH: SK2020845255</p>
        </div>
        <div class="footer_2">
            <p><a class="footerNav" href="{{ route('home') }}"  title="domov">Ochrana osobných údajov</a></p>
            <p><a class="footerNav" href="{{ route('home') }}" title="aktuality">Autorská ochrana</a></p>
            <p><a class="footerNav" href="{{ route('home') }}" title="dokumenty">RSS kanál</a></p>
            <p><a class="footerNav" href="{{ route('home') }}" title="fotoalbum">FAQ</a></p>
            <p><a class="footerNav" href="{{ route('home') }}" title="kontakt">Odkazy</a></p>
            <br>
            <br>
            <div class="sponsors">
                <img id="nav_img" src="{{asset( '/images/fiit.png' )}}" alt="FIIT STU" onclick="redirect('https://www.fiit.stuba.sk')" title="FIIT STU">
                <img id="nav_img" src="{{asset( '/images/cewe.png' )}}" alt="CEWE" onclick="redirect('https://www.cewe.sk')" title="CEWE">
            </div>
        </div>
    </div>
</footer>
