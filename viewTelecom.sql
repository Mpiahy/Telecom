-- VUE EQUIPEMENT = PHONE
create or replace view view_equipement_phones as 
    select * from equipement where id_type_equipement in (1, 2);
select * from view_equipement_phones;

-- VUE EQUIPEMENT = BOX
create or replace view view_equipement_box as 
    select * from equipement where id_type_equipement = 3;
select * from view_equipement_box;

-- View pour les marques Phone (id_marque commence par 1 ou 2)
CREATE OR REPLACE VIEW view_marque_phone AS
SELECT * 
FROM marque
WHERE id_marque >= 1000 AND id_marque < 3000;

-- View pour les marques Box (id_marque commence par 3)
CREATE OR REPLACE VIEW view_marque_box AS
SELECT * 
FROM marque
WHERE id_marque >= 3000 AND id_marque < 4000;

-- View pour les modèles Phone (id_modele commence par 1 ou 2)
CREATE OR REPLACE VIEW view_modele_phone AS
SELECT * 
FROM modele
WHERE id_modele >= 1000000 AND id_modele < 3000000;

-- View pour les modèles Box (id_modele commence par 3)
CREATE OR REPLACE VIEW view_modele_box AS
SELECT * 
FROM modele
WHERE id_modele >= 3000000 AND id_modele < 4000000;

-- View pour prix_total_element = prix_unitaire_element * quantite_element
CREATE or REPLACE VIEW view_element_prix AS
SELECT
    fe.id_forfait,
    e.id_element,
    e.libelle,
    fe.quantite,
    e.unite,
    e.prix_unitaire_element,
    (fe.quantite * e.prix_unitaire_element) AS prix_total_element
FROM
    forfait_element fe
JOIN
    element e ON fe.id_element = e.id_element;

-- View pour prix_forfait_ht = prix_forfait_ht_non_remise + droit_d_accise - remise_pied_de_page
CREATE or REPLACE VIEW view_forfait_prix AS
SELECT
    sub.id_forfait,
    sub.nom_forfait,
    sub.prix_forfait_ht_non_remise,
    sub.droit_d_accise,
    sub.remise_pied_de_page,
    (sub.prix_forfait_ht_non_remise + sub.droit_d_accise - sub.remise_pied_de_page) AS prix_forfait_ht
FROM (
    SELECT
        f.id_forfait,
        f.nom_forfait,
        SUM(fe.quantite * e.prix_unitaire_element) AS prix_forfait_ht_non_remise,
        SUM(fe.quantite * e.prix_unitaire_element) * 0.08 AS droit_d_accise,
        SUM(fe.quantite * e.prix_unitaire_element) * 0.216 AS remise_pied_de_page
    FROM
        forfait f
    JOIN
        forfait_element fe ON f.id_forfait = fe.id_forfait
    JOIN
        element e ON fe.id_element = e.id_element
    GROUP BY
        f.id_forfait, f.nom_forfait
) sub;

-- View pour détails d'une ligne
CREATE OR REPLACE VIEW view_ligne_details AS
SELECT
    ligne.id_ligne,
    ligne.num_ligne,
    ligne.num_sim,
    ligne.id_forfait,
    ligne.id_statut_ligne,
    ligne.id_type_ligne,
    ligne.id_operateur,
    recent_affectation.id_affectation,
    recent_affectation.id_utilisateur
FROM
    ligne
    LEFT JOIN (
        SELECT DISTINCT ON (id_ligne) 
            id_ligne,
            id_affectation,
            id_utilisateur,
            debut_affectation
        FROM affectation
        ORDER BY id_ligne, debut_affectation DESC, id_affectation DESC
    ) AS recent_affectation
ON ligne.id_ligne = recent_affectation.id_ligne;

-- View pour big détails d'une ligne
CREATE OR REPLACE VIEW view_ligne_big_details AS
SELECT 
    vld.id_ligne,
    vld.num_ligne,
    vld.num_sim,
    vld.id_forfait,
    forfait.nom_forfait,
    vld.id_statut_ligne,
    statut_ligne.statut_ligne,
    vld.id_type_ligne,
    type_ligne.type_ligne,
    vld.id_operateur,
    operateur.nom_operateur,
    contact_operateur.email AS contact_email,
    vld.id_utilisateur,
    utilisateur.login,
    localisation.localisation,
    vfp.prix_forfait_ht,
    vld.id_affectation,
    affectation.debut_affectation,
    affectation.fin_affectation
FROM 
    view_ligne_details vld
LEFT JOIN forfait ON vld.id_forfait = forfait.id_forfait
LEFT JOIN statut_ligne ON vld.id_statut_ligne = statut_ligne.id_statut_ligne
LEFT JOIN type_ligne ON vld.id_type_ligne = type_ligne.id_type_ligne
LEFT JOIN operateur ON vld.id_operateur = operateur.id_operateur
LEFT JOIN contact_operateur ON vld.id_operateur = contact_operateur.id_operateur
LEFT JOIN view_forfait_prix vfp ON vld.id_forfait = vfp.id_forfait
LEFT JOIN utilisateur ON vld.id_utilisateur = utilisateur.id_utilisateur
LEFT JOIN localisation ON utilisateur.id_localisation = localisation.id_localisation
LEFT JOIN affectation ON vld.id_affectation = affectation.id_affectation;

-- View pour count_ligne
CREATE VIEW view_ligne_actif AS
SELECT *
FROM ligne
WHERE id_statut_ligne = 3;

