-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 08-02-2026 a las 13:20:24
-- Versión del servidor: 5.7.23-23
-- Versión de PHP: 8.1.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Base de datos: `iedeocci_mycar`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracion`
--

CREATE TABLE `configuracion` (
  `id` bigint(1) NOT NULL,
  `nit` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `nombreLaboratorio` varchar(100) COLLATE utf8_spanish2_ci NOT NULL,
  `nombreCorto` varchar(20) COLLATE utf8_spanish2_ci NOT NULL,
  `direccionLaboratorio` varchar(100) COLLATE utf8_spanish2_ci NOT NULL,
  `telefonosLaboratorio` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `correoLaboratorio` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `webLaboratorio` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `bacteriologoLaboratorio` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `usuario` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `tarjetaPLaboratorio` varchar(20) COLLATE utf8_spanish2_ci NOT NULL,
  `urFirmaLaboratorio` longtext COLLATE utf8_spanish2_ci NOT NULL,
  `urlLogoLaboratorio` longtext COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `coprologico`
--

CREATE TABLE `coprologico` (
  `id` bigint(20) NOT NULL,
  `identificacion` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `consistencia` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `color` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `sangre` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `moco` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `otros_macroscopicos` text COLLATE utf8_spanish2_ci,
  `ph` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `endamoeba_histolitica_quistes` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `endamoeba_coli_quistes` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `endolimax_quistes` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `iodamoeba_quistes` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `giarda_lamblia_quistes` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `chilomastix_mesnili_quistes` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `trichomona_hominis_quistes` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `balantidium_coli_quistes` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `endamoeba_histolitica_trofozoitos` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `endamoeba_coli_trofozoitos` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `endolimax_trofozoitos` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `iodamoeba_trofozoitos` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `giarda_lamblia_trofozoitos` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `chilomastix_mesnili_trofozoitos` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `trichomona_hominis_trofozoitos` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `balantidium_coli_trofozoitos` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `Blastocystis_hominis_quistes` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `Blastocystis_hominis_trofozoitos` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `ascaris` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `tricocefalos` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `uncinaria` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `tenia_saginata` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `tenia_solium` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `himenolepsis` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `strongiloides_larva` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `oxiuros_huevos` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `sangre_oculta` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `lecucocitos` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `observaciones` text COLLATE utf8_spanish2_ci,
  `bacteriologo` varchar(50) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `fechahora` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `fechaResultados` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entidades`
--

