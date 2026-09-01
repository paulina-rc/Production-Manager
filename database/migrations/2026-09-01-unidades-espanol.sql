-- Convierte productions.unit de ingles a espanol
-- Mapeo: Units -> Unidades, Kilograms -> Kilogramos, Grams -> Gramos,
--        Liters -> Litros, Milliliters -> Mililitros, Other -> Otro

UPDATE productions SET unit = 'Unidades' WHERE unit = 'Units';
UPDATE productions SET unit = 'Kilogramos' WHERE unit = 'Kilograms';
UPDATE productions SET unit = 'Gramos' WHERE unit = 'Grams';
UPDATE productions SET unit = 'Litros' WHERE unit = 'Liters';
UPDATE productions SET unit = 'Mililitros' WHERE unit = 'Milliliters';
UPDATE productions SET unit = 'Otro' WHERE unit = 'Other';
