CREATE VIEW vista_tiquet AS
SELECT 
    t.id_tiquet,
    t.codi_equip,
    LEFT(t.descripcio_avaria, 50) AS descripcio_avaria,
    IF(LENGTH(t.descripcio_avaria) > 50, CONCAT(LEFT(t.descripcio_avaria, 47), '...'), t.descripcio_avaria) AS descripcio_avaria_limitada,
    t.nom_persona_contacte_centre,
    t.correu_persona_contacte_centre,
    DATE_FORMAT(t.data_alta, '%d-%m-%Y') AS data_alta_format,
    DATE_FORMAT(t.data_alta, '%H:%i:%s') AS hora_alta_format,
    DATE_FORMAT(t.data_ultima_modificacio, '%d-%m-%Y') AS data_ultima_modificacio_format,
    DATE_FORMAT(t.data_ultima_modificacio, '%H:%i:%s') AS hora_ultima_modificacio_format,
    td.nom_tipus_dispositiu, 
    e.nom_estat, 
    t.codi_centre_emissor,
    t.codi_centre_reparador,
    ce.nom_centre AS nom_centre_emissor,
    cr.nom_centre AS nom_centre_reparador
FROM tiquet t
LEFT JOIN centre ce ON t.codi_centre_emissor = ce.codi_centre
LEFT JOIN centre cr ON t.codi_centre_reparador = cr.codi_centre
LEFT JOIN tipus_dispositiu td ON t.id_tipus_dispositiu = td.id_tipus_dispositiu
LEFT JOIN estat e ON t.id_estat = e.id_estat;


CREATE VIEW vista_alumne AS
SELECT a.*, c.id_sstt, c.nom_centre
FROM alumne a
INNER JOIN centre c ON a.codi_centre = c.codi_centre;