
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
    let input_problem = document.createElement("input");
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
}

function esborrarTiquet(id_fila) {
    if (numero_files > 1) {
        let div_fila = document.getElementById(id_fila);
        div_fila.remove();
        numero_files--;
        restarNumTiquet();
    }
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