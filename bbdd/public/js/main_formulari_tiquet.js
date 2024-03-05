
/**
 * Funció dedicada a afegir files per afegir tiquets
 * 
 * @author Blai Burgués Vicente
 */
var numero_tiquets_afegir = 0;

function afegirTiquet() {
    numero_tiquets_afegir++;

    let div_files_formulari_tiquet = document.getElementById("div_files_formulari_tiquet");

    let div_fila = document.createElement("div");
    div_fila.id = "fila_formulari_tiquet_" + numero_tiquets_afegir;
    div_fila.classList.add("row","p-2");

    let div_col_codi_equip = document.createElement("div");
    div_col_codi_equip.classList.add("col", "d-flex", "align-items-center", "justify-content-center");
    let input_codi_equip = document.createElement("input");
    input_codi_equip.type = "text";
    input_codi_equip.name = "equipment_code_" + numero_tiquets_afegir;
    div_col_codi_equip.appendChild(input_codi_equip);

    let div_col_type = document.createElement("div");
    div_col_type.classList.add("col", "d-flex", "align-items-center", "justify-content-center");
    
    let select_col_type = document.createElement("select");
    select_col_type.name = "type_" + numero_tiquets_afegir;
    for (let i = 0; i < opcions_tipus_dispositius.length; i++) {
        let option_item = document.createElement("option");
        let text_option_item = document.createTextNode(opcions_tipus_dispositius[i]);
        option_item.appendChild(text_option_item);
        select_col_type.appendChild(option_item);
    }
    div_col_type.appendChild(select_col_type);




    let div_col_problem = document.createElement("div");
    div_col_problem.classList.add("col", "d-flex", "align-items-center", "justify-content-center");
    let input_problem = document.createElement("input");
    input_problem.type = "text";
    input_problem.name = "problem_" + numero_tiquets_afegir;
    div_col_problem.appendChild(input_problem);

    let div_espai = document.createElement("div");
    div_espai.classList.add("col");

    div_fila.appendChild(div_col_codi_equip);
    div_fila.appendChild(div_col_type);
    div_fila.appendChild(div_col_problem);
    div_fila.appendChild(div_espai);

    div_files_formulari_tiquet.appendChild(div_fila);

}

function esborrarTiquet(id_fila) {
    let div_fila = document.getElementById(id_fila);
    div_fila.remove();
}