CREATE TABLE `entidades` (
  `id` bigint(20) NOT NULL,
  `nombre` varchar(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `examenes`
--

CREATE TABLE `examenes` (
  `ind` bigint(20) UNSIGNED NOT NULL,
  `codexamen` varchar(12) CHARACTER SET utf8 COLLATE utf8_spanish2_ci DEFAULT NULL,
  `identificacion` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish2_ci DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `realizado` char(1) DEFAULT NULL,
  `entidad` varchar(100) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `examenesold`
--

CREATE TABLE `examenesold` (
  `ind` bigint(20) UNSIGNED NOT NULL,
  `codexamen` varchar(12) CHARACTER SET utf8 COLLATE utf8_spanish2_ci DEFAULT NULL,
  `identificacion` varchar(12) CHARACTER SET utf8 COLLATE utf8_spanish2_ci DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `realizado` char(1) DEFAULT NULL,
  `entidad` varchar(12) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `examen_tipo_1`
--

CREATE TABLE `examen_tipo_1` (
  `ind` bigint(20) NOT NULL,
  `identificacion` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `examen` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `valoracion` varchar(100) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `bacteriologo` varchar(50) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `examenind` bigint(20) UNSIGNED DEFAULT NULL,
  `citasind` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `indice` bigint(20) UNSIGNED DEFAULT NULL,
  `entidad` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `observaciones` text COLLATE utf8_spanish2_ci,
  `panel2` varchar(25) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `departamento` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `constante` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `hora` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `pyp` char(0) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `fechaResultados` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `examen_tipo_2`
--

CREATE TABLE `examen_tipo_2` (
  `ind` bigint(20) NOT NULL,
  `identificacion` varchar(12) DEFAULT NULL,
  `examen` varchar(12) DEFAULT NULL,
  `valoracion` text,
  `fecha` date DEFAULT NULL,
  `examenind` bigint(20) UNSIGNED DEFAULT NULL,
  `citasind` varchar(12) DEFAULT NULL,
  `indice` bigint(20) UNSIGNED DEFAULT NULL,
  `entidad` varchar(12) DEFAULT NULL,
  `departamento` varchar(12) DEFAULT NULL,
  `hora` time DEFAULT NULL,
  `pyp` char(0) DEFAULT NULL,
  `observaciones` varchar(100) NOT NULL,
  `bacteriologo` varchar(50) NOT NULL,
  `fechaResultados` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `examen_tipo_3`
--

CREATE TABLE `examen_tipo_3` (
  `ind` bigint(20) NOT NULL,
  `identificacion` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `examen` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `densidad` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `color` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `aspecto` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `ph` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `olor` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `proteinas` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `glucosa` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `cuerpos_cetonicos` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `sangre_hemolizada` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `sangre_no_hemolizada` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `bilirrubina` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `urobilinogeno` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `nitritos` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `leucocitos` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `leucocitosm` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `moco` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `eritrocitos` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `levaduras` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `piocitos` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `celulas_epiteliales` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `uratos_amorfos` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `fosfatos_amorfos` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `oxalato_de_calcio` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `bacterias` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `cilindros_hialinos` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `cilindros_granulosos` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `observaciones` text COLLATE utf8_spanish2_ci,
  `doctor` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `examenind` bigint(20) UNSIGNED DEFAULT NULL,
  `citasind` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `indice` bigint(20) UNSIGNED DEFAULT NULL,
  `entidad` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `departamento` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `hora` time DEFAULT NULL,
  `pyp` char(0) COLLATE utf8_spanish2_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `examen_tipo_4`
--

CREATE TABLE `examen_tipo_4` (
  `ind` bigint(20) NOT NULL,
  `identificacion` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `examen` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `consistencia` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `color` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `sangre` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `moco` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `otros_macroscopicos` text COLLATE utf8_spanish2_ci,
  `ph` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `endamoeba_histolitica_quistes` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `endamoeba_coli_quistes` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `endolimax_quistes` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `iodamoeba_quistes` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `giarda_lamblia_quistes` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `chilomastix_mesnili_quistes` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `trichomona_hominis_quistes` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `balantidium_coli_quistes` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `endamoeba_histolitica_trofozoitos` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `endamoeba_coli_trofozoitos` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `endolimax_trofozoitos` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `iodamoeba_trofozoitos` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `giarda_lamblia_trofozoitos` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `chilomastix_mesnili_trofozoitos` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `trichomona_hominis_trofozoitos` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `balantidium_coli_trofozoitos` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `Blastocystis_hominis_quistes` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `Blastocystis_hominis_trofozoitos` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `ascaris` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `tricocefalos` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `uncinaria` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `tenia_saginata` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `tenia_solium` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `himenolepsis` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `strongiloides_larva` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `oxiuros_huevos` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `sangre_oculta` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `lecucocitos` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `observaciones` text COLLATE utf8_spanish2_ci,
  `doctor` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `examenind` bigint(20) UNSIGNED DEFAULT NULL,
  `citasind` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `indice` bigint(20) UNSIGNED DEFAULT NULL,
  `entidad` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `departamento` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `hora` time DEFAULT NULL,
  `pyp` char(0) COLLATE utf8_spanish2_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `examen_tipo_5`
--

CREATE TABLE `examen_tipo_5` (
  `ind` bigint(20) NOT NULL,
  `identificacion` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `examen` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `hemoglobina` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `sedimentacion` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `html` text COLLATE utf8_spanish2_ci,
  `hematocrito` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `sistematizado` text COLLATE utf8_spanish2_ci,
  `leucocitos` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `otros_general` text COLLATE utf8_spanish2_ci,
  `mielocitos` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `pid` text COLLATE utf8_spanish2_ci,
  `juveniles` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `en_cayados` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `neutrofilos` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `eosinofilos` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `basofilos` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `linfocitos` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `monocitos` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `observaciones` text COLLATE utf8_spanish2_ci,
  `doctor` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `examenind` bigint(20) UNSIGNED DEFAULT NULL,
  `citasind` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `indice` bigint(20) UNSIGNED DEFAULT NULL,
  `entidad` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `departamento` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `hora` time DEFAULT NULL,
  `wbc` longblob,
  `rbc` longblob,
  `plt` longblob,
  `pyp` char(0) COLLATE utf8_spanish2_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `examen_tipo_7`
--

CREATE TABLE `examen_tipo_7` (
  `ind` bigint(20) NOT NULL,
  `identificacion` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `examen` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `tiempo_de_protrombina` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `tiempo_de_control` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `razon` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `inr` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `isi` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `tpts` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `doctor` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `observaciones` text COLLATE utf8_spanish2_ci,
  `examenind` bigint(20) UNSIGNED DEFAULT NULL,
  `citasind` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `indice` bigint(20) UNSIGNED DEFAULT NULL,
  `entidad` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `departamento` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `valor_de_referencia` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `hora` time DEFAULT NULL,
  `pyp` char(0) COLLATE utf8_spanish2_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `extemp`
--

CREATE TABLE `extemp` (
  `ind` int(11) NOT NULL,
  `codexamen` varchar(16) CHARACTER SET utf8 DEFAULT NULL,
  `identificacion` varchar(16) CHARACTER SET utf8 DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `realizado` char(0) CHARACTER SET utf8 DEFAULT NULL,
  `entidad` varchar(16) CHARACTER SET utf8 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `frotisVaginal`
--

CREATE TABLE `frotisVaginal` (
  `id` bigint(20) NOT NULL,
  `identificacion` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `examen` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `otros_fresco` text COLLATE utf8_spanish2_ci,
  `prueba_de_aminas` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `celulas_guia1` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `ph` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `trichonomas_vaginales` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `pmn` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `celulas_guia2` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `blastoconidias` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `seudomicelios` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `lactobacillus` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `gardnerella_sp` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `bacteroides_sp` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `mobilluncus_sp` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `pmnx` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `intracelulares` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `extracelulares` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `observaciones` text COLLATE utf8_spanish2_ci,
  `bacteriologo` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `fechaResultados` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `hemasist`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `hemasist` (
`identificacion` varchar(12)
,`examen` varchar(12)
,`fecha` date
,`sistematizado` text
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hemogramaRayto`
--

CREATE TABLE `hemogramaRayto` (
  `id` bigint(20) NOT NULL,
  `identificacion` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `fecha` date NOT NULL,
  `WBC` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `LYMn` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `MIDn` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `GRAn` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `LYMp` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `MIDp` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `GRAp` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `RBC` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `HGB` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `MCHC` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `MCH` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `MCV` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `RDWCV` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `RDWSD` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `HCT` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `PLT` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `MPV` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `PDW` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `PCT` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `PLCR` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `observaciones` text COLLATE utf8_spanish2_ci NOT NULL,
  `bacteriologo` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `fechahora` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fechaResultados` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `opcionesExamenes`
--

CREATE TABLE `opcionesExamenes` (
  `id` bigint(20) NOT NULL,
  `codexamen` varchar(10) COLLATE utf8_spanish2_ci NOT NULL,
  `campo` varchar(100) COLLATE utf8_spanish2_ci NOT NULL,
  `items` longtext COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `paciente`
--

CREATE TABLE `paciente` (
  `id` bigint(20) NOT NULL,
  `fecha` date DEFAULT NULL,
  `hora` time DEFAULT NULL,
  `tdei` varchar(50) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `identificacion` varchar(50) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `nombre1` varchar(50) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `nombre2` varchar(50) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `apellido1` varchar(50) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `apellido2` varchar(50) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `nombres` varchar(50) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `apellidos` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `genero` varchar(50) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `fecnac` date DEFAULT NULL,
  `edad` varchar(50) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `lugarnacimiento` varchar(50) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `direccion_residencia` varchar(50) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `ciudad_residencia` varchar(50) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `barrio` varchar(50) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `telefono` varchar(50) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `telefono_residencia2` varchar(50) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `telefono_movil` varchar(50) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `ocupacion` varchar(50) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `estado_civil` varchar(50) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `correo` varchar(50) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `email2` varchar(50) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `entidad` varchar(50) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `estado` varchar(50) COLLATE utf8_spanish2_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `pacientess`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `pacientess` (
`identificacion` varchar(50)
,`nombres` varchar(50)
,`apellidos` varchar(50)
,`fecnac` date
,`genero` varchar(50)
,`telefono` varchar(50)
,`correo` varchar(50)
,`entidad` varchar(50)
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `parcialOrina`
--

CREATE TABLE `parcialOrina` (
  `id` bigint(20) NOT NULL,
  `identificacion` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `densidad` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `color` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `aspecto` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `ph` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `olor` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `proteinas` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `glucosa` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `cuerpos_cetonicos` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `sangre_hemolizada` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `sangre_no_hemolizada` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `bilirrubina` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `urobilinogeno` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `nitritos` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `leucocitos` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `leucocitosm` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `moco` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `eritrocitos` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `levaduras` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `piocitos` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `celulas_epiteliales` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `uratos_amorfos` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `fosfatos_amorfos` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `oxalato_de_calcio` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `bacterias` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `cilindros_hialinos` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `cilindros_granulosos` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `observaciones` text COLLATE utf8_spanish2_ci,
  `bacteriologo` varchar(50) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `fechahora` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fechaResultados` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `perfilLipidico`
--

CREATE TABLE `perfilLipidico` (
  `id` bigint(20) NOT NULL,
  `identificacion` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `colesterol_total` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `colesterol_hdl` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `colesterol_vldl` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `colesterol_ldl` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `trigliceridos` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `indice_arterial` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `bacteriologo` varchar(50) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `observaciones` varchar(100) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `fechaResultados` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `procedimientos`
--

CREATE TABLE `procedimientos` (
  `ind` bigint(20) NOT NULL,
  `codigo` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `uuid` binary(36) NOT NULL,
  `nombre` varchar(50) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `tabla` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `info` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `color` varchar(50) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `constante` varchar(50) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `constante2` text COLLATE utf8_spanish2_ci NOT NULL,
  `unidades` varchar(50) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `tipo` char(3) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `tipoprocedimiento` varchar(50) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `abreviatura` varchar(100) COLLATE utf8_spanish2_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura para la vista `hemasist`
--
DROP TABLE IF EXISTS `hemasist`;

CREATE ALGORITHM=UNDEFINED DEFINER=`iedeocci`@`localhost` SQL SECURITY DEFINER VIEW `hemasist`  AS SELECT `iedeocci_lab`.`examen_tipo_5`.`identificacion` AS `identificacion`, `iedeocci_lab`.`examen_tipo_5`.`examen` AS `examen`, `iedeocci_lab`.`examen_tipo_5`.`fecha` AS `fecha`, `iedeocci_lab`.`examen_tipo_5`.`sistematizado` AS `sistematizado` FROM `iedeocci_lab`.`examen_tipo_5` WHERE ((`iedeocci_lab`.`examen_tipo_5`.`sistematizado` is not null) AND (`iedeocci_lab`.`examen_tipo_5`.`sistematizado` <> '')) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `pacientess`
--
DROP TABLE IF EXISTS `pacientess`;

CREATE ALGORITHM=UNDEFINED DEFINER=`iedeocci`@`localhost` SQL SECURITY DEFINER VIEW `pacientess`  AS SELECT `iedeocci_lab`.`paciente`.`identificacion` AS `identificacion`, `iedeocci_lab`.`paciente`.`nombres` AS `nombres`, `iedeocci_lab`.`paciente`.`apellidos` AS `apellidos`, `iedeocci_lab`.`paciente`.`fecnac` AS `fecnac`, `iedeocci_lab`.`paciente`.`genero` AS `genero`, `iedeocci_lab`.`paciente`.`telefono` AS `telefono`, `iedeocci_lab`.`paciente`.`correo` AS `correo`, `iedeocci_lab`.`paciente`.`entidad` AS `entidad` FROM `iedeocci_lab`.`paciente` ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `configuracion`
--
ALTER TABLE `configuracion`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nit` (`nit`,`nombreLaboratorio`);

--
-- Indices de la tabla `coprologico`
--
ALTER TABLE `coprologico`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `identificacion` (`identificacion`,`fecha`),
  ADD KEY `idind` (`identificacion`),
  ADD KEY `fecid` (`fecha`);

--
-- Indices de la tabla `entidades`
--
ALTER TABLE `entidades`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `examenes`
--
ALTER TABLE `examenes`
  ADD PRIMARY KEY (`ind`),
  ADD UNIQUE KEY `codexamen` (`codexamen`,`identificacion`,`fecha`),
  ADD KEY `identificacion` (`identificacion`),
  ADD KEY `fecha` (`fecha`),
  ADD KEY `codexamen_2` (`codexamen`);

--
-- Indices de la tabla `examenesold`
--
ALTER TABLE `examenesold`
  ADD PRIMARY KEY (`ind`),
  ADD UNIQUE KEY `codexamen` (`codexamen`,`identificacion`,`fecha`),
  ADD KEY `identificacion` (`identificacion`);

--
-- Indices de la tabla `examen_tipo_1`
--
ALTER TABLE `examen_tipo_1`
  ADD PRIMARY KEY (`ind`),
  ADD UNIQUE KEY `identificacion` (`identificacion`,`examen`,`fecha`),
  ADD KEY `idind` (`identificacion`),
  ADD KEY `exaind` (`examen`),
  ADD KEY `fecid` (`fecha`),
  ADD KEY `exid` (`examenind`),
  ADD KEY `indix` (`indice`),
  ADD KEY `citasindi` (`citasind`);

--
-- Indices de la tabla `examen_tipo_2`
--
ALTER TABLE `examen_tipo_2`
  ADD PRIMARY KEY (`ind`),
  ADD UNIQUE KEY `identificacion` (`identificacion`,`examen`,`fecha`);

--
-- Indices de la tabla `examen_tipo_3`
--
ALTER TABLE `examen_tipo_3`
  ADD PRIMARY KEY (`ind`),
  ADD KEY `idind` (`identificacion`),
  ADD KEY `exid` (`examen`),
  ADD KEY `fecid` (`fecha`),
  ADD KEY `indix` (`indice`);

--
-- Indices de la tabla `examen_tipo_4`
--
ALTER TABLE `examen_tipo_4`
  ADD PRIMARY KEY (`ind`),
  ADD KEY `idind` (`identificacion`),
  ADD KEY `exid` (`examen`),
  ADD KEY `fecid` (`fecha`),
  ADD KEY `exaid` (`examenind`),
  ADD KEY `indix` (`indice`);

--
-- Indices de la tabla `examen_tipo_5`
--
ALTER TABLE `examen_tipo_5`
  ADD PRIMARY KEY (`ind`),
  ADD KEY `indexid` (`identificacion`),
  ADD KEY `fecind` (`fecha`),
  ADD KEY `exid` (`examen`),
  ADD KEY `exaid` (`examenind`),
  ADD KEY `indix` (`indice`);

--
-- Indices de la tabla `examen_tipo_7`
--
ALTER TABLE `examen_tipo_7`
  ADD PRIMARY KEY (`ind`),
  ADD KEY `indexid` (`identificacion`),
  ADD KEY `fecid` (`fecha`),
  ADD KEY `exid` (`examenind`),
  ADD KEY `indix` (`indice`);

--
-- Indices de la tabla `frotisVaginal`
--
ALTER TABLE `frotisVaginal`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `identificacion` (`identificacion`,`fecha`);

--
-- Indices de la tabla `hemogramaRayto`
--
ALTER TABLE `hemogramaRayto`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `identificacion_2` (`identificacion`,`fecha`);

--
-- Indices de la tabla `opcionesExamenes`
--
ALTER TABLE `opcionesExamenes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codexamen` (`codexamen`,`campo`);

--
-- Indices de la tabla `paciente`
--
ALTER TABLE `paciente`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `identificacion` (`identificacion`);

--
-- Indices de la tabla `parcialOrina`
--
ALTER TABLE `parcialOrina`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `identificacion_2` (`identificacion`,`fecha`),
  ADD KEY `identificacion` (`identificacion`);

--
-- Indices de la tabla `perfilLipidico`
--
ALTER TABLE `perfilLipidico`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `identificacion` (`identificacion`,`fecha`);

--
-- Indices de la tabla `procedimientos`
--
ALTER TABLE `procedimientos`
  ADD PRIMARY KEY (`ind`),
  ADD UNIQUE KEY `codigo` (`codigo`,`nombre`),
  ADD UNIQUE KEY `codigo_2` (`codigo`,`nombre`,`tabla`,`tipo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `configuracion`
--
ALTER TABLE `configuracion`
  MODIFY `id` bigint(1) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `coprologico`
--
ALTER TABLE `coprologico`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `entidades`
--
ALTER TABLE `entidades`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `examenes`
--
ALTER TABLE `examenes`
  MODIFY `ind` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `examenesold`
--
ALTER TABLE `examenesold`
  MODIFY `ind` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `examen_tipo_1`
--
ALTER TABLE `examen_tipo_1`
  MODIFY `ind` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `examen_tipo_2`
--
ALTER TABLE `examen_tipo_2`
  MODIFY `ind` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `examen_tipo_3`
--
ALTER TABLE `examen_tipo_3`
  MODIFY `ind` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `examen_tipo_4`
--
ALTER TABLE `examen_tipo_4`
  MODIFY `ind` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `examen_tipo_5`
--
ALTER TABLE `examen_tipo_5`
  MODIFY `ind` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `examen_tipo_7`
--
ALTER TABLE `examen_tipo_7`
  MODIFY `ind` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `frotisVaginal`
--
ALTER TABLE `frotisVaginal`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `hemogramaRayto`
--
ALTER TABLE `hemogramaRayto`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `opcionesExamenes`
--
ALTER TABLE `opcionesExamenes`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `paciente`
--
ALTER TABLE `paciente`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `parcialOrina`
--
ALTER TABLE `parcialOrina`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `perfilLipidico`
--
ALTER TABLE `perfilLipidico`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `procedimientos`
--
ALTER TABLE `procedimientos`
  MODIFY `ind` bigint(20) NOT NULL AUTO_INCREMENT;
COMMIT;
