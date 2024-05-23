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
    t.id_estat,
    e.nom_estat, 
    t.codi_centre_emissor,
    t.codi_centre_reparador,
    ce.nom_centre AS nom_centre_emissor,
    cr.nom_centre AS nom_centre_reparador,
    ce.id_sstt AS id_sstt_emissor,
    ce.id_poblacio AS id_poblacio_emissor,
    cr.id_poblacio AS id_poblacio_reparador,
    pe.nom_poblacio AS nom_poblacio_emissor,
    pr.nom_poblacio AS nom_poblacio_reparador,
    pe.id_comarca,
    pr.id_comarca,
    come.nom_comarca AS nom_comarca_emissor,
    comr.nom_comarca AS nom_comarca_reparador	
    
FROM tiquet t
LEFT JOIN centre ce ON t.codi_centre_emissor = ce.codi_centre
LEFT JOIN centre cr ON t.codi_centre_reparador = cr.codi_centre
LEFT JOIN tipus_dispositiu td ON t.id_tipus_dispositiu = td.id_tipus_dispositiu
LEFT JOIN estat e ON t.id_estat = e.id_estat
LEFT JOIN poblacio pe ON ce.id_poblacio = pe.id_poblacio
LEFT JOIN poblacio pr ON cr.id_poblacio = pr.id_poblacio
LEFT JOIN comarca come ON pe.id_comarca = come.id_comarca
LEFT JOIN comarca comr ON pr.id_comarca = comr.id_comarca;



CREATE VIEW vista_alumne AS
SELECT a.*, c.id_sstt, c.nom_centre
FROM alumne a
INNER JOIN centre c ON a.codi_centre = c.codi_centre;


CREATE VIEW vista_intervencio AS
SELECT 
    i.id_intervencio,
    i.id_tiquet,
    LEFT(i.descripcio_intervencio, 25) AS descripcio_intervencio,
    IF(LENGTH(i.descripcio_intervencio) > 25, CONCAT(LEFT(i.descripcio_intervencio, 22), '...'), i.descripcio_intervencio) AS descripcio_intervencio_limitada,
    i.data_intervencio,
    i.correu_alumne,
    i.id_xtec,
    i.id_tipus_intervencio,
    ti.nom_tipus_intervencio,
    e.nom_estat AS estat_tiquet
FROM intervencio i
LEFT JOIN tiquet t ON i.id_tiquet = t.id_tiquet
LEFT JOIN estat e ON t.id_estat = e.id_estat
LEFT JOIN tipus_intervencio ti ON i.id_tipus_intervencio = ti.id_tipus_intervencio;


CREATE VIEW vista_inventari AS
SELECT 
    i.id_inventari,
    LEFT(i.descripcio_inventari, 50) AS descripcio_inventari_limitada,
    IF(CHAR_LENGTH(i.descripcio_inventari) > 50, CONCAT(LEFT(i.descripcio_inventari, 47), '...'), i.descripcio_inventari) AS descripcio_inventari,
    i.data_compra,
    i.preu,
    i.id_tipus_inventari,
    ti.nom_tipus_inventari,
    i.id_intervencio,
    i.codi_centre,
    c.nom_centre,
    c.id_sstt,
    s.nom_sstt
FROM 
    inventari i
JOIN 
    centre c ON i.codi_centre = c.codi_centre
JOIN 
    tipus_inventari ti ON i.id_tipus_inventari = ti.id_tipus_inventari
JOIN
    sstt s ON c.id_sstt = s.id_sstt;



