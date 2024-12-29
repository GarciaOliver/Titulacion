CREATE TABLE catalogo_estado (
    cat_id int AUTO_INCREMENT primary key,
    cat_estado VARCHAR(50)
);

CREATE TABLE idioma (
    idio_id int AUTO_INCREMENT primary key,
    idio_idioma VARCHAR(50),
    idio_dependencia int,
    cat_id int,
    FOREIGN KEY (cat_id) REFERENCES catalogo_estado(cat_id)
);

CREATE TABLE estudiante(
	est_id int primary key,
    cat_id int,
    FOREIGN KEY (cat_id) REFERENCES catalogo_estado(cat_id),
    FOREIGN KEY (est_id) REFERENCES estudiantes_ist17j.estudiante(est_id)
);

CREATE TABLE docente (
    usu_id int primary key,
    idio_id int,
    doc_permiso boolean,
    cat_id int,
    FOREIGN KEY (idio_id) REFERENCES idioma(idio_id),
    FOREIGN KEY (cat_id) REFERENCES catalogo_estado(cat_id),
    FOREIGN KEY (usu_id) REFERENCES activos_ist17j.usuario(usu_id)
);

CREATE TABLE resumen (
    res_id INT AUTO_INCREMENT PRIMARY KEY,
    est_id int,
    idio_id int,
    res_resumen TEXT,
    res_palabras_clave VARCHAR(255),
    res_fecha DATE,
    cat_id int,
    FOREIGN KEY (idio_id) REFERENCES idioma(idio_id),
    FOREIGN KEY (cat_id) REFERENCES catalogo_estado(cat_id),
    FOREIGN KEY (est_id) REFERENCES estudiante(est_id)
);

CREATE TABLE asignacion (
    asig_id int AUTO_INCREMENT primary key,
    res_id int,
    usu_id int,
    asig_fecha DATE,
    cat_id int,
    FOREIGN KEY (cat_id) REFERENCES catalogo_estado(cat_id),
    FOREIGN KEY (res_id) REFERENCES resumen(res_id),
    FOREIGN KEY (usu_id) REFERENCES docente(usu_id)    
);

CREATE TABLE revision (
    rev_id INT AUTO_INCREMENT PRIMARY KEY,
    asig_id INT,
    rev_observaciones VARCHAR(255),
    rev_fecha DATE,
	rev_archivo varchar(255),
    cat_id int,
    FOREIGN KEY (cat_id) REFERENCES catalogo_estado(cat_id),
    FOREIGN KEY (asig_id) REFERENCES asignacion(asig_id)
);

-- SP LOGIN --------------------------------------------------------------------------------------------------------

DELIMITER //

CREATE PROCEDURE sp_loginEst(
    IN usu_login_sp VARCHAR(50),
    IN usu_clave_sp VARCHAR(64)
)
BEGIN
    DECLARE est_id_sp INT;

    -- Verificar si el estudiante existe en estudiantes_ist17j.estudiante
    SELECT est_id INTO est_id_sp
    FROM estudiantes_ist17j.estudiante 
    WHERE est_login = usu_login_sp AND est_clave = usu_clave_sp
    LIMIT 1;

    -- Validar si se encontró un estudiante
    IF est_id_sp IS NOT NULL THEN
        -- Verificar si est_id_sp existe en resumenes_ist17j.estudiante
        IF EXISTS (
            SELECT 1 
            FROM resumenes_ist17j.estudiante 
            WHERE est_id = est_id_sp
        ) THEN
            -- Si existe, mostrar los datos del estudiante
            SELECT * 
            FROM estudiantes_ist17j.estudiante 
            WHERE est_login = usu_login_sp AND est_clave = usu_clave_sp;
        ELSE
            -- Si no existe, llamar a sp_estudiantes y luego mostrar los datos del estudiante
            CALL resumenes_ist17j.sp_estudiantes(0, est_id_sp, 1);
            SELECT * 
            FROM estudiantes_ist17j.estudiante 
            WHERE est_login = usu_login_sp AND est_clave = usu_clave_sp;
        END IF;
    END IF;

END //

DELIMITER ;

-- SP DOCENTES -----------------------------------------------------------------------------------------------

DELIMITER //
CREATE PROCEDURE sp_docentes(
	IN op int,
    IN usu_id_sp int,
    IN idio_id_sp int,
    IN doc_permiso_sp tinyint,
    IN cat_id_sp int)
