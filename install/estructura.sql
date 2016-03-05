SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE  TABLE IF NOT EXISTS `departamentos` (
  `id_departamento` INT(11) NOT NULL AUTO_INCREMENT ,
  `departamento` VARCHAR(100) NULL DEFAULT NULL ,
  `descripcion` VARCHAR(1000) NULL DEFAULT NULL ,
  `codigo` VARCHAR(10) NULL DEFAULT NULL ,
  PRIMARY KEY (`id_departamento`) ,
  UNIQUE INDEX `departamento_UNIQUE` (`departamento` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


CREATE  TABLE IF NOT EXISTS `aulas` (
  `id_aula` INT(11) NOT NULL AUTO_INCREMENT ,
  `aula` VARCHAR(100) NULL DEFAULT NULL ,
  `descripcion` VARCHAR(1000) NULL DEFAULT NULL ,
  `departamentos_id_departamento` INT(11) NOT NULL ,
  PRIMARY KEY (`id_aula`) ,
  UNIQUE INDEX `aula_UNIQUE` (`aula` ASC) ,
  INDEX `fk_aulas_departamentos_idx` (`departamentos_id_departamento` ASC) ,
  CONSTRAINT `fk_aulas_departamentos`
    FOREIGN KEY (`departamentos_id_departamento` )
    REFERENCES `departamentos` (`id_departamento` )
    ON DELETE RESTRICT
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE  TABLE IF NOT EXISTS `proveedores` (
  `id_proveedor` INT(11) NOT NULL AUTO_INCREMENT ,
  `proveedor` VARCHAR(100) NULL DEFAULT NULL ,
  `codigo` VARCHAR(10) NULL DEFAULT NULL ,
  `descripcion` VARCHAR(200) NULL DEFAULT NULL ,
  `material` VARCHAR(100) NULL DEFAULT NULL ,
  `telefono` VARCHAR(50) NULL DEFAULT NULL ,
  `movil` VARCHAR(10) NULL DEFAULT NULL ,
  `fax` VARCHAR(50) NULL DEFAULT NULL ,
  `nif` VARCHAR(10) NULL DEFAULT NULL ,
  `email` VARCHAR(100) NULL DEFAULT NULL ,
  `direccion` VARCHAR(200) NULL DEFAULT NULL ,
  `localidad` VARCHAR(100) NULL DEFAULT NULL ,
  `provincia` VARCHAR(100) NULL DEFAULT NULL ,
  `cp` VARCHAR(10) NULL DEFAULT NULL ,
  PRIMARY KEY (`id_proveedor`) ,
  UNIQUE INDEX `proveedor_UNIQUE` (`proveedor` ASC) ,
  UNIQUE INDEX `codigo_UNIQUE` (`codigo` ASC) ,
  UNIQUE INDEX `nif_UNIQUE` (`nif` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


CREATE  TABLE IF NOT EXISTS `tipos_materiales` (
  `id_tipo` INT(11) NOT NULL AUTO_INCREMENT ,
  `tipo` VARCHAR(100) NULL DEFAULT NULL ,
  `mantenimiento` TINYINT(1) NULL DEFAULT NULL ,
  PRIMARY KEY (`id_tipo`) ,
  UNIQUE INDEX `tipo_UNIQUE` (`tipo` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


CREATE  TABLE IF NOT EXISTS `inventario` (
  `id_inventario` INT(11) NOT NULL AUTO_INCREMENT ,
  `modelo` VARCHAR(100) NOT NULL ,
  `codigo` VARCHAR(50) NULL DEFAULT NULL ,
  `fecha_adquisicion` DATE NULL DEFAULT NULL ,
  `aulas_id_aula` INT(11) NULL DEFAULT NULL ,
  `puesto` INT(1) NULL ,
  `tipos_id_tipo` INT(11) NOT NULL ,
  `proveedores_id_proveedor` INT(11) NULL DEFAULT NULL ,
  `estado` ENUM('En uso', 'Baja', 'Averiado') NULL DEFAULT NULL ,
  `obs` VARCHAR(200) NULL DEFAULT NULL ,
  `departamentos_id_departamento` INT(11) NOT NULL ,
  PRIMARY KEY (`id_inventario`, `departamentos_id_departamento`) ,
  UNIQUE INDEX `codigo_UNIQUE` (`codigo` ASC) ,
  INDEX `fk_inventario_aulas1_idx` (`aulas_id_aula` ASC) ,
  INDEX `fk_inventario_tipos1_idx` (`tipos_id_tipo` ASC) ,
  INDEX `fk_inventario_proveedores1_idx` (`proveedores_id_proveedor` ASC) ,
  INDEX `fk_inventario_departamentos1_idx` (`departamentos_id_departamento` ASC) ,
  CONSTRAINT `fk_inventario_aulas1`
    FOREIGN KEY (`aulas_id_aula` )
    REFERENCES `aulas` (`id_aula` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_inventario_departamentos1`
    FOREIGN KEY (`departamentos_id_departamento` )
    REFERENCES `departamentos` (`id_departamento` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_inventario_proveedores1`
    FOREIGN KEY (`proveedores_id_proveedor` )
    REFERENCES `proveedores` (`id_proveedor` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_inventario_tipos1`
    FOREIGN KEY (`tipos_id_tipo` )
    REFERENCES `tipos_materiales` (`id_tipo` )
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


CREATE  TABLE IF NOT EXISTS `usuarios` (
  `id_usuario` INT(11) NOT NULL AUTO_INCREMENT ,
  `nick` VARCHAR(202) NULL DEFAULT NULL ,
  `password` VARCHAR(200) NULL DEFAULT NULL ,
  `nombre` VARCHAR(100) NULL DEFAULT NULL ,
  `apellido` VARCHAR(100) NULL DEFAULT NULL ,
  `estado` TINYINT(1) NOT NULL DEFAULT 1 ,
  `email` VARCHAR(100) NOT NULL ,
  `departamentos_id_departamento` INT(11) NOT NULL ,
  `perfil` tinyint(1) NOT NULL ,
  `activacion` VARCHAR(100) NULL ,
  PRIMARY KEY (`id_usuario`) ,
  INDEX `fk_usuarios_departamentos1_idx` (`departamentos_id_departamento` ASC) ,
  UNIQUE INDEX `email_UNIQUE` (`email` ASC) ,
  CONSTRAINT `fk_usuarios_departamentos1`
    FOREIGN KEY (`departamentos_id_departamento` )
    REFERENCES `departamentos` (`id_departamento` )
    ON DELETE RESTRICT
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_usuarios_perfiles1`
    FOREIGN KEY (`perfil` )
    REFERENCES `perfiles` (`id` )
    ON DELETE RESTRICT
    ON UPDATE RESTRICT)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


CREATE  TABLE IF NOT EXISTS `incidencias` (
  `id_incidencia` INT(11) NOT NULL AUTO_INCREMENT ,
  `inventario_id_inventario` INT(11) NULL DEFAULT NULL ,
  `causa` VARCHAR(100) NULL DEFAULT NULL ,
  `descripcion` TEXT NULL DEFAULT NULL ,
  `fecha_incidencia` DATE NULL DEFAULT NULL ,
  `fecha_solucion` DATE NULL DEFAULT NULL ,
  `estadoinc` ENUM('Abierta en Centro', 'Abierta en Proveedor', 'Cerrada') NULL DEFAULT NULL ,
  `prioridad` ENUM('Media', 'Baja', 'Alta') NULL DEFAULT NULL ,
  `responsable` INT(11) NOT NULL ,
  `detectadapor` INT(11) NOT NULL ,
  PRIMARY KEY (`id_incidencia`) ,
  INDEX `fk_incidencias_inventario1_idx` (`inventario_id_inventario` ASC) ,
  INDEX `fk_incidencias_usuarios1_idx` (`responsable` ASC) ,
  INDEX `fk_incidencias_usuarios2_idx` (`detectadapor` ASC) ,
  CONSTRAINT `fk_incidencias_inventario1`
    FOREIGN KEY (`inventario_id_inventario` )
    REFERENCES `inventario` (`id_inventario` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_incidencias_usuarios1`
    FOREIGN KEY (`responsable` )
    REFERENCES `usuarios` (`id_usuario` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_incidencias_usuarios2`
    FOREIGN KEY (`detectadapor` )
    REFERENCES `usuarios` (`id_usuario` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


CREATE  TABLE IF NOT EXISTS `incidencias_det` (
  `id_incidencia_det` INT(11) NOT NULL AUTO_INCREMENT ,
  `incidencias_id_incidencia` INT(11) NOT NULL ,
  `fecha` DATE NULL DEFAULT NULL ,
  `causa` VARCHAR(100) NULL ,
  `descripcion_detalle` TEXT NULL DEFAULT NULL ,
  `usuarios_id_usuario` INT(11) NOT NULL ,
  `tipo` ENUM('Apertura', 'Cierre', 'Asignación', 'Reasignación', 'Actualización') NULL ,
  `estadomat` ENUM('En uso', 'Baja', 'Averiado') NULL ,
  PRIMARY KEY (`id_incidencia_det`) ,
  INDEX `fk_incidencias_det_incidencias1_idx` (`incidencias_id_incidencia` ASC) ,
  INDEX `fk_incidencias_det_usuarios1_idx` (`usuarios_id_usuario` ASC) ,
  CONSTRAINT `fk_incidencias_det_incidencias1`
    FOREIGN KEY (`incidencias_id_incidencia` )
    REFERENCES `incidencias` (`id_incidencia` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_incidencias_det_usuarios1`
    FOREIGN KEY (`usuarios_id_usuario` )
    REFERENCES `usuarios` (`id_usuario` )
    ON DELETE RESTRICT
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE  TABLE IF NOT EXISTS `materiales_departamento` (
  `id_materiales_departamento` INT(11) NOT NULL AUTO_INCREMENT ,
  `tipos_materiales_id_tipo` INT(11) NOT NULL ,
  `departamentos_id_departamento` INT(11) NOT NULL ,
  PRIMARY KEY (`id_materiales_departamento`, `tipos_materiales_id_tipo`, `departamentos_id_departamento`) ,
  INDEX `fk_materiales_departamento_tipos_materiales1_idx` (`tipos_materiales_id_tipo` ASC) ,
  INDEX `fk_materiales_departamento_departamentos1_idx` (`departamentos_id_departamento` ASC) ,
  CONSTRAINT `fk_materiales_departamento_departamentos1`
    FOREIGN KEY (`departamentos_id_departamento` )
    REFERENCES `departamentos` (`id_departamento` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_materiales_departamento_tipos_materiales1`
    FOREIGN KEY (`tipos_materiales_id_tipo` )
    REFERENCES `tipos_materiales` (`id_tipo` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


CREATE  TABLE IF NOT EXISTS `proveedores_departamento` (
  `id_prov_depto` INT(11) NOT NULL ,
  `departamentos_id_departamento` INT(11) NOT NULL ,
  `proveedores_id_proveedor` INT(11) NOT NULL ,
  PRIMARY KEY (`id_prov_depto`, `departamentos_id_departamento`, `proveedores_id_proveedor`) ,
  INDEX `fk_proveedores_departamento_departamentos1_idx` (`departamentos_id_departamento` ASC) ,
  INDEX `fk_proveedores_departamento_proveedores1_idx` (`proveedores_id_proveedor` ASC) ,
  CONSTRAINT `fk_proveedores_departamento_departamentos1`
    FOREIGN KEY (`departamentos_id_departamento` )
    REFERENCES `departamentos` (`id_departamento` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_proveedores_departamento_proveedores1`
    FOREIGN KEY (`proveedores_id_proveedor` )
    REFERENCES `proveedores` (`id_proveedor` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


CREATE  TABLE IF NOT EXISTS `usuarios_aulas` (
  `aulas_id_aula` INT(11) NOT NULL ,
  `usuarios_id_usuario` INT(11) NOT NULL ,
  `responsable` TINYINT(1) NULL DEFAULT NULL ,
  INDEX `fk_mantenimientos_aulas1_idx` (`aulas_id_aula` ASC) ,
  INDEX `fk_mantenimientos_usuarios1_idx` (`usuarios_id_usuario` ASC) ,
  PRIMARY KEY (`aulas_id_aula`, `usuarios_id_usuario`) ,
  CONSTRAINT `fk_mantenimientos_aulas1`
    FOREIGN KEY (`aulas_id_aula` )
    REFERENCES `aulas` (`id_aula` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_mantenimientos_usuarios1`
    FOREIGN KEY (`usuarios_id_usuario` )
    REFERENCES `usuarios` (`id_usuario` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


CREATE  TABLE IF NOT EXISTS `mensajes` (
  `id_mensaje` INT NOT NULL AUTO_INCREMENT ,
  `usuarios_id_usuario` INT NOT NULL ,
  `asunto` VARCHAR(100) NOT NULL ,
  `mensaje` TEXT NOT NULL ,
  `destino` VARCHAR(100) NOT NULL ,
  `fecha` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
  PRIMARY KEY (`id_mensaje`) ,
  INDEX `fk_mensajes_usuarios1_idx` (`usuarios_id_usuario` ASC) ,
  CONSTRAINT `fk_mensajes_usuarios1`
    FOREIGN KEY (`usuarios_id_usuario` )
    REFERENCES `usuarios` (`id_usuario` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


CREATE  TABLE IF NOT EXISTS `mantenimientos` (
  `id_mantenimiento` INT(11) NOT NULL AUTO_INCREMENT ,
  `causa` VARCHAR(100) NULL DEFAULT NULL ,
  `descripcion` TEXT NULL DEFAULT NULL ,
  `fecha_mantenimiento` DATE NULL DEFAULT NULL ,
  `fecha_solucion` DATE NULL DEFAULT NULL ,
  `estadoman` ENUM('Abierto', 'Concluido') NULL DEFAULT NULL ,
  `prioridad` ENUM('Media', 'Baja', 'Alta') NULL DEFAULT NULL ,
  `responsable` INT(11) NOT NULL ,
  `detectadapor` INT(11) NOT NULL ,
  `aulas_id_aula` INT(11) NOT NULL ,
  PRIMARY KEY (`id_mantenimiento`) ,
  INDEX `fk_incidencias_usuarios1_idx` (`responsable` ASC) ,
  INDEX `fk_incidencias_usuarios2_idx` (`detectadapor` ASC) ,
  INDEX `fk_mantenimientos_aulas2_idx` (`aulas_id_aula` ASC) ,
  CONSTRAINT `fk_incidencias_usuarios10`
    FOREIGN KEY (`responsable` )
    REFERENCES `usuarios` (`id_usuario` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_incidencias_usuarios20`
    FOREIGN KEY (`detectadapor` )
    REFERENCES `usuarios` (`id_usuario` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_mantenimientos_aulas2`
    FOREIGN KEY (`aulas_id_aula` )
    REFERENCES `aulas` (`id_aula` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


CREATE  TABLE IF NOT EXISTS `mantenimientos_det` (
  `id_mantenimiento_det` INT(11) NOT NULL AUTO_INCREMENT ,
  `inventario_id_inventario` INT(11) NOT NULL ,
  `mantenimientos_id_mantenimiento` INT(11) NOT NULL ,
  PRIMARY KEY (`id_mantenimiento_det`) ,
  INDEX `fk_mantenimientos_det_inventario1_idx` (`inventario_id_inventario` ASC) ,
  INDEX `fk_mantenimientos_det_mantenimientos1_idx` (`mantenimientos_id_mantenimiento` ASC) ,
  CONSTRAINT `fk_mantenimientos_det_inventario1`
    FOREIGN KEY (`inventario_id_inventario` )
    REFERENCES `inventario` (`id_inventario` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_mantenimientos_det_mantenimientos1`
    FOREIGN KEY (`mantenimientos_id_mantenimiento` )
    REFERENCES `mantenimientos` (`id_mantenimiento` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


CREATE TABLE `perfiles` (
  `id` tinyint(1) NOT NULL AUTO_INCREMENT,
  `perfil` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
);


CREATE TABLE `acl` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `controller` varchar(100) NOT NULL,
  `action` varchar(100) DEFAULT NULL,
  `nombre` varchar(100) DEFAULT 'Regla nueva, sin definir',
  `descripcion` varchar(200) DEFAULT NULL,
  `visible` boolean default true,
  PRIMARY KEY (`id`),
  UNIQUE KEY `controller` (`controller`,`action`)
);


CREATE TABLE `acl_to_perfiles` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `acl_id` int(10) NOT NULL,
  `perfil_id` tinyint(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `acl_id` (`acl_id`),
  KEY `perfil_id` (`perfil_id`),
  CONSTRAINT `acl_to_perfiles_ibfk_1` FOREIGN KEY (`acl_id`) 
     REFERENCES `acl` (`id`) ON DELETE CASCADE,
  CONSTRAINT `acl_to_perfiles_ibfk_2` FOREIGN KEY (`perfil_id`) 
     REFERENCES `perfiles` (`id`) ON DELETE CASCADE
);


CREATE TABLE IF NOT EXISTS `incidenciasfungibles` (
  `id_incidencia` int(11) NOT NULL AUTO_INCREMENT,
  `aulas_id_aula` int(11) DEFAULT NULL,
  `causa` varchar(100) DEFAULT NULL,
  `descripcion` text,
  `fecha_incidencia` date DEFAULT NULL,
  `fecha_solucion` date DEFAULT NULL,
  `estadoinc` enum('Abierta en Centro','Abierta en Proveedor','Cerrada') DEFAULT NULL,
  `prioridad` enum('Media','Baja','Alta') DEFAULT NULL,
  `responsable` int(11) NOT NULL,
  `detectadapor` int(11) NOT NULL,
  PRIMARY KEY (`id_incidencia`),
  KEY `fk_incidenciasfungibles_aulas_idx` (`aulas_id_aula`),
  KEY `fk_incidenciasfungibles_usuarios1_idx` (`responsable`),
  KEY `fk_incidenciasfungibles_usuarios2_idx` (`detectadapor`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

ALTER TABLE `incidenciasfungibles`
  ADD CONSTRAINT `fk_incidenciasfungibles_aula` FOREIGN KEY (`aulas_id_aula`) REFERENCES `aulas` (`id_aula`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_incidenciasfungibles_usuarios1` FOREIGN KEY (`responsable`) REFERENCES `usuarios` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_incidenciasfungibles_usuarios2` FOREIGN KEY (`detectadapor`) REFERENCES `usuarios` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION;


CREATE TABLE IF NOT EXISTS `incidenciasfungibles_det` (
  `id_incidencia_det` int(11) NOT NULL AUTO_INCREMENT,
  `incidencias_id_incidencia` int(11) NOT NULL,
  `fecha` date DEFAULT NULL,
  `causa` varchar(100) DEFAULT NULL,
  `descripcion_detalle` text,
  `usuarios_id_usuario` int(11) NOT NULL,
  `tipo` enum('Apertura','Cierre','Asignación','Reasignación','Actualización') DEFAULT NULL,
  PRIMARY KEY (`id_incidencia_det`),
  KEY `fk_incidenciasfungibles_det_incidencias_idx` (`incidencias_id_incidencia`),
  KEY `fk_incidenciasfungibles_det_usuarios1_idx` (`usuarios_id_usuario`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;


ALTER TABLE `incidenciasfungibles_det`
  ADD CONSTRAINT `fk_incidenciasfungibles_det_incidencias1` FOREIGN KEY (`incidencias_id_incidencia`) REFERENCES `incidenciasfungibles` (`id_incidencia`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_incidenciasfungibles_det_usuarios1` FOREIGN KEY (`usuarios_id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON UPDATE NO ACTION;



CREATE  OR REPLACE VIEW vaula
AS
SELECT
id_aula, aula, aulas.descripcion as descripcion, aulas.departamentos_id_departamento as id_departamento, departamento, 
group_concat(usuarios_id_usuario) as id_usuarios, 
concat('<ul><li>',group_concat(CONCAT(nombre, ' ', apellido) order by apellido separator '</li><li>'),'</li></ul>') as responsables
FROM
aulas
left join departamentos on departamentos.id_departamento=aulas.departamentos_id_departamento
left join usuarios_aulas on aulas.id_aula=usuarios_aulas.aulas_id_aula and responsable=true
left join usuarios on usuarios.id_usuario= usuarios_aulas.usuarios_id_usuario
group by id_aula;


CREATE  OR REPLACE VIEW vinventario
AS
SELECT
id_inventario, modelo, inventario.codigo as codigo, fecha_adquisicion, inventario.aulas_id_aula, aula, puesto, tipos_id_tipo, 
tipo, proveedores_id_proveedor as id_proveedor, proveedor, inventario.estado,
obs, inventario.departamentos_id_departamento as id_departamento, departamento,
concat('<ul><li>',group_concat(CONCAT(nombre, ' ', apellido) order by apellido separator '</li><li>'),'</li></ul>') as responsables
FROM
inventario
left join departamentos on departamentos.id_departamento=inventario.departamentos_id_departamento
left join tipos_materiales on tipos_materiales.id_tipo=inventario.tipos_id_tipo
left join proveedores on proveedores.id_proveedor=inventario.proveedores_id_proveedor
left join aulas on aulas.id_aula=inventario.aulas_id_aula
left join usuarios_aulas on aulas.id_aula=usuarios_aulas.aulas_id_aula and responsable=true
left join usuarios on usuarios.id_usuario= usuarios_aulas.usuarios_id_usuario
group by id_inventario, id_aula;


CREATE  OR REPLACE VIEW vproveedores
AS
SELECT
id_proveedor, proveedor, proveedores.codigo as codigo, proveedores.descripcion as descripcion, material, telefono, movil, fax, nif, email, direccion, localidad, provincia, cp, 
group_concat(id_departamento order by departamento ASC SEPARATOR ',') as id_departamento, 
concat('<ul><li>',group_concat(departamento order by departamento separator '</li><li>'),'</li></ul>') as departamentos 
FROM
proveedores
left join proveedores_departamento on proveedores.id_proveedor=proveedores_departamento.proveedores_id_proveedor
left join departamentos on departamentos.id_departamento=proveedores_departamento.departamentos_id_departamento
group by id_proveedor;


CREATE OR REPLACE VIEW vusuarios
AS
SELECT
id_usuario, CONCAT(nombre, ' ', apellido) as nombrecompleto, nombre, apellido, estado, email, usuarios.departamentos_id_departamento, departamento, perfiles.id as id_perfil, perfiles.perfil as perfil, group_concat(id_aula) as id_aulas, 
concat('<ul><li>',group_concat(aulas.aula order by aulas.aula separator '</li><li>'),'</li></ul>') as aulas
FROM
usuarios
left join departamentos on departamentos.id_departamento=usuarios.departamentos_id_departamento
left join usuarios_aulas on usuarios.id_usuario=usuarios_aulas.usuarios_id_usuario 
left join aulas on aulas.id_aula = usuarios_aulas.aulas_id_aula
left join perfiles on usuarios.perfil = perfiles.id
group by usuarios.id_usuario;

CREATE  OR REPLACE VIEW vincidencias
AS
SELECT
id_incidencia, id_inventario, modelo, inventario.codigo as codigo, fecha_adquisicion, puesto, proveedor, responsable, CONCAT(nombre, ' ', apellido) as nombrecompleto, incidencias.estadoinc as estado, departamentos.id_departamento as id_departamento, departamento, aulas_id_aula, aula,
causa, incidencias.descripcion as descripcion, fecha_incidencia, fecha_solucion, prioridad, tipo
FROM
incidencias
left join inventario on incidencias.inventario_id_inventario=inventario.id_inventario
left join proveedores on inventario.proveedores_id_proveedor=proveedores.id_proveedor
left join usuarios on usuarios.id_usuario=incidencias.responsable
left join aulas on aulas.id_aula=inventario.aulas_id_aula
left join departamentos on inventario.departamentos_id_departamento=departamentos.id_departamento
join tipos_materiales on tipos_materiales.id_tipo=inventario.tipos_id_tipo;


CREATE  OR REPLACE VIEW vincidencias_usuarios
AS
SELECT
id_incidencia_det, incidencias_id_incidencia, fecha, causa, descripcion_detalle, usuarios_id_usuario, tipo, CONCAT(nombre, ' ', apellido) as nombrecompleto, estadomat
FROM
incidencias_det
join usuarios on usuarios.id_usuario=incidencias_det.usuarios_id_usuario;


CREATE  OR REPLACE VIEW vmantenimientos
AS
SELECT
id_mantenimiento, causa, mantenimientos.descripcion, fecha_mantenimiento, fecha_solucion, estadoman, prioridad, responsable, detectadapor, 
CONCAT(nombre, ' ', apellido) as nombrecompleto, id_departamento, departamento, aulas_id_aula, aula
FROM
mantenimientos
left join usuarios on usuarios.id_usuario=mantenimientos.responsable
left join aulas on aulas.id_aula=mantenimientos.aulas_id_aula
left join departamentos on aulas.departamentos_id_departamento=departamentos.id_departamento;


CREATE  OR REPLACE VIEW `vhistorial` AS
select id_incidencia, "Incidencia" as tipo, fecha_incidencia, inventario_id_inventario, causa, descripcion, estadoinc, CONCAT(nombre, ' ', apellido) as responsable from
incidencias, usuarios where responsable=id_usuario
UNION
select id_mantenimiento, "Mantenimiento", fecha_mantenimiento, inventario_id_inventario, causa, descripcion, estadoman, CONCAT(nombre, ' ', apellido) as responsable from
mantenimientos, usuarios, mantenimientos_det where responsable=id_usuario and mantenimientos_id_mantenimiento=id_mantenimiento
;


CREATE OR REPLACE VIEW vpermisos AS
select acl.controller, acl.action, acl.nombre, acl.descripcion, acl.visible, acl.id as acl_id, acl_to_perfiles.perfil_id as perfil_id, perfil,
acl_to_perfiles.id as regla_id from acl 
join acl_to_perfiles on acl_to_perfiles.acl_id=acl.id 
join perfiles on perfiles.id=acl_to_perfiles.perfil_id
UNION
select acl.controller, acl.action, acl.nombre, acl.descripcion, acl.visible, acl.id as acl_id, perfiles.id, perfil,
null
from 
acl, perfiles
where concat (acl.controller, acl.action, perfiles.id) not in (select concat(acl.controller, acl.action, acl_to_perfiles.perfil_id) from acl join acl_to_perfiles on acl_to_perfiles.acl_id=acl.id 
join perfiles on perfiles.id=acl_to_perfiles.perfil_id)
order by perfil, controller;

CREATE OR REPLACE VIEW vincidenciasfungibles_usuarios
AS
SELECT
id_incidencia_det, incidencias_id_incidencia, fecha, causa, descripcion_detalle, usuarios_id_usuario, tipo, CONCAT(nombre, ' ', apellido) as nombrecompleto
FROM
incidenciasfungibles_det
join usuarios on usuarios.id_usuario=incidenciasfungibles_det.usuarios_id_usuario;


CREATE VIEW vincidenciasfungibles
AS
SELECT
id_incidencia, responsable, CONCAT(nombre, ' ', apellido) as nombrecompleto, incidenciasfungibles.estadoinc as estado, departamentos.id_departamento as id_departamento, departamento, aulas_id_aula, aula,
causa, incidenciasfungibles.descripcion as descripcion, fecha_incidencia, fecha_solucion, prioridad
FROM
incidenciasfungibles
left join usuarios on usuarios.id_usuario=incidenciasfungibles.responsable
left join aulas on aulas.id_aula=incidenciasfungibles.aulas_id_aula
left join departamentos on aulas.departamentos_id_departamento=departamentos.id_departamento;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

-- Insertamos los mínimos datos necesarios--:

INSERT INTO perfiles (id, perfil) VALUES (1, 'Profesor');
INSERT INTO perfiles (id, perfil) VALUES (2, 'Jefe de Departamento');
INSERT INTO perfiles (id, perfil) VALUES (3, 'Secretario');
INSERT INTO perfiles (id, perfil) VALUES (4, 'Jefe de Estudios');
INSERT INTO perfiles (id, perfil) VALUES (5, 'Director');

INSERT INTO `acl` (`id`, `controller`, `action`, `nombre`, `descripcion`, `visible`) VALUES
(1, 'aulas', 'index', 'Configuración Aulas', 'Listado de aulas', 1),
(2, 'aulas', 'borrar', 'Configuración Aulas', 'Borrado de aulas', 1),
(3, 'aulas', 'guardar', 'Configuración Aulas', 'Crear nuevas aulas o modificar existentes.', 1),
(4, 'aulas', 'getresponsables', 'Configuración Aulas', 'Aulas: Obtener responsables de aula. Necesario para incidencias y mantenimientos.', 0),
(5, 'aulas', 'getprofesores', 'Configuración Aulas', 'Aulas: Obtener profesores del aula. Necesario para dar de alta incidencias y mantenimientos.', 0),
(6, 'departamentos', 'index', 'Configuración Departamentos', 'Listado de departamentos', 1),
(7, 'departamentos', 'borrar', 'Configuración Departamentos', 'Borrado de departamentos', 1),
(8, 'departamentos', 'guardar', 'Configuración Departamentos', 'Crear nuevos departamentos o modificar existentes', 1),
(9, 'departamentos', 'getprofesores', 'Configuración Departamentos', 'Departamentos: obtener listado de profesores del departamento. Necesario para incidencias y mantenimientos', 0),
(10, 'departamentos', 'getjefedepartamento', 'Configuración Departamentos', 'Departamentos: obtener jefe del departamento. Necesario para incidencias y mantenimientos', 0),
(11, 'incidencias', 'index', 'Incidencias Equipos', 'Listado de incidencias del material del inventario', 1),
(12, 'incidencias', 'borrar', 'Incidencias Equipos', 'Borrar incidencias de material del inventario', 1),
(13, 'incidencias', 'editar', 'Incidencias Equipos', 'Editar incidencia de material del inventario. Necesario para el permiso de Guardar nuevas incidencias.', 1),
(14, 'incidencias', 'editardet', 'Incidencias Equipos', 'Ver el detalle de una incidencia de material del inventario', 1),
(15, 'incidencias', 'guardar', 'Incidencias Equipos', 'Guardar nuevas incidencias de material del inventario', 1),
(16, 'incidencias', 'guardardet', 'Incidencias Equipos', 'Modificar incidencia de material del inventario: reasignarla, cambiar su estado o cerrarla', 1),
(17, 'index', 'index', 'Inicio', 'Inicio: Acceso a la pantalla inicial de la aplicación. Necesario siempre', 0),
(18, 'inventario', 'index', 'Inventario', 'Listado de inventario', 1),
(19, 'inventario', 'historial', 'Inventario', 'Ver historial de incidencias y mantenimientos del material del inventario. Necesita permisos de Inventario: Listado de Inventario', 1),
(20, 'inventario', 'borrar', 'Inventario', 'Borrar materiales del inventario', 1),
(21, 'inventario', 'guardar', 'Inventario', 'Crear nuevos materiales o modificar existentes del inventario', 1),
(22, 'inventario', 'obtenercodigo', 'Inventario', 'Obtener código para material del inventario', 1),
(23, 'mantenimientos', 'index', 'Mantenimientos', 'Listado de mantenimientos', 1),
(24, 'mantenimientos', 'borrar', 'Mantenimientos', 'Borrar mantenimientos', 1),
(25, 'mantenimientos', 'editar', 'Mantenimientos', 'Editar mantenimientos', 1),
(26, 'mantenimientos', 'guardar', 'Mantenimientos', 'Crear nuevo mantenimiento o modificar existentes', 1),
(27, 'materiales', 'index', 'Configuración Tipos de Materiales', 'Listado de tipos de materiales', 1),
(28, 'materiales', 'borrar', 'Configuración Tipos de Materiales', 'Borrado de tipos de materiales', 1),
(29, 'materiales', 'guardar', 'Configuración Tipos de Materiales', 'Crear nuevos tipos de materiales o modificar existentes', 1),
(30, 'permisos', 'index', 'Configuración Permisos', 'Listado de permisos por usuario.', 1),
(31, 'permisos', 'borrar', 'Configuración Permisos', 'Eliminar permisos a usuarios', 1),
(32, 'permisos', 'guardar', 'Configuración Permisos', 'Añadir permisos a usuarios', 1),
(33, 'proveedores', 'index', 'Configuración Proveedores', 'Listado de proveedores', 1),
(34, 'proveedores', 'borrar', 'Configuración Proveedores', 'Borrado de proveedores', 1),
(35, 'proveedores', 'guardar', 'Configuración Proveedores', 'Crear nuevos proveedores o modificar existentes', 1),
(36, 'usuarios', 'index', 'Configuración Usuarios', 'Listado de usuarios ', 1),
(37, 'usuarios', 'borrar', 'Configuración Usuarios', 'Borrado de usuarios', 1),
(38, 'usuarios', 'cambiarestado', 'Configuración Usuarios', 'Activar o desactivar el acceso de un usuario a la aplicación', 1),
(39, 'usuarios', 'guardar', 'Configuración Usuarios', 'Crear nuevo usuario o modificar existente', 1),
(40, 'usuarios', 'preactivar', 'Configuración', 'No es necesario configurarlo', 0),
(41, 'usuarios', 'activar', 'Configuración', 'No es necesario configurarlo', 0),
(42, 'usuarios', 'login', 'Configuración', 'No es necesario configurarlo', 0),
(43, 'usuarios', 'logout', 'Configuración', 'Usuarios: cerrar sesión. ¡Deberían tener permiso siempre!', 0),
(44, 'incidenciasfungibles', 'index', 'Incidencias fungible', 'Listado de incidencias de material fungible', 1),
(45, 'incidenciasfungibles', 'borrar', 'Incidencias fungible', 'Borrar incidencias de material fungible', 1),
(46, 'incidenciasfungibles', 'editar', 'Incidencias fungible', 'Editar incidencia de material fungible. Necesario para el permiso de Guardar nuevas incidencias.', 1),
(47, 'incidenciasfungibles', 'editardet', 'Incidencias fungible', 'Ver el detalle de una incidencia de material fungible', 1),
(48, 'incidenciasfungibles', 'guardar', 'Incidencias fungible', 'Guardar nuevas incidencias de material fungible', 1),
(49, 'incidenciasfungibles', 'guardardet', 'Incidencias fungible', 'Modificar incidencia de material fungible: reasignarla, cambiar su estado o cerrarla', 1),
(50, 'compras', 'index', 'Regla nueva, sin definir', NULL, 1);

INSERT INTO `acl_to_perfiles` (`id`, `acl_id`, `perfil_id`) VALUES
(4, 4, 1),
(5, 5, 1),
(9, 9, 1),
(10, 10, 1),
(11, 11, 1),
(12, 12, 1),
(13, 13, 1),
(14, 14, 1),
(15, 15, 1),
(16, 16, 1),
(17, 17, 1),
(18, 18, 1),
(19, 19, 1),
(22, 22, 1),
(23, 23, 1),
(24, 24, 1),
(25, 25, 1),
(26, 26, 1),
(40, 40, 1),
(41, 41, 1),
(42, 42, 1),
(43, 43, 1),
(44, 44, 1),
(45, 45, 1),
(46, 46, 1),
(47, 47, 1),
(48, 48, 1),
(49, 49, 1),
(50, 1, 2),
(51, 2, 2),
(52, 3, 2),
(53, 4, 2),
(54, 5, 2),
(58, 9, 2),
(59, 10, 2),
(60, 11, 2),
(61, 12, 2),
(62, 13, 2),
(63, 14, 2),
(64, 15, 2),
(65, 16, 2),
(66, 17, 2),
(67, 18, 2),
(68, 19, 2),
(69, 20, 2),
(70, 21, 2),
(71, 22, 2),
(72, 23, 2),
(73, 24, 2),
(74, 25, 2),
(75, 26, 2),
(85, 36, 2),
(86, 37, 2),
(87, 38, 2),
(88, 39, 2),
(89, 40, 2),
(90, 41, 2),
(91, 42, 2),
(92, 43, 2),
(93, 44, 2),
(94, 45, 2),
(95, 46, 2),
(96, 47, 2),
(97, 48, 2),
(98, 49, 2),
(99, 1, 3),
(100, 2, 3),
(101, 3, 3),
(102, 4, 3),
(103, 5, 3),
(104, 6, 3),
(105, 7, 3),
(106, 8, 3),
(107, 9, 3),
(108, 10, 3),
(109, 11, 3),
(110, 12, 3),
(111, 13, 3),
(112, 14, 3),
(113, 15, 3),
(114, 16, 3),
(115, 17, 3),
(116, 18, 3),
(117, 19, 3),
(118, 20, 3),
(119, 21, 3),
(120, 22, 3),
(121, 23, 3),
(122, 24, 3),
(123, 25, 3),
(124, 26, 3),
(125, 27, 3),
(126, 28, 3),
(127, 29, 3),
(128, 30, 3),
(129, 31, 3),
(130, 32, 3),
(131, 33, 3),
(132, 34, 3),
(133, 35, 3),
(134, 36, 3),
(135, 37, 3),
(136, 38, 3),
(137, 39, 3),
(138, 40, 3),
(139, 41, 3),
(140, 42, 3),
(141, 43, 3),
(142, 44, 3),
(143, 45, 3),
(144, 46, 3),
(145, 47, 3),
(146, 48, 3),
(147, 49, 3),
(148, 1, 4),
(149, 2, 4),
(150, 3, 4),
(151, 4, 4),
(152, 5, 4),
(153, 6, 4),
(154, 7, 4),
(155, 8, 4),
(156, 9, 4),
(157, 10, 4),
(158, 11, 4),
(159, 12, 4),
(160, 13, 4),
(161, 14, 4),
(162, 15, 4),
(163, 16, 4),
(164, 17, 4),
(165, 18, 4),
(166, 19, 4),
(167, 20, 4),
(168, 21, 4),
(169, 22, 4),
(170, 23, 4),
(171, 24, 4),
(172, 25, 4),
(173, 26, 4),
(174, 27, 4),
(175, 28, 4),
(176, 29, 4),
(177, 30, 4),
(178, 31, 4),
(179, 32, 4),
(180, 33, 4),
(181, 34, 4),
(182, 35, 4),
(183, 36, 4),
(184, 37, 4),
(185, 38, 4),
(186, 39, 4),
(187, 40, 4),
(188, 41, 4),
(189, 42, 4),
(190, 43, 4),
(191, 44, 4),
(192, 45, 4),
(193, 46, 4),
(194, 47, 4),
(195, 48, 4),
(196, 49, 4),
(197, 1, 5),
(198, 2, 5),
(199, 3, 5),
(200, 4, 5),
(201, 5, 5),
(202, 6, 5),
(203, 7, 5),
(204, 8, 5),
(205, 9, 5),
(206, 10, 5),
(207, 11, 5),
(208, 12, 5),
(209, 13, 5),
(210, 14, 5),
(211, 15, 5),
(212, 16, 5),
(213, 17, 5),
(214, 18, 5),
(215, 19, 5),
(216, 20, 5),
(217, 21, 5),
(218, 22, 5),
(219, 23, 5),
(220, 24, 5),
(221, 25, 5),
(222, 26, 5),
(223, 27, 5),
(224, 28, 5),
(225, 29, 5),
(226, 30, 5),
(227, 31, 5),
(228, 32, 5),
(229, 33, 5),
(230, 34, 5),
(231, 35, 5),
(232, 36, 5),
(233, 37, 5),
(234, 38, 5),
(235, 39, 5),
(236, 40, 5),
(237, 41, 5),
(238, 42, 5),
(239, 43, 5),
(240, 44, 5),
(241, 45, 5),
(242, 46, 5),
(243, 47, 5),
(244, 48, 5),
(245, 49, 5);

INSERT INTO `departamentos` (`id_departamento`, `departamento`, `descripcion`, `codigo`) VALUES
(1, 'Centro', 'Nombre departamento genérico', 'CENTRO');

INSERT INTO `usuarios` (`id_usuario`, `nick`, `password`, `nombre`, `apellido`, `estado`, `email`, `departamentos_id_departamento`, `perfil`, `activacion`) VALUES
(1, '', 'e9a6f9e26c8e0a3ee75b61fe36649e83b60755d6', 'Usuario', 'Administrador', 1, 'user@user.com', 1, 5, NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;







