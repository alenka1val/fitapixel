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
        document.getElementById("collapse").style.borderTop = "2px solid #5DD5D5";
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