BEGIN
	-- Añadir nuevo docente
	if(op=0) then
		insert into docente values(usu_id_sp,idio_id_sp,doc_permiso_sp,cat_id_sp);
	end if;
	
    -- Buscar docente
    if(op=1) then
		SELECT 
			activos_ist17j.usuario.usu_id,
			activos_ist17j.usuario.usu_nombre,
            activos_ist17j.usuario.usu_cedula,
			activos_ist17j.usuario.usu_telefono,
            resumenes_ist17j.idioma.idio_id,
			resumenes_ist17j.idioma.idio_idioma,
			resumenes_ist17j.docente.doc_permiso, 
			resumenes_ist17j.catalogo_estado.cat_estado
		FROM 
			activos_ist17j.usuario
		JOIN 
			resumenes_ist17j.docente 
			ON activos_ist17j.usuario.usu_id = resumenes_ist17j.docente.usu_id
		JOIN 
			resumenes_ist17j.idioma 
			ON resumenes_ist17j.docente.idio_id = resumenes_ist17j.idioma.idio_id
		JOIN
        resumenes_ist17j.catalogo_estado
			ON resumenes_ist17j.docente.cat_id = resumenes_ist17j.catalogo_estado.cat_id
		WHERE 
			activos_ist17j.usuario.usu_id = usu_id_sp;

	end if;
    
    -- Modificar docente
    if(op=2) then
		UPDATE docente SET 
            doc_permiso = doc_permiso_sp,
            cat_id = cat_id_sp
        WHERE usu_id = usu_id_sp;
	end if;
    
    -- Listar docentes ingresados
    if(op=3) then
		SELECT d.usu_id, u.usu_nombre, u.usu_cedula, d.idio_id, d.doc_permiso, d.cat_id
		FROM activos_ist17j.usuario AS u
		JOIN resumenes_ist17j.docente AS d 
			ON u.usu_id = d.usu_id
		WHERE d.usu_id != usu_id_sp and d.idio_id = idio_id_sp;
	end if;
    
    -- Listar docentes NO ingresados
    if(op=4) then
		select u.usu_id,u.usu_nombre,u.usu_cedula,u.usu_telefono
        from activos_ist17j.usuario as u
		left join resumenes_ist17j.docente as d 
        ON u.usu_id = d.usu_id
        WHERE d.usu_id IS NULL AND u.usu_id != usu_id_sp AND u.usu_condicion = '1';
	end if;
END

// DELIMITER ;

-- SP IDIOMA --------------------------------------------------------------------------------------------------------

DELIMITER //
CREATE PROCEDURE sp_idiomas(
	IN op int,
    IN idio_id int,
    IN idio_idioma_sp VARCHAR(50),
    IN idio_dependencia_sp int,
    IN cat_id_sp int)
BEGIN
	if(op=0) then
		insert into idioma(idio_idioma,idio_dependencia,cat_id)
			values(idio_idioma_sp,idio_dependencia_sp,cat_id_sp);
	end if;
    if(op=1) then
		SELECT 
			i1.idio_id,
            i1.idio_dependencia,
			i1.idio_idioma, 
			CASE 
				WHEN i1.idio_dependencia = 0 THEN 'Sin Dependencia'
				ELSE i2.idio_idioma
			END AS idio_dependenciaD, 
			i1.cat_id
		FROM 
			idioma i1
		LEFT JOIN 
			idioma i2
		ON 
			i1.idio_dependencia = i2.idio_id
		WHERE i1.cat_id=1;
    end if;
    if(op=2) then
		UPDATE idioma SET 
            idio_idioma = idio_idioma_sp,
            idio_dependencia = idio_dependencia_sp,
            cat_id = cat_id_sp
        WHERE idio_id = idio_id_sp;
    end if;
END

// DELIMITER ;

-- SP ESTUDIANTES----------------------------------------------------------------------------------------------------

DELIMITER //
CREATE PROCEDURE sp_estudiantes(
	IN op int,
    IN est_id_sp int,
    IN cat_id_sp int
)
BEGIN

	-- Insertar estudiante
	if(op=0) then
		insert into estudiante(est_id,cat_id)
			values(est_id_sp, cat_id_sp);
	end if;
    
    
END
// DELIMITER ;

-- SP RESUMEN -------------------------------------------------------------------------------------------------------

