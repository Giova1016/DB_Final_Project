-- Create tables
CREATE TABLE Usuario (
    Id INTEGER PRIMARY KEY AUTOINCREMENT,
    Nombre TEXT,
    FechaNac DATE,
    Direccion TEXT,
    Password TEXT
);

CREATE TABLE Cliente (
    Id INTEGER PRIMARY KEY,
    FOREIGN KEY (Id) REFERENCES Usuario(Id)
);

CREATE TABLE Administrador (
    Id INTEGER PRIMARY KEY,
    FOREIGN KEY (Id) REFERENCES Usuario(Id)
);

CREATE TABLE "Dueño de Negocios" (
    Id INTEGER PRIMARY KEY,
    FOREIGN KEY (Id) REFERENCES Usuario(Id)
);

CREATE TABLE "Gerente de ventas" (
    Id INTEGER PRIMARY KEY,
    FOREIGN KEY (Id) REFERENCES Usuario(Id)
);

CREATE TABLE Negocio (
    Id INTEGER PRIMARY KEY AUTOINCREMENT,
    DueñoId INTEGER,
    FOREIGN KEY (DueñoId) REFERENCES "Dueño de Negocios"(Id)
);

CREATE TABLE Bebidas (
    Id INTEGER PRIMARY KEY AUTOINCREMENT,
    NegocioId INTEGER,
    FOREIGN KEY (NegocioId) REFERENCES Negocio(Id)
);

CREATE TABLE Sabores (
    Id INTEGER PRIMARY KEY AUTOINCREMENT,
    BebidaId INTEGER,
    Nombre TEXT,
    FOREIGN KEY (BebidaId) REFERENCES Bebidas(Id)
);

CREATE TABLE Frutas (
    Id INTEGER PRIMARY KEY AUTOINCREMENT,
    BebidaId INTEGER,
    Nombre TEXT,
    FOREIGN KEY (BebidaId) REFERENCES Bebidas(Id)
);

-- Create relationships
CREATE TABLE Visita (
    ClienteId INTEGER,
    NegocioId INTEGER,
    PRIMARY KEY (ClienteId, NegocioId),
    FOREIGN KEY (ClienteId) REFERENCES Cliente(Id),
    FOREIGN KEY (NegocioId) REFERENCES Negocio(Id)
);

CREATE TABLE Tiene (
    DueñoId INTEGER,
    NegocioId INTEGER,
    PRIMARY KEY (DueñoId, NegocioId),
    FOREIGN KEY (DueñoId) REFERENCES "Dueño de Negocios"(Id),
    FOREIGN KEY (NegocioId) REFERENCES Negocio(Id)
);

CREATE TABLE Promociona (
    GerenteId INTEGER,
    NegocioId INTEGER,
    PRIMARY KEY (GerenteId, NegocioId),
    FOREIGN KEY (GerenteId) REFERENCES "Gerente de ventas"(Id),
    FOREIGN KEY (NegocioId) REFERENCES Negocio(Id)
);

CREATE TABLE Accesar (
    UsuarioId INTEGER,
    TipoUsuario TEXT,
    PRIMARY KEY (UsuarioId, TipoUsuario),
    FOREIGN KEY (UsuarioId) REFERENCES Usuario(Id)
);