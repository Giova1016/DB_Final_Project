-- Create tables
CREATE TABLE Usuario (
    Id INTEGER PRIMARY KEY AUTO_INCREMENT,
    Nombre TEXT,
    FechaNac DATE,
    Direccion TEXT,
    Email VARCHAR(255) UNIQUE,
    Password TEXT
);

CREATE TABLE Cliente (
    ClienteId INTEGER PRIMARY KEY,
    UsuarioId INTEGER,
    FOREIGN KEY (UsuarioId) REFERENCES Usuario(Id)
    ON UPDATE CASCADE
    ON DELETE SET NULL
);

CREATE TABLE Administrador (
    Id INTEGER PRIMARY KEY,
    UsuarioId INTEGER,
    FOREIGN KEY (UsuarioId) REFERENCES Usuario(Id)
    ON UPDATE CASCADE
    ON DELETE SET NULL
);

CREATE TABLE `Dueño de Negocios` (
    Id INTEGER PRIMARY KEY,
    UsuarioId INTEGER,
    FOREIGN KEY (UsuarioId) REFERENCES Usuario(Id)
    ON UPDATE CASCADE
    ON DELETE SET NULL
);

CREATE TABLE `Gerente de Ventas` (
    Id INTEGER PRIMARY KEY,
    UsuarioId INTEGER,
    FOREIGN KEY (UsuarioId) REFERENCES Usuario(Id)
    ON UPDATE CASCADE
    ON DELETE SET NULL
);

CREATE TABLE Negocio (
    Id INTEGER PRIMARY KEY AUTO_INCREMENT,
    DueñoId INTEGER,
    FOREIGN KEY (DueñoId) REFERENCES `Dueño de Negocios`(Id)
    ON UPDATE CASCADE
    ON DELETE SET NULL
);

CREATE TABLE Inventario(
    Id INTEGER PRIMARY KEY AUTO_INCREMENT,
    NegocioId INTEGER NOT NULL,
    Descripcion TEXT,
    Cantidad INTEGER,
    FOREIGN KEY (NegocioId) REFERENCES Negocio(Id)
    ON UPDATE CASCADE
    ON DELETE SET NULL
);

CREATE TABLE Bebidas (
    Id INTEGER PRIMARY KEY AUTO_INCREMENT,
    NegocioId INTEGER,
    FOREIGN KEY (NegocioId) REFERENCES Negocio(Id) 
    ON UPDATE CASCADE
    ON DELETE SET NULL
);

CREATE TABLE Sabores (
    Id INTEGER PRIMARY KEY AUTO_INCREMENT,
    BebidaId INTEGER,
    Nombre TEXT,
    FOREIGN KEY (BebidaId) REFERENCES Bebidas(Id)
    ON UPDATE CASCADE
    ON DELETE SET NULL
);

CREATE TABLE Frutas (
    Id INTEGER PRIMARY KEY AUTO_INCREMENT,
    BebidaId INTEGER,
    Nombre TEXT,
    FOREIGN KEY (BebidaId) REFERENCES Bebidas(Id)
    ON UPDATE CASCADE
    ON DELETE SET NULL
);

-- Create relationships
CREATE TABLE Visita (
    ClienteId INTEGER,
    NegocioId INTEGER,
    PRIMARY KEY (ClienteId, NegocioId),
    FOREIGN KEY (ClienteId) REFERENCES Cliente(ClienteId)
    ON UPDATE CASCADE
    ON DELETE SET NULL, 
    FOREIGN KEY (NegocioId) REFERENCES Negocio(Id)
    ON UPDATE CASCADE
    ON DELETE SET NULL
);

CREATE TABLE Tiene (
    DueñoId INTEGER,
    NegocioId INTEGER,
    PRIMARY KEY (DueñoId, NegocioId),
    FOREIGN KEY (DueñoId) REFERENCES `Dueño de Negocios`(Id)
    ON UPDATE CASCADE
    ON DELETE SET NULL,
    FOREIGN KEY (NegocioId) REFERENCES Negocio(Id)
    ON UPDATE CASCADE
    ON DELETE SET NULL
);

CREATE TABLE Posee(
    NegocioId INTEGER,
    InventarioId INTEGER,
    Primary KEY (NegocioId, InventarioId),
    FOREIGN KEY (NegocioId) REFERENCES Negocio(Id) 
    ON UPDATE CASCADE 
    ON DELETE SET NULL,
    FOREIGN KEY (InventarioId) REFERENCES Inventario(Id) 
    ON UPDATE CASCADE 
    ON DELETE SET NULL
);

CREATE TABLE Promociona (
    GerenteId INTEGER,
    NegocioId INTEGER,
    PRIMARY KEY (GerenteId, NegocioId),
    FOREIGN KEY (GerenteId) REFERENCES `Gerente de Ventas`(Id)
    ON UPDATE CASCADE
    ON DELETE SET NULL,
    FOREIGN KEY (NegocioId) REFERENCES Negocio(Id)
    ON UPDATE CASCADE
    ON DELETE SET NULL
);

CREATE TABLE Accesar (
    UsuarioId INTEGER,
    TipoUsuario TEXT,
    PRIMARY KEY (UsuarioId, TipoUsuario),
    FOREIGN KEY (UsuarioId) REFERENCES Usuario(Id)
    ON UPDATE CASCADE
    ON DELETE SET NULL
);
