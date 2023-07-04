


CREATE TABLE T_ZONA_AREAS(
	
	COD_ZONA CHAR(3) PRIMARY KEY ,
	NOMBRE_T_ZONA_AREAS VARCHAR(50) NULL,
	FECHA DATETIME DEFAULT GETDATE() NULL,
	VERSION NCHAR(2) NULL,
)


CREATE TABLE T_INFRAESTRUCTURA(
	
	COD_INFRAESTRUCTURA CHAR(3) PRIMARY KEY ,
	COD_ZONA CHAR(3), 
	NOMBRE_INFRAESTRUCTURA VARCHAR(50) NOT NULL,
	NDIAS INT NULL,
	FECHA DATETIME NULL,
	VERSION NCHAR(2) NULL,
	USUARIO VARCHAR(15) NULL,
	FOREIGN KEY (COD_ZONA) REFERENCES T_ZONA_AREAS(COD_ZONA)
)

CREATE TABLE T_VERSION(
	COD_VERSION INT IDENTITY(1,1) NOT NULL,
	VERSION CHAR(2) NULL,
	 FECHA_VERSION DATETIME DEFAULT GETDATE(),

)


CREATE TABLE T_ALERTA (
  COD_ALERTA INT IDENTITY(1,1) PRIMARY KEY,
  COD_INFRAESTRUCTURA CHAR(3) ,
  FECHA_CREACION DATETIME DEFAULT GETDATE(),
  FECHA_TOTAL DATETIME,
  FECHA_ACORDAR DATETIME,
  FECHA_EJECUCION DATETIME,
  ESTADO CHAR(2) DEFAULT 'P',
  OBSERVACION VARCHAR(100),
  N_DIAS_POS INT NULL,
  FECHA_POSTERGACION DATETIME,
  POSTERGACION CHAR(2) DEFAULT 'NO',
  CALIFICACION CHAR(1),
 
  COD_PERSONAL CHAR(5),
  FECHA_REALIZADA DATETIME,
  ACCION_CORRECTIVA VARCHAR(80),
  VERIFICACION_REALIZADA VARCHAR(30),
  FOREIGN KEY (COD_INFRAESTRUCTURA) REFERENCES T_INFRAESTRUCTURA(COD_INFRAESTRUCTURA)
);
CREATE TABLE T_USUARIO(
	ID_USUARIO INT IDENTITY(1,1) NOT NULL,
	USUARIO VARCHAR(20),
	CLAVE VARCHAR(20)

)


CREATE TABLE T_SOLUCIONES(
	ID_SOLUCIONES INT IDENTITY(1,1) PRIMARY KEY,
	NOMBRE_INSUMOS VARCHAR(50)
)
CREATE TABLE T_PREPARACIONES(
	ID_PREPARACIONES INT IDENTITY(1,1) PRIMARY KEY,
	ID_SOLUCIONES INT,
	NOMBRE_PREPARACION VARCHAR(80),
	FOREIGN KEY (ID_SOLUCIONES) REFERENCES T_SOLUCIONES(ID_SOLUCIONES)
)
CREATE TABLE T_CANTIDAD(
	ID_CANTIDAD INT IDENTITY(1,1) PRIMARY KEY,
	ID_PREPARACIONES INT,
	CANTIDAD_PORCENTAJE VARCHAR(20),
	FOREIGN KEY (ID_PREPARACIONES) REFERENCES T_PREPARACIONES(ID_PREPARACIONES)
)

CREATE TABLE T_L(
	ID_L INT IDENTITY(1,1) PRIMARY KEY ,
	CANTIDAD_LITROS VARCHAR(20),
)

CREATE TABLE T_ML(
	ID_ML INT IDENTITY(1,1),
	ID_CANTIDAD INT,
	ID_L INT,
	CANTIDAD_MILILITROS VARCHAR(10),
	FOREIGN KEY (ID_CANTIDAD) REFERENCES T_CANTIDAD(ID_CANTIDAD),
	FOREIGN KEY (ID_L) REFERENCES T_L(ID_L)
)

CREATE TABLE T_UNION(
	ID_UNION INT IDENTITY(1,1),
	NOMBRE_INSUMOS VARCHAR(50),
    NOMBRE_PREPARACION VARCHAR(80),
    CANTIDAD_PORCENTAJE VARCHAR(20),
    CANTIDAD_LITROS VARCHAR(20),
	CANTIDAD_MILILITROS VARCHAR(10),
	FECHA DATETIME DEFAULT GETDATE(),
	OBSERVACION VARCHAR(100),
	ESTADO CHAR(2) DEFAULT 'P',
	
)