DELIMITER //
CREATE PROCEDURE sp_resumenes(
	IN op int,
    IN res_id_sp int, 
	IN est_id_sp int,
	IN idio_id_sp int,
	IN res_resumen_sp text,
	IN res_palabras_clave_sp varchar(255),
	IN res_fecha_sp date,
	IN cat_id_sp int
)
BEGIN

	if(op=0) then
		insert into resumen(est_id, idio_id, res_resumen, res_palabras_clave, res_fecha, cat_id)
			values(est_id_sp, idio_id_sp, res_resumen_sp, res_palabras_clave_sp, CURDATE(), cat_id_sp);
    end if;
    if(op=1) then
		SELECT
			r.res_id,
            r.res_resumen,
            r.res_palabras_clave,
            r.res_fecha,
            es.est_nombre,
            i.idio_idioma
		FROM 
			resumen r
        INNER JOIN
			estudiantes_ist17j.estudiante es ON r.est_id = es.est_id
		INNER JOIN 
			idioma i ON i.idio_id = r.idio_id
		WHERE 
			r.res_id=res_id_sp;
    end if;
    
END
// DELIMITER ;

-- SP ASIGNACION----------------------------------------------------------------------------------------------------

DELIMITER //
CREATE PROCEDURE sp_asignaciones(
	IN op int,
    IN asig_id_sp int, 
	IN res_id_sp int,
    IN usu_id_sp int,
	IN asig_fecha_sp date,
	IN cat_id_sp int
)
BEGIN

	if(op=0) then
		insert into resumen(est_id, idio_id, res_resumen, res_palabras_clave, res_fecha, cat_id)
			values(est_id_sp, idio_id_sp, res_resumen_sp, res_palabras_clave_sp, CURDATE(), cat_id_sp);
    end if;
    if(op=1) then
		SELECT 
			a.asig_id,
            a.res_id,
			e.est_id,
			es.est_nombre,
			d.usu_id,
			ds.usu_nombre,
            c.cat_estado,
			a.asig_fecha
		FROM 
			asignacion a
		INNER JOIN 
			resumen r ON a.res_id = r.res_id
		INNER JOIN 
			estudiante e ON r.est_id = e.est_id
		INNER JOIN 
			estudiantes_ist17j.estudiante es ON e.est_id = es.est_id
		INNER JOIN 
			docente d ON a.usu_id = d.usu_id
		INNER JOIN 
			activos_ist17j.usuario ds ON d.usu_id = ds.usu_id
		INNER JOIN
			catalogo_estado c ON a.cat_id = c.cat_id
		WHERE 
			a.cat_id = 5
			AND d.usu_id = usu_id_sp;
    end if;
    
END
// DELIMITER ;

-- SP Crear Asignación cuando se crea Resumen Trigger ---------------------------------------------------------------------------

DELIMITER //

CREATE TRIGGER tr_crearAsignacion
AFTER INSERT ON resumen
FOR EACH ROW
BEGIN
    CALL sp_crearAsignacionTrigger(NEW.res_id,NEW.idio_id);
END //

DELIMITER ;

DELIMITER //

CREATE PROCEDURE sp_crearAsignacionTrigger(IN res_id_nuevo INT, IN idio_id_resumen INT)
BEGIN
    DECLARE total_docentes_idioma INT;
    DECLARE indice_docente INT;
    DECLARE usu_id_asignado INT;
    DECLARE offset_value INT;

    SELECT COUNT(*) INTO total_docentes_idioma FROM docente WHERE cat_id = 1 AND idio_id = idio_id_resumen;
    SET indice_docente = (SELECT COUNT(*) FROM asignacion WHERE res_id IN (SELECT res_id FROM resumen WHERE idio_id = idio_id_resumen)) % total_docentes_idioma + 1;

	SET offset_value = IF(indice_docente - 1 > 0, indice_docente - 1, 0);

    SELECT usu_id INTO usu_id_asignado FROM docente
		WHERE cat_id = 1 AND idio_id = idio_id_resumen
		LIMIT 1
		OFFSET offset_value;

    INSERT INTO asignacion (res_id, usu_id, asig_fecha, cat_id)
    VALUES (res_id_nuevo, usu_id_asignado, CURDATE(), 5); 

END //

DELIMITER ;

-- SP revisiones aprobadas por estudiante----------------------------------------

DELIMITER //
CREATE PROCEDURE sp_revisionesAprobEstudiante(
    IN est_id_sp INT
)
BEGIN

	SELECT * FROM revision r
		INNER JOIN asignacion a ON r.asig_id = a.asig_id
		INNER JOIN resumen re ON a.res_id = re.res_id
		WHERE re.est_id = est_id_sp
			AND r.cat_id = 4;
    
END
// DELIMITER ;

