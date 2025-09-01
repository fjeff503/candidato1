
/*MANEJO DE LA BASE DE DATOS*/
CREATE DATABASE candidato1;
GO
USE candidato1;
GO
/*1- CREAR LAS TABLAS*/
-- Tabla docentes
CREATE TABLE docentes (
    id INT IDENTITY(1,1) PRIMARY KEY,
    nombre_completo VARCHAR(150) NOT NULL,
    correo VARCHAR(120) UNIQUE NOT NULL,
    fecha_ingreso DATE NOT NULL
);
GO
-- Tabla asignaturas
CREATE TABLE asignaturas (
    id INT IDENTITY(1,1) PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    codigo VARCHAR(50) UNIQUE NOT NULL
);
GO
-- Tabla horarios
CREATE TABLE horarios (
    id INT IDENTITY(1,1) PRIMARY KEY,
    id_docente INT NOT NULL,
    id_asignatura INT NOT NULL,
    dia_semana VARCHAR(20) NOT NULL, -- Ej: 'Lunes'
    hora_inicio TIME NOT NULL,
    hora_fin TIME NOT NULL,
    CONSTRAINT fk_horarios_docente FOREIGN KEY (id_docente) REFERENCES docentes(id),
    CONSTRAINT fk_horarios_asignatura FOREIGN KEY (id_asignatura) REFERENCES asignaturas(id)
);

/*2- PROCEDIMIENTO ALMACENADO*/
CREATE PROCEDURE sp_asignar_horario
    @correo_docente VARCHAR(120),
    @codigo_asignatura VARCHAR(50),
    @dia_semana VARCHAR(20),
    @hora_inicio TIME,
    @hora_fin TIME
AS
BEGIN
    SET NOCOUNT ON;

    DECLARE @id_docente INT, @id_asignatura INT;

    -- Validar docente
    SELECT @id_docente = id
    FROM docentes
    WHERE correo = @correo_docente;

    IF @id_docente IS NULL
    BEGIN
        RAISERROR('El docente con el correo especificado no existe.', 16, 1);
        RETURN;
    END;

    -- Validar asignatura
    SELECT @id_asignatura = id
    FROM asignaturas
    WHERE codigo = @codigo_asignatura;

    IF @id_asignatura IS NULL
    BEGIN
        RAISERROR('La asignatura con el código especificado no existe.', 16, 1);
        RETURN;
    END;

    -- Validar duplicidad de horario
    IF EXISTS (
        SELECT 1
        FROM horarios
        WHERE id_docente = @id_docente
          AND dia_semana = @dia_semana
          AND (
                (@hora_inicio BETWEEN hora_inicio AND hora_fin) OR
                (@hora_fin BETWEEN hora_inicio AND hora_fin) OR
                (hora_inicio BETWEEN @hora_inicio AND @hora_fin) OR
                (hora_fin BETWEEN @hora_inicio AND @hora_fin)
              )
    )
    BEGIN
        RAISERROR('Ya existe un horario para este docente en la misma franja horaria.', 16, 1);
        RETURN;
    END;

    -- Insertar nuevo horario
    INSERT INTO horarios (id_docente, id_asignatura, dia_semana, hora_inicio, hora_fin)
    VALUES (@id_docente, @id_asignatura, @dia_semana, @hora_inicio, @hora_fin);

    PRINT 'Horario asignado correctamente.';
END;
GO

/*3- INSERTAR DATOS DE PRUEBA*/
--DOCENTES
INSERT INTO docentes (nombre_completo, correo, fecha_ingreso)
VALUES 
('Juan Pérez', 'juan.perez@ejemplo.com', '2025-09-01'),
('Maria López', 'maria.lopez@ejemplo.com', '2025-08-15'),
('Carlos Gómez', 'carlos.gomez@ejemplo.com', '2025-07-20'),
('Ana Ramírez', 'ana.ramirez@ejemplo.com', '2025-06-10'),
('Luis Martínez', 'luis.martinez@ejemplo.com', '2025-05-05');
GO

--ASIGNATURA
INSERT INTO asignaturas (nombre, codigo)
VALUES
('Matemáticas', 'MAT101'),
('Física', 'FIS201'),
('Química', 'QUI301'),
('Biología', 'BIO401'),
('Historia', 'HIS501');
GO

/*DATOS DE PRUEBA DEL SP*/
-- 1
EXEC sp_asignar_horario 
    @correo_docente = 'juan.perez@ejemplo.com',
    @codigo_asignatura = 'MAT101',
    @dia_semana = 'Lunes',
    @hora_inicio = '08:00',
    @hora_fin = '10:00';

-- 2
EXEC sp_asignar_horario 
    @correo_docente = 'maria.lopez@ejemplo.com',
    @codigo_asignatura = 'FIS201',
    @dia_semana = 'Martes',
    @hora_inicio = '10:00',
    @hora_fin = '12:00';

