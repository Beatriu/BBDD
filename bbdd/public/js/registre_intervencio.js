function retocarPagina() {
    let div = document.getElementById('output2');
    if (div) {
        div.children[5].classList.remove("mt-3");
        div.children[6].classList.remove("mt-3");
    }

    let table = document.getElementById("data-list-vista_intervencio");
    let tbody = table.children.item(1);
    let tiquets = tbody.children;

    for (let i = 0; i < tiquets.length; i++) {
        let div_accions = tiquets[i].children[5];
        console.log(tiquets[i]);
        div_accions.style.minWidth = "100px";
    }
}

window.addEventListener("load", (event) => {
    let paginate_buttons = document.getElementById("data-list-vista_intervencio_paginate");
    paginate_buttons.addEventListener("click", () => this.retocarPagina());

    let div = document.getElementById('output2');
    if (div) {
        div.children[5].classList.remove("mt-3");
        div.children[6].classList.remove("mt-3");
    }

    let table = document.getElementById("data-list-vista_intervencio");
    let tbody = table.children.item(1);
    let tiquets = tbody.children;

    for (let i = 0; i < tiquets.length; i++) {
        let div_accions = tiquets[i].children[5];
        console.log(tiquets[i]);
        div_accions.style.minWidth = "100px";

        let div_id = tiquets[i].children[0];
        
    }

  });