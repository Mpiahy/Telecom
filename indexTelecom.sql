CREATE INDEX idx_type_equipement ON equipement (id_type_equipement);

CREATE INDEX idx_equipement_id_modele ON equipement (id_modele);
CREATE INDEX idx_equipement_id_statut ON equipement (id_statut_equipement);
CREATE INDEX idx_equipement_modele_statut ON equipement (id_modele, id_statut_equipement);

-- Index pour les id_marque qui commencent par 1 ou 2 (Phone)
CREATE INDEX idx_marque_phone ON marque (id_marque)
WHERE id_marque >= 1000 AND id_marque < 3000;

-- Index pour les id_marque qui commencent par 3 (Box)
CREATE INDEX idx_marque_box ON marque (id_marque)
WHERE id_marque >= 3000 AND id_marque < 4000;

-- Index pour les id_modele qui commencent par 1 ou 2 (Phone)
CREATE INDEX idx_modele_phone ON modele (id_modele)
WHERE id_modele >= 1000000 AND id_modele < 3000000;

-- Index pour les id_modele qui commencent par 3 (Box)
CREATE INDEX idx_modele_box ON modele (id_modele)
WHERE id_modele >= 3000000 AND id_modele < 4000000;

-- Index pour optimiser les sous-requêtes sur id_marque
CREATE INDEX idx_marque_id ON marque (id_marque);

-- Index pour optimiser les sous-requêtes sur id_modele
CREATE INDEX idx_modele_id ON modele (id_modele);

-- Index pour optimiser les sous-requêtes sur affectation
CREATE INDEX idx_utilisateur_id_localisation ON utilisateur (id_localisation);

-- Index pour améliorer le filtre sur les équipements dans la table affectation
CREATE INDEX idx_affectation_id_equipement ON affectation (id_equipement);

-- Index pour améliorer les jointures sur l'utilisateur dans la table affectation
CREATE INDEX idx_affectation_id_utilisateur ON affectation (id_utilisateur);

-- Index pour la jointure entre equipement et type_equipement
CREATE INDEX idx_equipement_id_type_equipement ON equipement (id_type_equipement);

-- Index pour la jointure entre modele et marque
CREATE INDEX idx_modele_id_marque ON modele (id_marque);

-- Index pour améliorer le filtre sur les lignes dans la table affectation
CREATE INDEX idx_affectation_id_ligne ON affectation (id_ligne);

-- Index pour la jointure entre ligne et forfait
CREATE INDEX idx_ligne_id_forfait ON ligne (id_forfait);

-- Index pour la jointure entre ligne et type_ligne
CREATE INDEX idx_ligne_id_type_ligne ON ligne (id_type_ligne);

CREATE INDEX idx_affectation_ligne_debut ON affectation (id_ligne, debut_affectation DESC);
CREATE INDEX idx_affectation_id ON affectation (id_affectation);
