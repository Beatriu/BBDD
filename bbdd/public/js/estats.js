function actualitzarColorsEstats() {
    let table = document.getElementById("data-list-vista_tiquet");
    let tbody = table.children.item(1);
    let tiquets = tbody.children;

    for (let i = 0; i < tiquets.length; i++) {

        if (role == "sstt" || role == "admin_sstt" || role == "desenvolupador") {

            let element = tiquets[i].children[6];
            if (element.textContent == "Pendent de recollir") {
                element.innerHTML = "<div class='border rounded text-bg-danger p-2'>" + element.textContent + " <i class='fa-solid fa-circle-exclamation'></i></div>";
            } else if (element.textContent == "Assignat i pendent de recollir") {
                element.innerHTML = "<div class='border rounded text-bg-danger p-2'>" + element.textContent + "</div>";
            } else if (element.textContent == "Emmagatzemat a SSTT") {
                element.innerHTML = "<div class='border rounded text-bg-danger p-2'>" + element.textContent + "</div>";
            } else if (element.textContent == "Assignat i emmagatzemat a SSTT") {
                element.innerHTML = "<div class='border rounded text-bg-danger p-2'>" + element.textContent + "</div>";
            } else if (element.textContent == "Pendent de reparar") {
                element.innerHTML = "<div class='border rounded text-bg-secondary p-2'>" + element.textContent + "</div>";
            } else if (element.textContent == "Reparant") {
                element.innerHTML = "<div class='border rounded text-bg-secondary p-2'>" + element.textContent + "</div>";
            } else if (element.textContent == "Reparat i pendent de recollir") {
                element.innerHTML = "<div class='border rounded text-bg-warning p-2'>" + element.textContent + "</div>";
            } else if (element.textContent == "Pendent de retorn") {
                element.innerHTML = "<div class='border rounded text-bg-warning p-2'>" + element.textContent + "</div>";
            } else if (element.textContent == "Retornat") {
                element.innerHTML = "<div class='border rounded text-bg-light p-2'>" + element.textContent + "</div>";
            } else if (element.textContent == "Rebutjat per SSTT") {
                element.innerHTML = "<div class='border rounded text-bg-light p-2'>" + element.textContent + "</div>";
            } else if (element.textContent == "Desguassat") {
                element.innerHTML = "<div class='border rounded text-bg-light p-2'>" + element.textContent + "</div>";
            }

            let div_accions = tiquets[i].children[10];
            div_accions.style.minWidth = "100px";

        } else if (role == "alumne" || role == "professor") {

            let element = tiquets[i].children[4];
            if (element.textContent == "Pendent de reparar") {
                element.innerHTML = "<div class='border rounded text-bg-danger p-2'>" + element.textContent + "</div>";
            } else if (element.textContent == "Reparant") {
                element.innerHTML = "<div class='border rounded text-bg-danger p-2'>" + element.textContent + "</div>";
            } else if (element.textContent == "Reparat i pendent de recollir") {
                if (role == "alumne") {
                    element.innerHTML = "<div class='border rounded text-bg-secondary p-2'>" + element.textContent + "</div>";
                } else if (role == "professor") {
                    element.innerHTML = "<div class='border rounded text-bg-warning p-2'>" + element.textContent + "</div>";
                }
            }

            if (role == "professor") {
                let div_accions = tiquets[i].children[8];
                div_accions.style.minWidth = "100px";
            }

        } else if (role == "centre_emissor") {

            let element = tiquets[i].children[4];
            if (element.textContent == "Pendent de recollir") {
                element.innerHTML = "<div class='border rounded text-bg-secondary p-2'>" + element.textContent + " <i class='fa-solid fa-circle-exclamation'></i></div>";
            } else if (element.textContent == "Assignat i pendent de recollir") {
                element.innerHTML = "<div class='border rounded text-bg-secondary p-2'>" + element.textContent + "</div>";
            } else if (element.textContent == "Emmagatzemat a SSTT") {
                element.innerHTML = "<div class='border rounded text-bg-secondary p-2'>" + element.textContent + "</div>";
            } else if (element.textContent == "Assignat i emmagatzemat a SSTT") {
                element.innerHTML = "<div class='border rounded text-bg-secondary p-2'>" + element.textContent + "</div>";
            } else if (element.textContent == "Pendent de reparar") {
                element.innerHTML = "<div class='border rounded text-bg-danger p-2'>" + element.textContent + "</div>";
            } else if (element.textContent == "Reparant") {
                element.innerHTML = "<div class='border rounded text-bg-danger p-2'>" + element.textContent + "</div>";
            } else if (element.textContent == "Reparat i pendent de recollir") {
                element.innerHTML = "<div class='border rounded text-bg-danger p-2'>" + element.textContent + "</div>";
            } else if (element.textContent == "Pendent de retorn") {
                element.innerHTML = "<div class='border rounded text-bg-warning p-2'>" + element.textContent + "</div>";
            } else if (element.textContent == "Retornat") {
                element.innerHTML = "<div class='border rounded text-bg-success p-2'>" + element.textContent + "</div>";
            } else if (element.textContent == "Rebutjat per SSTT") {
                element.innerHTML = "<div class='border rounded text-bg-light p-2'>" + element.textContent + "</div>";
            } else if (element.textContent == "Desguassat") {
                element.innerHTML = "<div class='border rounded text-bg-light p-2'>" + element.textContent + "</div>";
            }

            let div_accions = tiquets[i].children[8];
            div_accions.style.minWidth = "100px";

        }

    }
}

