-- Drop des vues
DROP VIEW IF EXISTS forfaitPrix;
DROP VIEW IF EXISTS elementPrix;

-- Drop des tables dans l'ordre
DROP TABLE IF EXISTS forfait_element;
DROP TABLE IF EXISTS element;
DROP TABLE IF EXISTS forfait;

CREATE TABLE element (
    id_element SERIAL PRIMARY KEY,
    libelle VARCHAR(50),
    unite VARCHAR(10),
    prix_unitaire_element DOUBLE PRECISION
);
CREATE TABLE forfait (
    id_forfait SERIAL PRIMARY KEY,
    nom_forfait VARCHAR(20)
);
CREATE TABLE forfait_element (
    id_element INT,
    id_forfait INT,
    quantite INT DEFAULT 0, -- Quantité spécifique pour cet élément dans ce forfait
    PRIMARY KEY (id_element, id_forfait),
    FOREIGN KEY (id_element) REFERENCES element(id_element),
    FOREIGN KEY (id_forfait) REFERENCES forfait(id_forfait)
);

INSERT INTO forfait (nom_forfait)
VALUES
('Forfait 0'),
('Forfait 1'),
('Forfait 2'),
('Forfait 2Bis'),
('Forfait 3'),
('Forfait 4'),
('Forfait 5');

INSERT INTO element (libelle, unite, prix_unitaire_element)
VALUES
('Appel Flotte initial', 'Heures', 2160),
('Appel Flotte supplémentaire', 'Heures', 3600),
('Appel Tout TELMA', 'Heures', 4000),
('Appel Tout MADA', 'Heures', 9000),
('Appel vers Etranger', 'Heures', 10000),
('DATA', '15 Go', 62500),
('SMS', '100 SMS', 7500);


-- FORFAIT 0
INSERT INTO forfait_element (id_element, id_forfait, quantite)
VALUES
(1, 1, 5),  -- Appel Flotte initial -> Forfait 0 avec 5 unités
(2, 1, 0),  -- Appel Flotte supplémentaire -> Forfait 0 avec 0 unité
(3, 1, 0),  -- Appel Tout TELMA -> Forfait 0 avec 2 unités
(4, 1, 0),  -- Appel Tout MADA -> Forfait 0 avec 1 unité
(5, 1, 0),  -- Appel vers l'Etranger -> Forfait 0 avec 0 unité
(6, 1, 0),  -- DATA -> Forfait 0 avec 1 unité
(7, 1, 1);  -- SMS -> Forfait 0 avec 1 unité

-- FORFAIT 1
INSERT INTO forfait_element (id_element, id_forfait, quantite)
VALUES
(1, 2, 5),  -- Appel Flotte initial -> Forfait 1 avec 5 unités
(2, 2, 0),  -- Appel Flotte supplémentaire -> Forfait 1 avec 0 unité
(3, 2, 2),  -- Appel Tout TELMA -> Forfait 1 avec 2 unités
(4, 2, 1),  -- Appel Tout MADA -> Forfait 1 avec 1 unité
(5, 2, 0),  -- Appel vers l'Etranger -> Forfait 1 avec 0 unité
(6, 2, 0),  -- DATA -> Forfait 1 avec 0 unité
(7, 2, 1);  -- SMS -> Forfait 1 avec 1 unité 

-- FORFAIT 2
INSERT INTO forfait_element (id_element, id_forfait, quantite)
VALUES
(1, 3, 5),  -- Appel Flotte initial -> Forfait 2 avec 5 unités
(2, 3, 0),  -- Appel Flotte supplémentaire -> Forfait 2 avec 0 unité
(3, 3, 5),  -- Appel Tout TELMA -> Forfait 2 avec 5 unités
(4, 3, 2),  -- Appel Tout MADA -> Forfait 2 avec 2 unités
(5, 3, 0),  -- Appel vers l'Etranger -> Forfait 2 avec 0 unité
(6, 3, 0),  -- DATA -> Forfait 2 avec 0 unité
(7, 3, 1);  -- SMS -> Forfait 2 avec 1 unité

-- FORFAIT 2BIS
INSERT INTO forfait_element (id_element, id_forfait, quantite)
VALUES
(1, 4, 5),  -- Appel Flotte initial -> Forfait 2BIS avec 5 unités
(2, 4, 10), -- Appel Flotte supplémentaire -> Forfait 2BIS avec 10 unités
(3, 4, 5),  -- Appel Tout TELMA -> Forfait 2BIS avec 5 unités
(4, 4, 3),  -- Appel Tout MADA -> Forfait 2BIS avec 3 unités
(5, 4, 0),  -- Appel vers l'Etranger -> Forfait 2BIS avec 0 unité
(6, 4, 1),  -- DATA -> Forfait 2BIS avec 1 unité
(7, 4, 1);  -- SMS -> Forfait 2BIS avec 1 unité

-- FORFAIT 3
INSERT INTO forfait_element (id_element, id_forfait, quantite)
VALUES
(1, 5, 5),  -- Appel Flotte initial -> Forfait 3 avec 5 unités
(2, 5, 10), -- Appel Flotte supplémentaire -> Forfait 3 avec 10 unités
(3, 5, 5),  -- Appel Tout TELMA -> Forfait 3 avec 5 unités
(4, 5, 3),  -- Appel Tout MADA -> Forfait 3 avec 3 unités
(5, 5, 1),  -- Appel vers l'Etranger -> Forfait 3 avec 1 unité
(6, 5, 1),  -- DATA -> Forfait 3 avec 1 unité
(7, 5, 1);  -- SMS -> Forfait 3 avec 1 unité

-- FORFAIT 4
INSERT INTO forfait_element (id_element, id_forfait, quantite)
VALUES
(1, 6, 5),  -- Appel Flotte initial -> Forfait 4 avec 5 unités
(2, 6, 20), -- Appel Flotte supplémentaire -> Forfait 4 avec 20 unités
(3, 6, 3),  -- Appel Tout TELMA -> Forfait 4 avec 3 unités
(4, 6, 3),  -- Appel Tout MADA -> Forfait 4 avec 3 unités
(5, 6, 2),  -- Appel vers l'Etranger -> Forfait 4 avec 2 unités
(6, 6, 1),  -- DATA -> Forfait 4 avec 1 unité
(7, 6, 2);  -- SMS -> Forfait 4 avec 2 unités  

-- FORFAIT 5
INSERT INTO forfait_element (id_element, id_forfait, quantite)
VALUES
(1, 7, 5),  -- Appel Flotte initial -> Forfait 5 avec 5 unités
(2, 7, 10), -- Appel Flotte supplémentaire -> Forfait 5 avec 10 unités
(3, 7, 4),  -- Appel Tout TELMA -> Forfait 5 avec 4 unités
(4, 7, 2),  -- Appel Tout MADA -> Forfait 5 avec 2 unités
(5, 7, 2),  -- Appel vers l'Etranger -> Forfait 5 avec 2 unités
(6, 7, 1),  -- DATA -> Forfait 5 avec 1 unité
(7, 7, 3);  -- SMS -> Forfait 5 avec 3 unités

CREATE VIEW elementPrix AS
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

CREATE VIEW forfaitPrix AS
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


SELECT * FROM elementPrix;
SELECT * FROM forfaitPrix;
