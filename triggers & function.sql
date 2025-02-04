-- Fonction qui calcule le dernier jour du mois
CREATE OR REPLACE FUNCTION get_last_day_of_month(date_value DATE) 
RETURNS DATE AS $$
BEGIN
    RETURN (date_trunc('month', date_value) + interval '1 month' - interval '1 day')::date;
END;
$$ LANGUAGE plpgsql;

-- Fonction trigger pour remplir fin_operation
CREATE OR REPLACE FUNCTION set_fin_operation()
RETURNS TRIGGER AS $$
BEGIN
    NEW.fin_operation := get_last_day_of_month(NEW.debut_operation);
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Création du trigger
CREATE TRIGGER tr_set_fin_operation
    BEFORE INSERT OR UPDATE OF debut_operation
    ON operation
    FOR EACH ROW
    EXECUTE FUNCTION set_fin_operation();