window.addEventListener("load", (event) => {
    //let paginate_buttons = document.getElementById("data-list-vista_tiquet_paginate");
    //paginate_buttons.addEventListener("click", () => this.actualitzarColorsEstats());
    

    let table = document.getElementById("data-list-vista_tiquet");
    let tbody = table.children.item(1);
    let tiquets = tbody.children;

    for (let i = 0; i < tiquets.length; i++) {

        if (role == "sstt" || role == "admin_sstt" || role == "desenvolupador") {

            let element = tiquets[i].children[6];
            if (element.textContent == "Pendent de recollir") {
                element.innerHTML = "<div class='border rounded text-bg-danger p-2'>" + element.textContent + " <i class='fa-solid fa-circle-exclamation'></i></div>";
            } else if (element.textContent == "Assignat i pendent de recollir") {
                element.innerHTML = "<div class='border rounded text-bg-danger p-2'>" + element.textContent + "</div>";
            } else if (element.textContent == "Emmagatzemat a SSTT") {
                element.innerHTML = "<div class='border rounded text-bg-danger p-2'>" + element.textContent + "</div>";
            } else if (element.textContent == "Assignat i emmagatzemat a SSTT") {
                element.innerHTML = "<div class='border rounded text-bg-danger p-2'>" + element.textContent + "</div>";
            } else if (element.textContent == "Pendent de reparar") {
                element.innerHTML = "<div class='border rounded text-bg-secondary p-2'>" + element.textContent + "</div>";
            } else if (element.textContent == "Reparant") {
                element.innerHTML = "<div class='border rounded text-bg-secondary p-2'>" + element.textContent + "</div>";
            } else if (element.textContent == "Reparat i pendent de recollir") {
                element.innerHTML = "<div class='border rounded text-bg-warning p-2'>" + element.textContent + "</div>";
            } else if (element.textContent == "Pendent de retorn") {
                element.innerHTML = "<div class='border rounded text-bg-warning p-2'>" + element.textContent + "</div>";
            } else if (element.textContent == "Retornat") {
                element.innerHTML = "<div class='border rounded text-bg-light p-2'>" + element.textContent + "</div>";
            } else if (element.textContent == "Rebutjat per SSTT") {
                element.innerHTML = "<div class='border rounded text-bg-light p-2'>" + element.textContent + "</div>";
            } else if (element.textContent == "Desguassat") {
                element.innerHTML = "<div class='border rounded text-bg-light p-2'>" + element.textContent + "</div>";
            }

            let div_accions = tiquets[i].children[10];
            div_accions.style.minWidth = "100px";

        } else if (role == "alumne" || role == "professor") {

            let element = tiquets[i].children[4];
            if (element.textContent == "Pendent de reparar") {
                element.innerHTML = "<div class='border rounded text-bg-danger p-2'>" + element.textContent + "</div>";
            } else if (element.textContent == "Reparant") {
                element.innerHTML = "<div class='border rounded text-bg-danger p-2'>" + element.textContent + "</div>";
            } else if (element.textContent == "Reparat i pendent de recollir") {
                if (role == "alumne") {
                    element.innerHTML = "<div class='border rounded text-bg-secondary p-2'>" + element.textContent + "</div>";
                } else if (role == "professor") {
                    element.innerHTML = "<div class='border rounded text-bg-warning p-2'>" + element.textContent + "</div>";
                }
            }

            if (role == "professor") {
                let div_accions = tiquets[i].children[8];
                div_accions.style.minWidth = "100px";
            }

        } else if (role == "centre_emissor") {

            let element = tiquets[i].children[4];
            if (element.textContent == "Pendent de recollir") {
                element.innerHTML = "<div class='border rounded text-bg-secondary p-2'>" + element.textContent + "</div>";
            } else if (element.textContent == "Assignat i pendent de recollir") {
                element.innerHTML = "<div class='border rounded text-bg-secondary p-2'>" + element.textContent + "</div>";
            } else if (element.textContent == "Emmagatzemat a SSTT") {
                element.innerHTML = "<div class='border rounded text-bg-secondary p-2'>" + element.textContent + "</div>";
            } else if (element.textContent == "Assignat i emmagatzemat a SSTT") {
                element.innerHTML = "<div class='border rounded text-bg-secondary p-2'>" + element.textContent + "</div>";
            } else if (element.textContent == "Pendent de reparar") {
                element.innerHTML = "<div class='border rounded text-bg-danger p-2'>" + element.textContent + "</div>";
            } else if (element.textContent == "Reparant") {
                element.innerHTML = "<div class='border rounded text-bg-danger p-2'>" + element.textContent + "</div>";
            } else if (element.textContent == "Reparat i pendent de recollir") {
                element.innerHTML = "<div class='border rounded text-bg-danger p-2'>" + element.textContent + "</div>";
            } else if (element.textContent == "Pendent de retorn") {
                element.innerHTML = "<div class='border rounded text-bg-warning p-2'>" + element.textContent + "</div>";
            } else if (element.textContent == "Retornat") {
                element.innerHTML = "<div class='border rounded text-bg-success p-2'>" + element.textContent + "</div>";
            } else if (element.textContent == "Rebutjat per SSTT") {
                element.innerHTML = "<div class='border rounded text-bg-light p-2'>" + element.textContent + "</div>";
            } else if (element.textContent == "Desguassat") {
                element.innerHTML = "<div class='border rounded text-bg-light p-2'>" + element.textContent + "</div>";
            }

            let div_accions = tiquets[i].children[8];
            div_accions.style.minWidth = "100px";

        }

    }

  });