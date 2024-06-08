CREATE VIEW vista_tiquet AS
SELECT 
    t.id_tiquet,
    SUBSTRING_INDEX(t.id_tiquet, '-', -1) AS id_tiquet_limitat,
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
    t.preu_total,
    t.codi_centre_emissor,
    t.codi_centre_reparador,
    t.id_sstt,
    ce.nom_centre AS nom_centre_emissor,
    cr.nom_centre AS nom_centre_reparador,
    ce.id_sstt AS id_sstt_emissor,
    cr.id_sstt AS id_sstt_reparador,
    pe.id_poblacio AS id_poblacio_emissor,
    pr.id_poblacio AS id_poblacio_reparador,
    pe.nom_poblacio AS nom_poblacio_emissor,
    pr.nom_poblacio AS nom_poblacio_reparador,
    come.id_comarca AS id_comarca_emissor,
    comr.id_comarca AS id_comarca_reparador,
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
SELECT a.*, c.id_sstt, c.nom_centre, p.id_poblacio, p.nom_poblacio, com.id_comarca ,com.nom_comarca
FROM alumne a
INNER JOIN centre c ON a.codi_centre = c.codi_centre
INNER JOIN poblacio p ON c.id_poblacio = p.id_poblacio
INNER JOIN comarca com ON p.id_comarca = com.id_comarca;


CREATE VIEW vista_intervencio AS
SELECT 
    i.id_intervencio,
    SUBSTRING_INDEX(i.id_intervencio, '-', -1) AS id_intervencio_limitat,
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
    SUBSTRING_INDEX(i.id_inventari, '-', -1) AS id_inventari_limitat,
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
    s.nom_sstt,
    p.id_poblacio,
    p.nom_poblacio,
    com.id_comarca,
    com.nom_comarca
FROM 
    inventari i
JOIN 
    centre c ON i.codi_centre = c.codi_centre
JOIN 
    tipus_inventari ti ON i.id_tipus_inventari = ti.id_tipus_inventari
JOIN
    sstt s ON c.id_sstt = s.id_sstt
JOIN poblacio p ON c.id_poblacio = p.id_poblacio
JOIN comarca com ON p.id_comarca = com.id_comarca;


CREATE VIEW vista_centres AS
SELECT 
    c.codi_centre,
    c.nom_centre,
    CASE 
        WHEN c.actiu = 1 THEN 'Si'
        ELSE 'No'
    END AS actiu,
    CASE 
        WHEN c.taller = 1 THEN 'Si'
        ELSE 'No'
    END AS taller,
    c.telefon_centre,
    c.nom_persona_contacte_centre,
    c.correu_persona_contacte_centre,
    c.id_sstt,
    c.login,
    p.id_poblacio,
    p.nom_poblacio,
    com.id_comarca,
    com.nom_comarca,
    (SELECT SUM(t.preu_total) 
     FROM tiquet t 
     WHERE t.codi_centre_reparador = c.codi_centre) AS Preu_total,
    (SELECT count(t.id_tiquet)
    FROM tiquet t
    WHERE t.codi_centre_reparador = c.codi_centre) AS Tiquets_del_centre

FROM 
    centre c
JOIN poblacio p ON c.id_poblacio = p.id_poblacio
JOIN comarca com ON p.id_comarca = com.id_comarca;