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

    ],
    'banderilla'=>'img' . DIRECTORY_SEPARATOR . 'Banderes' . DIRECTORY_SEPARATOR . 'ES.png',
    'user' => 'Usuari',
    'password' => 'Contrasenya',
    'password_confirmar' => 'Confirmar contrasenya',
    'tancar' => 'Tancar sessió',
    'formulari_tiquet' => 'Crear Tiquet',
    'name' => 'Nom persona de contacte centre',
    'contact' => 'Correu persona de contacte centre',
    'create_tiquet' => 'Crear tiquet/s',
    'tiquet' => 'Tiquet',
    'edit_tiquet' => 'Editar tiquet',
    'equipment_code' => 'Codi equip',
    'type' => 'Tipus',
    'problem' => 'Problema',
    'cancell' => 'Cancel·lar',
    'save' => 'Emetre tiquet/s',
    'errors_validation' => [
        'sNomContacteCentre_required' => 'El nom de contacte centre és obligatori!',
        'sCorreuContacteCentre_required' => 'El correu de contacte de centre és obligatori!',
        'equipment_code_required' => "El codi de l'equip és obligatori!",
        'equipment_code_max' => "El codi de l'equip pot tenir com a màxim 32 caràcters!",
        'problem_required' => "La descripció de l'avaria és obligatoria!",
        'problem_max' => "La descripció de l'avaria pot tenir com a màxim 512 caràcters!",
        'sNomContacteCentre_max' => "El nom de contacte del centre pot tenir com a màxim 64 caràcters!",
        'sCorreuContacteCentre_max' => "El correu de contacte del centre pot tenir com a màxim 32 caràcters!",
    ],
    'nom_usuari_required' => "Has d'introduir el nom d'usuari!",
    'contrasenya_required' => "Has d'introduir la contrasenya!",
    'contrasenya_min_length' => "La contrasenya ha de tenir mínim 6 caràcters!",
    'nom_usuari_required_max_length' => "El nom d'usuari pot tenir com a màxim 32 caràcters!",
    'confirmar_matches' => 'Has de confirmar la contrasenya. Els camps contrasenya i confrimació no corresponen.',
    'config' => "Configuració",
    'tancar' => "Tancar sessió",
    'tornar' => "Tornar",
    'centres_datalist' => "Escriu per buscar...",
    'centre_emissor' => "Escriu per buscar el CENTRE EMISSOR...",
    'centre_reparador' => "Escriu per buscar el CENTRE REPARADOR...",
    'centre_emissor_curt' => "Assignar centre emissor",
    'centre_reparador_curt' => "Assignar centre reparador",
    'afegir_tiquet' => "Afegir tiquet",
    'nombre_tiquets' => 'El nombre de tiquets que estàs emetent és:',
    'informacio_comuna' => 'Informació comuna tiquet/s',
    'informacio_tiquet' => 'Informació tiquet',
    'plantilla_csv' => 'Plantilla CSV',
    'importar_csv' => 'Importar CSV',
    'dades_tiquet' => [
        'dades_titol' => 'Dades',
        'dades_codi' => 'Codi:',
        'dades_tipus' => 'Tipus:',
        'dades_estat' => 'Estat informatiu:',
        'dades_intervencions' => 'Intervencions del tiquet:',
    ],
];