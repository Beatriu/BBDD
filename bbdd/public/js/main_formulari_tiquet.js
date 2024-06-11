
/**
 * Funció dedicada a afegir files per afegir tiquets
 * 
 * @author Blai Burgués Vicente
 */
var numero_tiquets_afegir = 0;
var numero_files = 0;
var tiquets = [];

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
    input_codi_equip.name = "equipment_code_" + numero;
    input_codi_equip.title = "Codi equip línea " + numero;
    input_codi_equip.placeholder = clic_escriu_codi + " " + numero;
    input_codi_equip.addEventListener("input", () => afegirTiquetDisabled('fila_formulari_tiquet_' + numero));
    input_codi_equip.style.backgroundColor = "#e9ecef";
    let button_aleatori = document.createElement("button");
    button_aleatori.type = "button";
    button_aleatori.id = "random_pass_button_" + numero;
    button_aleatori.addEventListener('click', () => this.generar_pass(numero));
    button_aleatori.addEventListener("click", () => afegirTiquetDisabled('fila_formulari_tiquet_' + numero));
    button_aleatori.classList.add('btn', 'rounded-pill', 'ms-1', 'me-3', 'random_pass_button');
    let i_button_aleatori = document.createElement("i");
    i_button_aleatori.classList.add("fa-solid", "fa-shuffle");
    button_aleatori.appendChild(i_button_aleatori);
    
    div_col_codi_equip.appendChild(input_codi_equip);
    div_col_codi_equip.appendChild(button_aleatori);

    let div_col_type = document.createElement("div");
    div_col_type.classList.add("col", "d-flex", "align-items-center", "justify-content-center");
    
    let comptador_tipus_dispositius = 1;
    let select_col_type = document.createElement("select");
    select_col_type.classList.add('form-select');
    select_col_type.id = "type_" + numero;
    select_col_type.name = "type_" + numero;
    select_col_type.title = "Tipus dispositiu línea " + numero;
    select_col_type.style.backgroundColor = "#e9ecef";
    select_col_type.addEventListener("onchange", () => afegirTiquetDisabled('fila_formulari_tiquet_' + numero));
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
    input_problem.name = "problem_" + numero;
    input_problem.title = "Problema línea " + numero;
    input_problem.placeholder = clic_escriu_problema + " " + numero;
    input_problem.addEventListener("input", () => afegirTiquetDisabled('fila_formulari_tiquet_' + numero));
    input_problem.style.backgroundColor = "#e9ecef";
    //input_problem.required = true;
    div_col_problem.appendChild(input_problem);


    /*let numero_borrar = parseInt(numero_tiquets_afegir) + 1;
    let div_borrar = document.createElement("div");
    div_borrar.classList.add("col-1", "d-flex", "align-items-center", "justify-content-center");
    let button_div_borrar = document.createElement("button");
    button_div_borrar.id = "button_borrar_linea_" + numero_borrar;
    button_div_borrar.type = "button";
    button_div_borrar.title = "Botó esborrar línea " + numero_borrar;
    button_div_borrar.classList.add("btn", "btn-danger", "rounded-circle");
    button_div_borrar.addEventListener("click", () => eliminarLinea(numero_borrar));
    let trash_icon = document.createElement("i");
    trash_icon.classList.add("fa-solid", "fa-trash");
    button_div_borrar.appendChild(trash_icon);
    div_borrar.appendChild(button_div_borrar);*/



    div_fila.appendChild(div_col_codi_equip);
    div_fila.appendChild(div_col_type);
    div_fila.appendChild(div_col_problem);
    //div_fila.appendChild(div_borrar);

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

    tiquets[numero] = [equipment_code.value, type.value, problem.value];
    console.log(tiquets);

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


        let div_borrar = document.getElementById('button_borrar_linea_' + numero);
        if (div_borrar == null) {
            
            let div_fila = document.getElementById('fila_formulari_tiquet_' + numero);
    
            let numero_borrar = parseInt(numero_tiquets_afegir) + 1;
            let div_borrar = document.createElement("div");
            div_borrar.id = "button_borrar_linea_" + numero_borrar;
            div_borrar.classList.add("col-1", "d-flex", "align-items-center", "justify-content-center");
            let button_div_borrar = document.createElement("button");
            button_div_borrar.type = "button";
            button_div_borrar.title = "Botó esborrar línea " + numero_borrar;
            button_div_borrar.classList.add("btn", "btn-danger", "rounded-circle");
            button_div_borrar.addEventListener("click", () => eliminarLinea(numero_borrar));
            let trash_icon = document.createElement("i");
            trash_icon.classList.add("fa-solid", "fa-trash");
            button_div_borrar.appendChild(trash_icon);
            div_borrar.appendChild(button_div_borrar);
    
            div_fila.appendChild(div_borrar);
        }


    } else if (equipment_code.value == "" && problem.value == "") {

        equipment_code.name = 'equipment_code_' + numero;
        type.name = 'type_' + numero;
        problem.name = 'problem_' + numero;

        // Eliminem la línea següent, actualitzem l'array i actualitzem els números de control
        if ( numero == (tiquets.length-1)) {
            tiquets.splice(numero,1);
            let numero_seguent = parseInt(numero) + 1;
            let numero_anterior = parseInt(numero) - 1;
            let div_seguent = document.getElementById('fila_formulari_tiquet_' + numero_seguent);
            if (div_seguent != null) {
                div_seguent.remove();
            }

            numero_tiquets_afegir = numero_anterior;
            numero_files = numero_anterior;
            document.getElementById('num_tiquets').value = numero_files;

            let div_borrar = document.getElementById('button_borrar_linea_' + numero);
            if (div_borrar != null) {
                div_borrar.remove();
                // TODO tb eliminarLinea
            }

            if (numero != 1) {
                equipment_code.style.backgroundColor = "#e9ecef";
                type.style.backgroundColor = "#e9ecef";
                problem.style.backgroundColor = "#e9ecef";
                equipment_code.required = false;
                problem.required = false;
    
    
            } 

        } else {

            let numero_linea = numero;

            let llargada_tiquets_antiga = tiquets.length;
            tiquets.splice(numero_linea,1);
            let llargada_tiquets_nova = tiquets.length;
    
            for (let i = numero_linea; i < llargada_tiquets_nova; i++) {
                let equipment_code = document.getElementById('equipment_code_' + i);
                let type = document.getElementById('type_' + i);
                let problem = document.getElementById('problem_' + i);
    
                equipment_code.value = tiquets[i][0];
                type.value = tiquets[i][1];
                problem.value = tiquets[i][2];
            }
    
            // DIV DESHABILITAT
            let equipment_code = document.getElementById('equipment_code_' + llargada_tiquets_nova);
            let type = document.getElementById('type_' + llargada_tiquets_nova);
            let problem = document.getElementById('problem_' + llargada_tiquets_nova);
    
            equipment_code.value = "";
            type.value = 1;
            problem.value = "";
    
            equipment_code.style.backgroundColor = "#e9ecef";
            type.style.backgroundColor = "#e9ecef";
            problem.style.backgroundColor = "#e9ecef";
            equipment_code.required = false;
            problem.required = false;
    
            // ESBORREM EL DIV/BOTO DE BORRAR
            let div_borrar = document.getElementById('button_borrar_linea_' + llargada_tiquets_nova);
            div_borrar.remove();
    
            // DIV BORRAR
            let div_ultim = document.getElementById('fila_formulari_tiquet_' + llargada_tiquets_antiga);
            if (div_ultim != null) {
                div_ultim.remove();
            }
    
            // ACTUALITZEM NÚMEROS DE CONTROL
            numero_tiquets_afegir = llargada_tiquets_nova-1;
            numero_files = llargada_tiquets_nova-1;
            document.getElementById('num_tiquets').value = numero_files;

        }


    }

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
        //document.getElementById("button_afegir_fila_tiquet").disabled = true;

        let camp_sstt = document.getElementById("institutsDataListSSTT");
        if (camp_sstt) {
            camp_sstt.required = false;
            camp_sstt.disabled = true;
        }

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

    let camp_sstt = document.getElementById("institutsDataListSSTT");
    if (camp_sstt) {
        camp_sstt.required = true;
        camp_sstt.disabled = false;
    }

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

