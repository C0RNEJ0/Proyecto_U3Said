-- Datos de Prueba para Sistema de Horarios UPV
USE `horarios_upv`;

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- 1. Profesores (10)
TRUNCATE TABLE `profesores`;
INSERT INTO `profesores` (`nombre`, `abreviatura`, `max_horas_semana`) VALUES
('Ing. Juan Carlos Pérez', 'Ing. Pérez', 40),
('Dra. María Elena López', 'Dra. López', 30),
('MTI. Roberto Gómez Bolaños', 'MTI. Gómez', 35),
('Lic. Ana Sofía Martínez', 'Lic. Martínez', 20),
('Dr. Pedro Antonio Ruiz', 'Dr. Ruiz', 40),
('Ing. Laura Cristina Torres', 'Ing. Torres', 25),
('Mtro. Javier Hernández', 'Mtro. Hernández', 40),
('Ing. Diana Patricia Silva', 'Ing. Silva', 30),
('Lic. Fernando Colunga', 'Lic. Colunga', 15),
('Dra. Carmen Salinas', 'Dra. Salinas', 20);

-- 2. Grupos (10)
TRUNCATE TABLE `grupos`;
INSERT INTO `grupos` (`nombre_grupo`, `semestre`, `turno`, `num_alumnos`) VALUES
('ITI-1-1', 1, 'Matutino', 30),
('ITI-1-2', 1, 'Vespertino', 28),
('ITI-4-1', 4, 'Matutino', 25),
('ITI-4-2', 4, 'Matutino', 24),
('ITI-7-1', 7, 'Matutino', 20),
('ITI-7-2', 7, 'Matutino', 18),
('ITI-9-1', 9, 'Matutino', 15),
('PYMES-1-1', 1, 'Matutino', 35),
('MAN-1-1', 1, 'Matutino', 30),
('MEC-1-1', 1, 'Matutino', 32);

-- 3. Aulas (10)
TRUNCATE TABLE `aulas`;
INSERT INTO `aulas` (`nombre_aula`, `capacidad`, `tipo_aula`) VALUES
('A-101', 35, 'Normal'),
('A-102', 35, 'Normal'),
('A-103', 35, 'Normal'),
('A-104', 30, 'Normal'),
('A-201', 30, 'Normal'),
('Lab. Cisco', 25, 'Laboratorio'),
('Lab. Mac', 20, 'Laboratorio'),
('Lab. Redes', 25, 'Laboratorio'),
('Taller de Hardware', 20, 'Taller'),
('Sala Audiovisual', 50, 'Normal');

-- 4. Bloques de Horario (Turno Matutino Completo - 35 bloques)
TRUNCATE TABLE `bloques_horario`;
INSERT INTO `bloques_horario` (`dia`, `hora_inicio`, `hora_fin`, `turno`) VALUES
('Lunes', '07:00', '07:50', 'Matutino'), ('Lunes', '07:50', '08:40', 'Matutino'), ('Lunes', '08:40', '09:30', 'Matutino'), ('Lunes', '09:30', '10:20', 'Matutino'), ('Lunes', '10:20', '11:10', 'Matutino'), ('Lunes', '11:10', '12:00', 'Matutino'), ('Lunes', '12:00', '12:50', 'Matutino'),
('Martes', '07:00', '07:50', 'Matutino'), ('Martes', '07:50', '08:40', 'Matutino'), ('Martes', '08:40', '09:30', 'Matutino'), ('Martes', '09:30', '10:20', 'Matutino'), ('Martes', '10:20', '11:10', 'Matutino'), ('Martes', '11:10', '12:00', 'Matutino'), ('Martes', '12:00', '12:50', 'Matutino'),
('Miercoles', '07:00', '07:50', 'Matutino'), ('Miercoles', '07:50', '08:40', 'Matutino'), ('Miercoles', '08:40', '09:30', 'Matutino'), ('Miercoles', '09:30', '10:20', 'Matutino'), ('Miercoles', '10:20', '11:10', 'Matutino'), ('Miercoles', '11:10', '12:00', 'Matutino'), ('Miercoles', '12:00', '12:50', 'Matutino'),
('Jueves', '07:00', '07:50', 'Matutino'), ('Jueves', '07:50', '08:40', 'Matutino'), ('Jueves', '08:40', '09:30', 'Matutino'), ('Jueves', '09:30', '10:20', 'Matutino'), ('Jueves', '10:20', '11:10', 'Matutino'), ('Jueves', '11:10', '12:00', 'Matutino'), ('Jueves', '12:00', '12:50', 'Matutino'),
('Viernes', '07:00', '07:50', 'Matutino'), ('Viernes', '07:50', '08:40', 'Matutino'), ('Viernes', '08:40', '09:30', 'Matutino'), ('Viernes', '09:30', '10:20', 'Matutino'), ('Viernes', '10:20', '11:10', 'Matutino'), ('Viernes', '11:10', '12:00', 'Matutino'), ('Viernes', '12:00', '12:50', 'Matutino');

-- 5. Materias (20 - Asignando carga a los grupos 1 y 2 principalmente para pruebas)
TRUNCATE TABLE `materias`;
-- Materias para ITI-1-1 (Grupo ID 1)
INSERT INTO `materias` (`nombre_materia`, `id_grupo`, `id_profesor`, `horas_semana`, `tipo_aula_requerida`) VALUES
('Matemáticas I', 1, 1, 5, 'Normal'),       -- Ing. Pérez
('Programación I', 1, 3, 5, 'Laboratorio'), -- MTI. Gómez
('Inglés I', 1, 4, 3, 'Normal'),            -- Lic. Martínez
('Física I', 1, 5, 4, 'Normal'),            -- Dr. Ruiz
('Introducción a TI', 1, 2, 3, 'Normal');   -- Dra. López

-- Materias para ITI-4-1 (Grupo ID 3)
INSERT INTO `materias` (`nombre_materia`, `id_grupo`, `id_profesor`, `horas_semana`, `tipo_aula_requerida`) VALUES
('Base de Datos', 3, 3, 5, 'Laboratorio'),  -- MTI. Gómez (Mismo profe, conflicto potencial)
('Redes I', 3, 6, 4, 'Laboratorio'),        -- Ing. Torres
('Matemáticas IV', 3, 1, 4, 'Normal'),      -- Ing. Pérez (Mismo profe, conflicto potencial)
('Sistemas Operativos', 3, 7, 4, 'Normal'), -- Mtro. Hernández
('Inglés IV', 3, 4, 3, 'Normal');           -- Lic. Martínez

-- Materias para ITI-7-1 (Grupo ID 5)
INSERT INTO `materias` (`nombre_materia`, `id_grupo`, `id_profesor`, `horas_semana`, `tipo_aula_requerida`) VALUES
('Gestión de Proyectos', 5, 2, 4, 'Normal'), -- Dra. López
('Seguridad Informática', 5, 6, 4, 'Laboratorio'), -- Ing. Torres
('Inteligencia Artificial', 5, 5, 5, 'Laboratorio'), -- Dr. Ruiz
('Desarrollo Web', 5, 8, 5, 'Laboratorio'), -- Ing. Silva
('Inglés VII', 5, 9, 3, 'Normal');          -- Lic. Colunga

SET FOREIGN_KEY_CHECKS = 1;
