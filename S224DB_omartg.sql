-- --------------------------------------------------------

--
-- Table structure for table `Usuario`
--

CREATE TABLE Usuario (
    Id INT(11) PRIMARY KEY AUTO_INCREMENT,
    NombreC VARCHAR(56),
    FechaNac DATE,
    Direccion TEXT,
    Email VARCHAR(255) UNIQUE,
    Password VARCHAR(255)
);

-- --------------------------------------------------------

--
-- Table structure for table `Administrador`
--

CREATE TABLE Administrador (
    AdminId INT(11) PRIMARY KEY,
    UsuarioId INT(11),
    FOREIGN KEY (UsuarioId) REFERENCES Usuario(Id)
    ON UPDATE CASCADE
    ON DELETE SET NULL
);

-- --------------------------------------------------------

--
-- Table structure for table `Cliente`
--

CREATE TABLE Cliente (
    ClienteId INT(11) PRIMARY KEY,
    UsuarioId INT(11),
    Visita DATE,
    NegocioId INT(11),
    FOREIGN KEY (UsuarioId) REFERENCES Usuario(Id)
    ON UPDATE CASCADE
    ON DELETE SET NULL,
    FOREIGN KEY (NegocioId) REFERENCES Negocio(Id)
    ON UPDATE CASCADE
    ON DELETE SET NULL
);

-- --------------------------------------------------------

--
-- Table structure for table `Dueño de Negocios`
--

CREATE TABLE `Dueño de Negocios` (
    DueñoId INT(11) PRIMARY KEY AUTO_INCREMENT,
    UsuarioId INT(11),
    FOREIGN KEY (UsuarioId) REFERENCES Usuario(Id)
    ON UPDATE CASCADE
    ON DELETE SET NULL
);

-- --------------------------------------------------------

--
-- Table structure for table `Gerente de Ventas`
--

CREATE TABLE `Gerente de Ventas` (
    GerenteID INT(11) PRIMARY KEY,
    UsuarioId INT(11),
    FOREIGN KEY (UsuarioId) REFERENCES Usuario(Id)
    ON UPDATE CASCADE
    ON DELETE SET NULL
);

-- --------------------------------------------------------

--
-- Table structure for table `Negocio`
--

CREATE TABLE Negocio (
    NegocioId INT(11) PRIMARY KEY AUTO_INCREMENT,
    DueñoId INT(11),
    NombreNegocio TEXT NOT NULL,
    MenuBebidas TEXT DEFAULT NULL,
    FOREIGN KEY (DueñoId) REFERENCES `Dueño de Negocios`(DueñoId)
    ON UPDATE CASCADE
    ON DELETE SET NULL
);

-- --------------------------------------------------------

--
-- Table structure for table `Inventario`
--

CREATE TABLE Inventario (
    InventoryId INT(11) PRIMARY KEY AUTO_INCREMENT,
    NegocioId INT(11),
    Descripcion TEXT DEFAULT NULL,
    Cantidad INT(11) DEFAULT NULL,
    FOREIGN KEY (NegocioId) REFERENCES Negocio(NegocioId)
    ON UPDATE CASCADE
    ON DELETE SET NULL
);

-- --------------------------------------------------------

--
-- Table structure for table `Bebidas`
--

CREATE TABLE Bebidas (
    BebidasId INT(11) PRIMARY KEY AUTO_INCREMENT,
    NegocioId INT(11),
    NombreBebida TEXT NOT NULL,
    Descripcion TEXT NOT NULL,
    FOREIGN KEY (NegocioId) REFERENCES Negocio(NegocioId)
    ON UPDATE CASCADE
    ON DELETE SET NULL
);

-- --------------------------------------------------------

--
-- Table structure for table `Sabores`
--

CREATE TABLE Sabores (
    SaborId INT(11) PRIMARY KEY AUTO_INCREMENT,
    BebidaId INT(11),
    TiposSabores VARCHAR(56) DEFAULT NULL,
    FOREIGN KEY (BebidaId) REFERENCES Bebidas(BebidasId)
    ON UPDATE CASCADE
    ON DELETE SET NULL
);

-- --------------------------------------------------------

--
-- Table structure for table `Frutas`
--

CREATE TABLE Frutas (
    FrutaId INT(11) PRIMARY KEY AUTO_INCREMENT,
    BebidaId INT(11),
    NombreFruta VARCHAR(56) DEFAULT NULL,
    FOREIGN KEY (BebidaId) REFERENCES Bebidas(BebidasId)
    ON UPDATE CASCADE
    ON DELETE SET NULL
);

-- --------------------------------------------------------

--
-- Table structure for table `Sirve`
--

CREATE TABLE Sirve (
    SirveId INT(11) PRIMARY KEY AUTO_INCREMENT,
    BebidaId INT(11),
    NegocioId INT(11),
    Descripcion TEXT NOT NULL,
    FOREIGN KEY (BebidaId) REFERENCES Bebidas(BebidasId)
    ON UPDATE CASCADE
    ON DELETE SET NULL,
    FOREIGN KEY (NegocioId) REFERENCES Negocio(NegocioId)
    ON UPDATE CASCADE
    ON DELETE SET NULL
);

-- --------------------------------------------------------

--
-- Table structure for table `Promociones`
--

CREATE TABLE Promociones (
    PromocionesId INT(11) PRIMARY KEY,
    NegocioId INT(11),
    BebidasId INT(11),
    Descuento TEXT NOT NULL,
    FOREIGN KEY (NegocioId) REFERENCES Negocio(NegocioId)
    ON UPDATE CASCADE
    ON DELETE SET NULL,
    FOREIGN KEY (BebidasId) REFERENCES Bebidas(BebidasId)
    ON UPDATE CASCADE
    ON DELETE SET NULL
);

-- --------------------------------------------------------