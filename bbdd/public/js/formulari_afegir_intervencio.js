var inventari = [];
var inventari_esborrat = [];
var numero_linea_inventari = 0;

function afegirInventari() {
    numero_linea_inventari++;

    let input_inventari = document.getElementById('intervencioDataListInventari');
    let inventari_id = obtenirID(input_inventari.value);

    let datalist_options = document.getElementById('datalistOptionsInventari').children;
    let tbody_inventari = document.getElementById('tbody_inventari');

    for (let i = 0; i < datalist_options.length; i++) {
        let option_id = obtenirID(datalist_options[i].value);
        if (inventari_id === option_id) {
            let tr = crearFilaInventari(datalist_options[i].value, numero_linea_inventari);
            tbody_inventari.appendChild(tr);

            afegirInventariArrayEsborrat(obtenirNom(datalist_options[i].value), obtenirData(datalist_options[i].value), option_id);
            document.getElementById(datalist_options[i].id).remove();
            console.log(inventari_esborrat);
        }
    }

    console.log("CREEM: " + "linea_inventari_" + numero_linea_inventari);
    input_inventari.value = "";
}

function obtenirNom(value) {
    return value.split("//")[0].trim();
}
function obtenirData(value) {
    return value.split("//")[1].trim();
}
function obtenirID(value) {
    return value.split("//")[2].trim();
}

function afegirInventariArray(nom_inventari, data_inventari, id_inventari) {
    inventari.push({ nom: nom_inventari, data: data_inventari, id: id_inventari });
}

function afegirInventariArrayEsborrat(nom_inventari, data_inventari, id_inventari) {
    inventari_esborrat.push({ nom: nom_inventari, data: data_inventari, id: id_inventari });
}

function crearFilaInventari(value, numero) {
    let nom_inventari = obtenirNom(value);
    let data_inventari = obtenirData(value);
    let id_inventari = obtenirID(value);

    let tr = document.createElement('tr');
    tr.id = "linea_inventari_" + numero;
    tr.classList.add("odd");

    tr.appendChild(crearCela(id_inventari));
    tr.appendChild(crearCela(nom_inventari));
    tr.appendChild(crearCela(data_inventari));
    tr.appendChild(crearCelaBotoEliminar(numero, id_inventari));

    afegirInventariArray(nom_inventari, data_inventari, id_inventari);
    actualitzarInput();

    return tr;
}

function crearCela(text) {
    let td = document.createElement('td');
    td.textContent = text;
    return td;
}

function crearCelaBotoEliminar(numero, id) {
    let td = document.createElement('td');
    let button = document.createElement('button');
    button.type = "button";
    button.classList.add("btn", "btn-danger", "rounded-circle");
    button.title = "Desassignar Peça";
    button.addEventListener("click", () => eliminarInventari("linea_inventari_" + numero, id));

    let icon = document.createElement('i');
    icon.classList.add("fa-solid", "fa-trash");
    icon.setAttribute('aria-hidden', 'true');

    button.appendChild(icon);
    td.appendChild(button);
    return td;
}

function eliminarInventari(id_linea_inventari, id) {

    console.log("ESBORRAR: " + id_linea_inventari);
    let fila = document.getElementById(id_linea_inventari);
    if (fila) {
        fila.remove();
    }

    inventari = inventari.filter(item => item.id !== id);

    let nova_option = inventari_esborrat.filter(item => item.id === id)[0];

    
    let option = document.createElement('option');
    option.id = nova_option.id;
    option.value = nova_option.nom + " // " + nova_option.data + " // " + nova_option.id;
    let option_text = document.createTextNode(nova_option.nom + " // " + nova_option.data + " // " + nova_option.id);
    option.appendChild(option_text);

    inventari_esborrat = inventari_esborrat.filter(item => item.id !== id);

    let datalist = document.getElementById('datalistOptionsInventari');
    datalist.appendChild(option);

    actualitzarInput();
}

function actualitzarInput() {
    document.getElementById('inventari_json').value = JSON.stringify(inventari);
    console.log(JSON.stringify(inventari));
}
