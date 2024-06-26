<?php

// override core en language system validation or define your own en language validation message
return [
    'pageCreateTitle'    => 'Pagina crear noticia',
    'labelNewsTitle'    => 'Titular noticia',
    'labelNewsBody'      => 'Contenido noticia',
    'btnNewsCreate'      => 'Crear noticia',
    'lnkBackNewsList'      => 'Volver a noticias',
    'buttons' => [
        'create' => 'Crear',
        'delete' => 'Eliminar',
        'update' => 'Actualitzar',
        'cancel' => 'Cancelar',
        'buscar_button' => 'Buscar',
    ],
    'banderilla'=>'img' . DIRECTORY_SEPARATOR . 'Banderes' . DIRECTORY_SEPARATOR . 'CA.png',
    'user' => 'Usuario',
    'password' => 'Contraseña',
    'password_confirmar' => 'Confirmar contraseña',
    'tancar' => 'Cerrar sessión',
    'formulari_tiquet' => 'Crear tiquet/s',
    'name' => 'Nombre persona de contacto centro',
    'contact' => 'Correo persona de contacto centro',
    'create_tiquet' => 'Crear tiquet',
    'edit_tiquet' => 'Editar tiquet',
    'equipment_code' => 'Código equipo',
    'type' => 'Tipo',
    'problem' => 'Problema',
    'cancell' => 'Cancelar',
    'save' => 'Emitir tiquet/s',
    'errors_validation' => [
        'sNomContacteCentre_required' => 'El nombre de contacto centro es obligatorio!',
        'sCorreuContacteCentre_required' => 'El correo de contacto de centro es obligatorio!',
        'equipment_code_required' => "El código del equipo és obligatorio!",
        'equipment_code_max' => "El código del equipo puede tener como a máximo 32 caracteres!",
        'problem_required' => "La descripción de la avería és obligatoria!",
        'problem_max' => "La descripción de la avería puede tener como máximo 512 caracteres!",
        'sNomContacteCentre_max' => "El nombre de contacto del centro puede tener como a máximo 64 caracteres!",
        'sCorreuContacteCentre_max' => "El correo de contacto del centro puede tener como máximo 32 caracteres!",
    ],
    'nom_usuari_required' => "Tienes que introducir el nombre de usuario!",
    'contrasenya_required' => 'Tienes que introducir la contraseña!',
    'contrasenya_min_length' => "La contraseña tiene como mínimo 6 caracteres!",
    'nom_usuari_required_max_length' => "El nombre de usuario puede tener como máximo 32 caracteres!",
    'confirmar_matches' => 'Tienes que confirmar la contraseña. Los campos contraseña i confrimación no corresponden.',
    'config' => "Configuración",
    'tancar' => "Cerrar sessión",
    'tornar' => "Volver",
    'centres_datalist' => "Escribe para buscar...",
    'centre_emissor' => "Escribe para buscar el CENTRO EMISOR...",
    'centre_reparador' => "Escribe para buscar el CENTRO REPARADOR...",
    'centre_emissor_curt' => "Asignar centro emisor",
    'centre_reparador_curt' => "Asignar centro reparador",
    'afegir_tiquet' => "Añadir tiquet",
    'nombre_tiquets' => 'El numero de tiquets que estas emitiendo es:',
    'informacio_comuna' => 'Información comuna tiquet/s',
    'informacio_tiquet' => 'Información tiquet',
    'plantilla_csv' => 'Plantilla CSV',
    'importar_csv' => 'Importar CSV',
    'afegir_linea' => "Afegir línea",
    'clic_escriu_codi' => "Haz clic y escribe el código del dispositivo",
    'clic_escriu_problema' => "Haz clic y escribe el problema del dispositivo",
    'sstt' => 'Servicios Territoriales',
    'generate_equipment_code' => "Genera un código de equipo aleatorio",
    'nota_login_professsorat' => "Inicio de sessión profesorado: "
];