function eliminarLinea(numero_linea) {

    let equipment_code = document.getElementById('equipment_code_' + numero_linea);
    let type = document.getElementById('type_' + numero_linea);
    let problem = document.getElementById('problem_' + numero_linea);

    if ( numero_linea == (tiquets.length-1) && numero_linea == 1) {

        equipment_code.value = "";
        type.value = 1;
        problem.value = "";

        let numero = parseInt(numero_linea) + 1;
        let div_seguent = document.getElementById('fila_formulari_tiquet_' + numero);
        if (div_seguent != null) {
            div_seguent.remove();
        }
        
        numero_tiquets_afegir = 0;
        numero_files = 0;
        tiquets.splice(numero_linea,1);

        let div_borrar = document.getElementById('button_borrar_linea_' + numero_linea);
        div_borrar.remove();

    } else if ( numero_linea == (tiquets.length-1) ) {

        equipment_code.value = "";
        type.value = 1;
        problem.value = "";
        equipment_code.style.backgroundColor = "#e9ecef";
        type.style.backgroundColor = "#e9ecef";
        problem.style.backgroundColor = "#e9ecef";
        equipment_code.required = false;
        problem.required = false;

        let numero = parseInt(numero_linea) + 1;
        let div_seguent = document.getElementById('fila_formulari_tiquet_' + numero);
        if (div_seguent != null) {
            div_seguent.remove();
        }
        
        numero_tiquets_afegir = numero_linea-1;
        numero_files = numero_linea-1;
        tiquets.splice(numero_linea,1);

        let div_borrar = document.getElementById('button_borrar_linea_' + numero_linea);
        div_borrar.remove();

    } else {
        let llargada_tiquets_antiga = tiquets.length;
        tiquets.splice(numero_linea,1);
        let llargada_tiquets_nova = tiquets.length;

        for (let i = numero_linea; i < llargada_tiquets_nova; i++) {
            let equipment_code = document.getElementById('equipment_code_' + i);
            let type = document.getElementById('type_' + i);
            let problem = document.getElementById('problem_' + i);

            equipment_code.value = tiquets[i][0];
            type.value = tiquets[i][1];
            problem.value = tiquets[i][2];
        }

        // DIV DESHABILITAT
        let equipment_code = document.getElementById('equipment_code_' + llargada_tiquets_nova);
        let type = document.getElementById('type_' + llargada_tiquets_nova);
        let problem = document.getElementById('problem_' + llargada_tiquets_nova);

        equipment_code.value = "";
        type.value = 1;
        problem.value = "";

        equipment_code.style.backgroundColor = "#e9ecef";
        type.style.backgroundColor = "#e9ecef";
        problem.style.backgroundColor = "#e9ecef";
        equipment_code.required = false;
        problem.required = false;

        // ESBORREM EL DIV/BOTO DE BORRAR
        let div_borrar = document.getElementById('button_borrar_linea_' + llargada_tiquets_nova);
        div_borrar.remove();

        // DIV BORRAR
        let div_ultim = document.getElementById('fila_formulari_tiquet_' + llargada_tiquets_antiga);
        if (div_ultim != null) {
            div_ultim.remove();
        }

        // ACTUALITZEM NÚMEROS DE CONTROL
        numero_tiquets_afegir = llargada_tiquets_nova-1;
        numero_files = llargada_tiquets_nova-1;
    }

    let span = document.getElementById("span_nombre_tiquets");
    span.innerHTML = numero_files;
    document.getElementById('num_tiquets').value = numero_files;
    console.log(tiquets);
    console.log(tiquets.length);
}

function generar_pass(number) {
    let result = '';
    var caracters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890?!*';
    var longitud = 10;
    for (let index = 0; index < longitud; index++) {
        const random = Math.floor(Math.random() * caracters.length);
        result += caracters[random];
    }
    var input = document.getElementById("equipment_code_" + number);
    input.value = '';
    input.value = result;
}