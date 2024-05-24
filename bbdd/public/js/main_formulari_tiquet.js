
/**
 * Funció dedicada a afegir files per afegir tiquets
 * 
 * @author Blai Burgués Vicente
 */
var numero_tiquets_afegir = 0;
var numero_files = 0;

function afegirTiquet() {
    numero_tiquets_afegir++;
    numero_files++;

    let numero = parseInt(numero_tiquets_afegir) + 1;

    let div_files_formulari_tiquet = document.getElementById("div_files_formulari_tiquet");

    let div_fila = document.createElement("div");
    div_fila.id = "fila_formulari_tiquet_" + numero;
    div_fila.classList.add("row","p-2");

    let div_col_codi_equip = document.createElement("div");
    div_col_codi_equip.classList.add("col", "d-flex", "align-items-center", "justify-content-center");
    let input_codi_equip = document.createElement("input");
    input_codi_equip.classList.add('form-control');
    input_codi_equip.type = "text";
    input_codi_equip.id = "equipment_code_" + numero;
    input_codi_equip.name = "provisional_equipment_code_" + numero;
    input_codi_equip.title = "Codi equip línea " + numero;
    input_codi_equip.addEventListener("input", () => afegirTiquetDisabled('fila_formulari_tiquet_' + numero));
    input_codi_equip.style.backgroundColor = "#e9ecef";
    //input_codi_equip.required = true;
    div_col_codi_equip.appendChild(input_codi_equip);

    let div_col_type = document.createElement("div");
    div_col_type.classList.add("col", "d-flex", "align-items-center", "justify-content-center");
    
    let comptador_tipus_dispositius = 1;
    let select_col_type = document.createElement("select");
    select_col_type.classList.add('form-select');
    select_col_type.id = "type_" + numero;
    select_col_type.name = "provisional_type_" + numero;
    select_col_type.title = "Tipus dispositiu línea " + numero;
    select_col_type.style.backgroundColor = "#e9ecef";
    for (let i = 0; i < opcions_tipus_dispositius.length; i++) {
        let option_item = document.createElement("option");
        option_item.value = comptador_tipus_dispositius;
        let text_option_item = document.createTextNode(opcions_tipus_dispositius[i]);
        option_item.appendChild(text_option_item);
        select_col_type.appendChild(option_item);
        comptador_tipus_dispositius++;
    }
    div_col_type.appendChild(select_col_type);


    let div_col_problem = document.createElement("div");
    div_col_problem.classList.add("col", "d-flex", "align-items-center", "justify-content-center");
    let input_problem = document.createElement("textarea");
    input_problem.classList.add('form-control');
    input_problem.style.width = '100%';
    input_problem.style.height = '30px';
    input_problem.type = "text";
    input_problem.id = "problem_" + numero;
    input_problem.name = "provisional_problem_" + numero;
    input_problem.title = "Problema línea " + numero;
    input_problem.addEventListener("input", () => afegirTiquetDisabled('fila_formulari_tiquet_' + numero));
    input_problem.style.backgroundColor = "#e9ecef";
    //input_problem.required = true;
    div_col_problem.appendChild(input_problem);


    div_fila.appendChild(div_col_codi_equip);
    div_fila.appendChild(div_col_type);
    div_fila.appendChild(div_col_problem);

    div_files_formulari_tiquet.appendChild(div_fila);

    sumarNumTiquet();

    let span = document.getElementById("span_nombre_tiquets");
    span.innerHTML = numero_files;
}

function afegirTiquetDisabled(id_fila) {
    let numero = id_fila.split("_")[3];
    console.log('equipment_code_' + numero);

    let equipment_code = document.getElementById('equipment_code_' + numero);
    let type = document.getElementById('type_' + numero);
    let problem = document.getElementById('problem_' + numero);

    if (equipment_code.value != "" && problem.value != "" && numero_files == (parseInt(numero)-1) ) {
        afegirTiquet();
    }

    if (equipment_code.value != "" || problem.value != "") {

        equipment_code.name = 'equipment_code_' + numero;
        equipment_code.style.backgroundColor = "#ffffff";
        type.name = 'type_' + numero;
        type.style.backgroundColor = "#ffffff";
        problem.name = 'problem_' + numero;
        problem.style.backgroundColor = "#ffffff";
        equipment_code.required = true;
        problem.required = true;

    } else if (equipment_code.value == "" && problem.value == "") {

        equipment_code.name = 'provisional_equipment_code_' + numero;
        type.name = 'provisional_type_' + numero;
        problem.name = 'provisional_problem_' + numero;

        if (numero != 1) {
            equipment_code.style.backgroundColor = "#e9ecef";
            type.style.backgroundColor = "#e9ecef";
            problem.style.backgroundColor = "#e9ecef";
            equipment_code.required = false;
            problem.required = false;
        }

    }
}


