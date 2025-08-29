
-- MySQL
CREATE TABLE IF NOT EXISTS clients (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(120) NOT NULL,
  apellido VARCHAR(120) NOT NULL,
  dni VARCHAR(12) NOT NULL,
  domicilio VARCHAR(200) NOT NULL,
  provincia VARCHAR(120) NOT NULL,
  localidad VARCHAR(120) NOT NULL,
  cp VARCHAR(10) NOT NULL,
  telefono VARCHAR(20) NOT NULL,
  email VARCHAR(160) NOT NULL,
  transporte VARCHAR(120) NOT NULL,
  peso VARCHAR(12) NULL,
  bulto VARCHAR(10) NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- SQLite alternativa
-- CREATE TABLE clients (
--   id INTEGER PRIMARY KEY AUTOINCREMENT,
--   nombre TEXT, apellido TEXT, dni TEXT, domicilio TEXT,
--   provincia TEXT, localidad TEXT, cp TEXT, telefono TEXT,
--   email TEXT, transporte TEXT, peso TEXT, bulto TEXT,
--   created_at TEXT
-- );