-- 3
EXEC sp_asignar_horario 
    @correo_docente = 'carlos.gomez@ejemplo.com',
    @codigo_asignatura = 'QUI301',
    @dia_semana = 'Miércoles',
    @hora_inicio = '13:00',
    @hora_fin = '15:00';

-- 4
EXEC sp_asignar_horario 
    @correo_docente = 'ana.ramirez@ejemplo.com',
    @codigo_asignatura = 'BIO401',
    @dia_semana = 'Jueves',
    @hora_inicio = '09:00',
    @hora_fin = '11:00';

-- 5
EXEC sp_asignar_horario 
    @correo_docente = 'luis.martinez@ejemplo.com',
    @codigo_asignatura = 'HIS501',
    @dia_semana = 'Viernes',
    @hora_inicio = '14:00',
    @hora_fin = '16:00';

-- 6
EXEC sp_asignar_horario 
    @correo_docente = 'juan.perez@ejemplo.com',
    @codigo_asignatura = 'MAT101',
    @dia_semana = 'Martes',
    @hora_inicio = '08:00',
    @hora_fin = '10:00';

-- 7
EXEC sp_asignar_horario 
    @correo_docente = 'maria.lopez@ejemplo.com',
    @codigo_asignatura = 'FIS201',
    @dia_semana = 'Miércoles',
    @hora_inicio = '10:00',
    @hora_fin = '12:00';

-- 8
EXEC sp_asignar_horario 
    @correo_docente = 'carlos.gomez@ejemplo.com',
    @codigo_asignatura = 'QUI301',
    @dia_semana = 'Jueves',
    @hora_inicio = '13:00',
    @hora_fin = '15:00';

-- 9
EXEC sp_asignar_horario 
    @correo_docente = 'ana.ramirez@ejemplo.com',
    @codigo_asignatura = 'BIO401',
    @dia_semana = 'Viernes',
    @hora_inicio = '09:00',
    @hora_fin = '11:00';

-- 10
EXEC sp_asignar_horario 
    @correo_docente = 'luis.martinez@ejemplo.com',
    @codigo_asignatura = 'HIS501',
    @dia_semana = 'Lunes',
    @hora_inicio = '14:00',
    @hora_fin = '16:00';

-- 11
EXEC sp_asignar_horario 
    @correo_docente = 'juan.perez@ejemplo.com',
    @codigo_asignatura = 'MAT101',
    @dia_semana = 'Miércoles',
    @hora_inicio = '08:00',
    @hora_fin = '10:00';

-- 12
EXEC sp_asignar_horario 
    @correo_docente = 'maria.lopez@ejemplo.com',
    @codigo_asignatura = 'FIS201',
    @dia_semana = 'Jueves',
    @hora_inicio = '10:00',
    @hora_fin = '12:00';

-- 13
EXEC sp_asignar_horario 
    @correo_docente = 'carlos.gomez@ejemplo.com',
    @codigo_asignatura = 'QUI301',
    @dia_semana = 'Viernes',
    @hora_inicio = '13:00',
    @hora_fin = '15:00';

-- 14
EXEC sp_asignar_horario 
    @correo_docente = 'ana.ramirez@ejemplo.com',
    @codigo_asignatura = 'BIO401',
    @dia_semana = 'Lunes',
    @hora_inicio = '09:00',
    @hora_fin = '11:00';

-- 15
EXEC sp_asignar_horario 
    @correo_docente = 'luis.martinez@ejemplo.com',
    @codigo_asignatura = 'HIS501',
    @dia_semana = 'Martes',
    @hora_inicio = '14:00',
    @hora_fin = '16:00';

/*4- CREAR VISTA*/
CREATE VIEW vw_horarios_docentes AS
SELECT 
    h.id AS id_horario,
    d.nombre_completo AS nombre_docente,
    d.correo AS correo_docente,
    a.nombre AS nombre_asignatura,
    a.codigo AS codigo_asignatura,
    h.dia_semana,
    h.hora_inicio,
    h.hora_fin
FROM horarios h
INNER JOIN docentes d ON h.id_docente = d.id
INNER JOIN asignaturas a ON h.id_asignatura = a.id;

select * from vw_horarios_docentes

/*EXTRAS*/
/*CONSULTAS*/
SELECT * FROM horarios
SELECT * FROM docentes
SELECT * FROM asignaturas

/*CREACION DE USUARIO candidato1*/
-- Crear usuario de login a nivel de servidor
CREATE LOGIN candidato1 WITH PASSWORD = 'candidato1';
GO
-- Asignar el rol de sysadmin (equivalente a SA)
ALTER SERVER ROLE sysadmin ADD MEMBER candidato1;
GO



