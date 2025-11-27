-- Esquema de Base de Datos: Sistema de Horarios UPV
-- Motor: MySQL / MariaDB

CREATE DATABASE IF NOT EXISTS `horarios_upv`;
USE `horarios_upv`;

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- -----------------------------------------------------
-- Tabla: profesores
-- -----------------------------------------------------
DROP TABLE IF EXISTS `profesores`;
CREATE TABLE `profesores` (
  `id_profesor` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(100) NOT NULL,
  `abreviatura` VARCHAR(20) NOT NULL COMMENT 'Ej. MTI Pérez',
  `max_horas_semana` INT DEFAULT 40,
  `max_horas_dia` INT DEFAULT 8,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_profesor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------------------------------
-- Tabla: grupos
-- -----------------------------------------------------
DROP TABLE IF EXISTS `grupos`;
CREATE TABLE `grupos` (
  `id_grupo` INT NOT NULL AUTO_INCREMENT,
  `nombre_grupo` VARCHAR(50) NOT NULL COMMENT 'Ej. ITI 1-1',
  `semestre` INT NOT NULL,
  `turno` ENUM('Matutino', 'Vespertino', 'Mixto') NOT NULL DEFAULT 'Matutino',
  `num_alumnos` INT DEFAULT 30,
  PRIMARY KEY (`id_grupo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------------------------------
-- Tabla: aulas
-- -----------------------------------------------------
DROP TABLE IF EXISTS `aulas`;
CREATE TABLE `aulas` (
  `id_aula` INT NOT NULL AUTO_INCREMENT,
  `nombre_aula` VARCHAR(50) NOT NULL COMMENT 'Ej. A-101, Lab Cisco',
  `capacidad` INT NOT NULL DEFAULT 30,
  `tipo_aula` ENUM('Normal', 'Laboratorio', 'Taller') NOT NULL DEFAULT 'Normal',
  PRIMARY KEY (`id_aula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------------------------------
-- Tabla: bloques_horario
-- Representa los "Colores" del grafo
-- -----------------------------------------------------
DROP TABLE IF EXISTS `bloques_horario`;
CREATE TABLE `bloques_horario` (
  `id_bloque` INT NOT NULL AUTO_INCREMENT,
  `dia` ENUM('Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado') NOT NULL,
  `hora_inicio` TIME NOT NULL,
  `hora_fin` TIME NOT NULL,
  `turno` ENUM('Matutino', 'Vespertino') NOT NULL,
  PRIMARY KEY (`id_bloque`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------------------------------
-- Tabla: materias (Cursos a programar)
-- -----------------------------------------------------
DROP TABLE IF EXISTS `materias`;
CREATE TABLE `materias` (
  `id_materia` INT NOT NULL AUTO_INCREMENT,
  `nombre_materia` VARCHAR(100) NOT NULL,
  `id_grupo` INT NOT NULL,
  `id_profesor` INT NOT NULL,
  `horas_semana` INT NOT NULL DEFAULT 4 COMMENT 'Número de bloques necesarios',
  `tipo_aula_requerida` ENUM('Normal', 'Laboratorio', 'Taller') DEFAULT 'Normal',
  PRIMARY KEY (`id_materia`),
  CONSTRAINT `fk_materias_grupo`
    FOREIGN KEY (`id_grupo`)
    REFERENCES `grupos` (`id_grupo`)
    ON DELETE CASCADE,
  CONSTRAINT `fk_materias_profesor`
    FOREIGN KEY (`id_profesor`)
    REFERENCES `profesores` (`id_profesor`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------------------------------
-- Tabla: horarios_generados (Resultado del algoritmo)
-- -----------------------------------------------------
DROP TABLE IF EXISTS `horarios_generados`;
CREATE TABLE `horarios_generados` (
  `id_asignacion` INT NOT NULL AUTO_INCREMENT,
  `id_materia` INT NOT NULL,
  `id_bloque` INT NOT NULL,
  `id_aula` INT DEFAULT NULL,
  `fecha_generacion` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_asignacion`),
  UNIQUE KEY `unique_materia_bloque` (`id_materia`, `id_bloque`),
  CONSTRAINT `fk_horario_materia`
    FOREIGN KEY (`id_materia`)
    REFERENCES `materias` (`id_materia`)
    ON DELETE CASCADE,
  CONSTRAINT `fk_horario_bloque`
    FOREIGN KEY (`id_bloque`)
    REFERENCES `bloques_horario` (`id_bloque`)
    ON DELETE CASCADE,
  CONSTRAINT `fk_horario_aula`
    FOREIGN KEY (`id_aula`)
    REFERENCES `aulas` (`id_aula`)
    ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

SET FOREIGN_KEY_CHECKS = 1;
