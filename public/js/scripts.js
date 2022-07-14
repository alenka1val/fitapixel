window.onresize = function (event) {
    if (window.innerWidth >= 768 && document.getElementById("collapse").style.height.toString() !== 0) {
        document.getElementById("collapse").style.display = "none";
        document.getElementById("collapse").style.height = "0px";
        document.getElementById("collapse").style.border = "none";
    }
};

function collapseNav() {
    let height = "170px";
    if (document.getElementById("collapse").style.height.toString() === height) {
        document.getElementById("collapse").style.display = "none";
        document.getElementById("collapse").style.height = "0px";
        document.getElementById("collapse").style.border = "none";
        document.getElementById("burgerMenu").style.display = "block";
        document.getElementById("burgerX").style.display = "none";
    } else {
        document.getElementById("collapse").style.display = "block";
        document.getElementById("collapse").style.height = height;
        //document.getElementById("collapse").style.borderTop = "2px solid rgb(46, 163, 242)";
        document.getElementById("burgerMenu").style.display = "none";
        document.getElementById("burgerX").style.display = "block";
    }
}

function home() {
    window.location.pathname = "/";
}

function redirect(url) {
    window.open(url, '_blank');
}

function collapseResult(index) {
    if (document.getElementById("arrow-up-" + index).style.display !== "block") {
        document.getElementById("resultDiv" + index).style.maxHeight = "none";
        document.getElementById("arrow-down-" + index).style.display = "none";
        document.getElementById("arrow-up-" + index).style.display = "block";
    } else {
        document.getElementById("resultDiv" + index).style.maxHeight = "36px";
        document.getElementById("arrow-down-" + index).style.display = "block";
        document.getElementById("arrow-up-" + index).style.display = "none";
    }
}

function zoomIn(imgName, index, photograph, description, theme) {
    const modal = document.getElementById("myModal");
    modal.style.zIndex = "99";

    const img = document.getElementById(imgName);
    const modalImg = document.getElementById("img01");
    const captionText1 = document.getElementById("captionText1");
    const captionText2 = document.getElementById("captionText2");
    const captionText3 = document.getElementById("captionText3");

    modal.style.display = "block";
    modalImg.src = img.src;
    modalImg.alt = index;
    captionText1.innerHTML = "Fotograf: " + photograph;
    captionText2.innerHTML = "Opis: " + description;
    captionText3.innerHTML = "Téma: " + theme;

    const span = document.getElementsByClassName("close")[0];

    span.onclick = function () {
        modal.style.display = "none";
    };
}

function moveLeft(photoList) {
    const modalImg = document.getElementById("img01");
    const captionText1 = document.getElementById("captionText1");
    const captionText2 = document.getElementById("captionText2");
    const captionText3 = document.getElementById("captionText3");

    let index = parseInt(modalImg.alt);
    let nextIndex = index <= 0 ? photoList.length - 1 : index - 1;

    modalImg.src = photoList[nextIndex]['filename'];
    captionText1.innerHTML = "Fotograf: " + photoList[nextIndex]['photograph'];
    captionText2.innerHTML = "Opis: " + photoList[nextIndex]['description'];
    captionText3.innerHTML = "Téma: " + photoList[nextIndex]['theme'];
    modalImg.alt = nextIndex;
}

function moveRight(photoList) {
    const modalImg = document.getElementById("img01");
    const captionText1 = document.getElementById("captionText1");
    const captionText2 = document.getElementById("captionText2");
    const captionText3 = document.getElementById("captionText3");

    let index = parseInt(modalImg.alt);
    let nextIndex = index >= photoList.length - 1 ? 0 : index + 1;

    modalImg.src = photoList[nextIndex]['filename'];
    captionText1.innerHTML = "Fotograf: " + photoList[nextIndex]['photograph'];
    captionText2.innerHTML = "Opis: " + photoList[nextIndex]['description'];
    captionText3.innerHTML = "Téma: " + photoList[nextIndex]['theme'];
    modalImg.alt = nextIndex;
}

function showAISLogin() {
    let ais_login = document.getElementById("fiit_user");
    let group_select = document.getElementById("group_id");
    let jury_div = document.getElementById("juryDiv");

    let select_value = group_select.options[group_select.selectedIndex].value;
    if (select_value == 1 || select_value == 2 || select_value == 4) {
        if (ais_login !== null) ais_login.style.display = "block";
    } else {
        if (ais_login !== null) ais_login.style.display = "none";
    }

    if (select_value == 6) {
        if (jury_div !== null) jury_div.style.display = "block";
    } else {
        if (jury_div !== null) jury_div.style.display = "none";
    }
}

function redirect(url) {
    console.log(url);
    window.location.href = url;
}

function countCharacters(max_count) {
    let text_area = document.getElementById("description");
    let count_characters = document.getElementById("count_characters");

    count_characters.innerText = text_area.value.length + "/" + max_count;
}
