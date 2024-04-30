
/**
 * Funció dedicada a afegir files per afegir tiquets
 * 
 * @author Blai Burgués Vicente
 */
var numero_tiquets_afegir = 1;
var numero_files = 1;

function afegirTiquet() {
    numero_tiquets_afegir++;
    numero_files++;

    let div_files_formulari_tiquet = document.getElementById("div_files_formulari_tiquet");

    let div_fila = document.createElement("div");
    div_fila.id = "fila_formulari_tiquet_" + numero_tiquets_afegir;
    div_fila.classList.add("row","p-2");

    let div_col_codi_equip = document.createElement("div");
    div_col_codi_equip.classList.add("col", "d-flex", "align-items-center", "justify-content-center");
    let input_codi_equip = document.createElement("input");
    input_codi_equip.type = "text";
    input_codi_equip.name = "equipment_code_" + numero_tiquets_afegir;
    input_codi_equip.required = true;
    div_col_codi_equip.appendChild(input_codi_equip);

    let div_col_type = document.createElement("div");
    div_col_type.classList.add("col", "d-flex", "align-items-center", "justify-content-center");
    
    let comptador_tipus_dispositius = 1;
    let select_col_type = document.createElement("select");
    select_col_type.name = "type_" + numero_tiquets_afegir;
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
    input_problem.style.width = '100%';
    input_problem.type = "text";
    input_problem.name = "problem_" + numero_tiquets_afegir;
    input_problem.required = true;
    div_col_problem.appendChild(input_problem);

    let id_borrar = "fila_formulari_tiquet_" + numero_tiquets_afegir;

    let div_borrar = document.createElement("div");
    div_borrar.classList.add("col", "d-flex", "align-items-center", "justify-content-center");
    let button_div_borrar = document.createElement("button");
    button_div_borrar.type = "button";
    button_div_borrar.classList.add("btn", "btn-danger", "rounded-circle");
    button_div_borrar.addEventListener("click", () => esborrarTiquet(id_borrar));
    let trash_icon = document.createElement("i");
    trash_icon.classList.add("fa-solid", "fa-trash");
    button_div_borrar.appendChild(trash_icon);
    div_borrar.appendChild(button_div_borrar);

    div_fila.appendChild(div_col_codi_equip);
    div_fila.appendChild(div_col_type);
    div_fila.appendChild(div_col_problem);
    div_fila.appendChild(div_borrar);

    div_files_formulari_tiquet.appendChild(div_fila);

    sumarNumTiquet();

    let span = document.getElementById("span_nombre_tiquets");
    span.innerHTML = numero_files;
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
        document.getElementById("button_afegir_fila_tiquet").disabled = true;
        document.getElementById("equipment_code_1").disabled = true;
        document.getElementById("type_1").disabled = true;
        document.getElementById("problem_1").disabled = true;
        document.getElementById("cancelar_importar_csv").style.display = "inline";
        document.getElementById("div_csv").style.display = "none";

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
    document.getElementById("button_afegir_fila_tiquet").disabled = false;
    document.getElementById("equipment_code_1").disabled = false;
    document.getElementById("type_1").disabled = false;
    document.getElementById("problem_1").disabled = false;
    document.getElementById("cancelar_importar_csv").style.display = "none";
    document.getElementById("div_csv").style.display = "inline";

    document.getElementById("csv_tiquet").value = null;
    document.getElementById("mostrar_csv").innerHTML = "";
}