CREATE VIEW view_ligne_en_attente AS
SELECT *
FROM ligne
WHERE id_statut_ligne = 2;

CREATE OR REPLACE VIEW view_ligne_resilie AS
SELECT *
FROM ligne
WHERE id_statut_ligne IN (1, 4);

-- View pour count_equipement
CREATE VIEW view_equipement_actif AS
SELECT *
FROM equipement
WHERE id_statut_equipement = 2;

CREATE VIEW view_equipement_inactif AS
SELECT *
FROM equipement
WHERE id_statut_equipement IN (1, 3);

CREATE OR REPLACE VIEW view_equipement_hs AS
SELECT *
FROM equipement
WHERE id_statut_equipement = 4;

-- View pour avoir Phones inactifs (Smart & à touche)
CREATE VIEW view_phones_inactif AS
SELECT 
    e.id_equipement, 
    e.imei, 
    e.serial_number, 
    ma.marque, 
    mo.nom_modele AS modele
FROM 
    view_equipement_inactif e
JOIN 
    modele mo ON e.id_modele = mo.id_modele
JOIN 
    marque ma ON mo.id_marque = ma.id_marque
WHERE 
    e.id_type_equipement IN (1, 2);

-- View pour avoir Box inactifs
CREATE VIEW view_box_inactif AS
SELECT 
    e.id_equipement, 
    e.imei, 
    e.serial_number, 
    ma.marque, 
    mo.nom_modele AS modele
FROM 
    view_equipement_inactif e
JOIN 
    modele mo ON e.id_modele = mo.id_modele
JOIN 
    marque ma ON mo.id_marque = ma.id_marque
WHERE 
    e.id_type_equipement = 3;

-- View pour avoir Phones avec détails d'affectation
CREATE OR REPLACE VIEW view_phones_details AS
SELECT 
    e.id_equipement,
    m.marque,
    mo.nom_modele AS modele,
    e.imei,
    e.serial_number,
    te.type_equipement,
    se.statut_equipement,
    e.enrole,
    u.nom,
    u.prenom,
    u.login,
    l.localisation,
    a.id_affectation,
    a.debut_affectation,
    a.fin_affectation
FROM view_equipement_phones e
LEFT JOIN modele mo ON e.id_modele = mo.id_modele
LEFT JOIN marque m ON mo.id_marque = m.id_marque
LEFT JOIN type_equipement te ON e.id_type_equipement = te.id_type_equipement
LEFT JOIN statut_equipement se ON e.id_statut_equipement = se.id_statut_equipement
LEFT JOIN affectation a ON e.id_equipement = a.id_equipement
LEFT JOIN utilisateur u ON a.id_utilisateur = u.id_utilisateur
LEFT JOIN localisation l ON u.id_localisation = l.id_localisation;

-- View pour avoir Box avec détails d'affectation
CREATE OR REPLACE VIEW view_box_details AS
SELECT 
    e.id_equipement,
    m.marque,
    mo.nom_modele AS modele,
    e.imei,
    e.serial_number,
    te.type_equipement,
    se.statut_equipement,
    u.nom,
    u.prenom,
    u.login,
    l.localisation,
    a.id_affectation,
    a.debut_affectation,
    a.fin_affectation
FROM view_equipement_box e
LEFT JOIN modele mo ON e.id_modele = mo.id_modele
LEFT JOIN marque m ON mo.id_marque = m.id_marque
LEFT JOIN type_equipement te ON e.id_type_equipement = te.id_type_equipement
LEFT JOIN statut_equipement se ON e.id_statut_equipement = se.id_statut_equipement
LEFT JOIN affectation a ON e.id_equipement = a.id_equipement
LEFT JOIN utilisateur u ON a.id_utilisateur = u.id_utilisateur
LEFT JOIN localisation l ON u.id_localisation = l.id_localisation;

CREATE OR REPLACE VIEW view_historique_equipement AS
SELECT 
    aff.id_equipement,
    u.nom AS utilisateur_nom,
    u.prenom AS utilisateur_prenom,
    u.login AS utilisateur_login,
    loc.localisation AS localisation,
    aff.debut_affectation,
    aff.fin_affectation
FROM 
    (SELECT * FROM affectation WHERE id_equipement IS NOT NULL) aff
INNER JOIN utilisateur u ON aff.id_utilisateur = u.id_utilisateur
LEFT JOIN localisation loc ON u.id_localisation = loc.id_localisation;

CREATE OR REPLACE VIEW view_historique_ligne AS
SELECT 
    l.id_ligne,
    u.nom,
    u.prenom,
    u.login,
    loc.localisation,
    l.num_ligne,
    l.num_sim,
    tf.type_forfait,
    f.nom_forfait AS forfait,
    vfp.prix_forfait_ht,
    a.debut_affectation,
    a.fin_affectation
FROM 
    affectation a
INNER JOIN 
    utilisateur u ON a.id_utilisateur = u.id_utilisateur
INNER JOIN 
    localisation loc ON u.id_localisation = loc.id_localisation
INNER JOIN 
    ligne l ON a.id_ligne = l.id_ligne
INNER JOIN 
    forfait f ON a.id_forfait = f.id_forfait
INNER JOIN 
    type_forfait tf ON f.id_type_forfait = tf.id_type_forfait
INNER JOIN 
    view_forfait_prix vfp ON f.id_forfait = vfp.id_forfait
WHERE 
    a.id_ligne IS NOT NULL;