function esborrarTiquet(id_fila) {
    if (numero_files > 1) {
        let div_fila = document.getElementById(id_fila);
        div_fila.remove();
        numero_files--;
        restarNumTiquet();
    }

    let span = document.getElementById("span_nombre_tiquets");
    span.innerHTML = numero_files;
}

function sumarNumTiquet() {
    let num = document.getElementById('num_tiquets').value;

    num = parseInt(num) + 1;

    document.getElementById('num_tiquets').value = num;
}

function restarNumTiquet() {
    let num = document.getElementById('num_tiquets').value;

    num = parseInt(num) - 1;

    document.getElementById('num_tiquets').value = num;

}

function afegirFitxer() {
    document.getElementById('csv_tiquet').click();
}

function mostrarFitxers(input) {
    document.getElementById("div_csv").classList.add("text-white");

    if (input.files[0].type == "text/csv") {
        let span = document.getElementById("mostrar_csv");
        span.innerHTML = "";
        let span_text = document.createTextNode(input.files[0].name);
        span.appendChild(span_text);

        document.getElementById("sNomContacteCentre").required = false;
        document.getElementById("sNomContacteCentre").disabled = true;
        document.getElementById("sCorreuContacteCentre").required = false;
        document.getElementById("sCorreuContacteCentre").disabled = true;
        //document.getElementById("button_afegir_fila_tiquet").disabled = true;

        document.getElementById("cancelar_importar_csv").style.display = "inline"; // Botó Cancel·lar
        document.getElementById("div_csv").style.display = "none"; // Botó Importar CSV
        document.getElementById("div_csv_descarregar").style.display = "none"; // Botó Importar CSV
        document.getElementById("submit_afegir").style.display = "none"; // Botó Emetre Tiquet de baix
        document.getElementById("submit_afegir_csv").classList.remove("d-none"); // Botó Emetre Tiquet de dalt

        // Files formulari - Treure required i deshabilitar
        let div_files_formulari = document.getElementById("div_files_formulari_tiquet");
        for (let j = 0; j < div_files_formulari.children.length; j++) {
            let columnes = div_files_formulari.children[j].children;
            for (let k = 0; k < columnes.length; k++) {
                console.log(columnes[k].children[0]);
                columnes[k].children[0].required = false;
                columnes[k].children[0].disabled = true;
                columnes[k].children[0].style.backgroundColor = "#e9ecef";
            }
        }

    } else {
        input.value = null;
        alert("No és un CSV!");
    }

}

function cancellFitxer() {
    document.getElementById("sNomContacteCentre").required = true;
    document.getElementById("sNomContacteCentre").disabled = false;
    document.getElementById("sCorreuContacteCentre").required = true;
    document.getElementById("sCorreuContacteCentre").disabled = false;
    //document.getElementById("button_afegir_fila_tiquet").disabled = false;

    document.getElementById("cancelar_importar_csv").style.display = "none";
    document.getElementById("div_csv").style.display = "inline";
    document.getElementById("div_csv_descarregar").style.display = "inline"; // Botó Importar CSV
    document.getElementById("submit_afegir").style.display = "inline";
    document.getElementById("submit_afegir_csv").classList.add("d-none"); // Botó Emetre Tiquet de dalt

    // Files formulari - Treure required i deshabilitar
    let div_files_formulari = document.getElementById("div_files_formulari_tiquet");
    for (let j = 0; j < div_files_formulari.children.length; j++) {
        let columnes = div_files_formulari.children[j].children;
        for (let k = 0; k < columnes.length; k++) {
            console.log(columnes[k].children[0]);
            columnes[k].children[0].required = true;
            columnes[k].children[0].disabled = false;

            if (j == (div_files_formulari.children.length-1) && j != 0 && columnes[0].children[0].value == "" && columnes[2].children[0].value == "") {
                console.log(columnes[0].children[0].value);
                columnes[k].children[0].style.backgroundColor = "#e9ecef";
            } else {
                columnes[k].children[0].style.backgroundColor = "#fff";
            }
        }
    }

    document.getElementById("csv_tiquet").value = null;
    document.getElementById("mostrar_csv").innerHTML = "";
}