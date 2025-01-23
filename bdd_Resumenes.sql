-- Nombre BDD: resumenes_ist17j
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
    FOREIGN KEY (idio_id) REFERENCES idioma(idio_id),
    FOREIGN KEY (usu_id) REFERENCES activos_ist17j.usuario(usu_id)
);

CREATE TABLE resumen (
    res_id INT AUTO_INCREMENT PRIMARY KEY,
    est_id int,
    idio_id int,
    res_resumen TEXT,
    res_palabras_clave VARCHAR(255),
    res_fecha DATETIME,
    cat_id int,
    FOREIGN KEY (idio_id) REFERENCES idioma(idio_id),
    FOREIGN KEY (cat_id) REFERENCES catalogo_estado(cat_id),
    FOREIGN KEY (est_id) REFERENCES estudiante(est_id)
);

CREATE TABLE asignacion (
    asig_id int AUTO_INCREMENT primary key,
    res_id int,
    usu_id int,
    asig_fecha DATETIME,
    cat_id int,
    FOREIGN KEY (cat_id) REFERENCES catalogo_estado(cat_id),
    FOREIGN KEY (res_id) REFERENCES resumen(res_id),
    FOREIGN KEY (usu_id) REFERENCES docente(usu_id)    
);

CREATE TABLE revision (
    rev_id INT AUTO_INCREMENT PRIMARY KEY,
    asig_id INT,
    rev_observaciones text,
    rev_fecha DATETIME,
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
    IN doc_permiso_sp tinyint)
BEGIN
	-- Añadir nuevo docente
	if(op=0) then
		insert into docente values(usu_id_sp,idio_id_sp,doc_permiso_sp);
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
			resumenes_ist17j.docente.doc_permiso
		FROM 
			activos_ist17j.usuario
		JOIN 
			resumenes_ist17j.docente 
			ON activos_ist17j.usuario.usu_id = resumenes_ist17j.docente.usu_id
		JOIN 
			resumenes_ist17j.idioma 
			ON resumenes_ist17j.docente.idio_id = resumenes_ist17j.idioma.idio_id
		WHERE 
			activos_ist17j.usuario.usu_id = usu_id_sp;

	end if;
    
    -- Modificar docente
    if(op=2) then
		UPDATE docente SET 
            doc_permiso = doc_permiso_sp
        WHERE usu_id = usu_id_sp;
	end if;
    
    -- Listar docentes ingresados
    if(op=3) then
		SELECT d.usu_id, u.usu_nombre, u.usu_cedula, d.idio_id, d.doc_permiso
		FROM activos_ist17j.usuario AS u
		JOIN resumenes_ist17j.docente AS d 
			ON u.usu_id = d.usu_id
		WHERE d.usu_id != usu_id_sp and d.idio_id = idio_id_sp AND u.usu_condicion = '1';
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
	-- Insertar idioma
	if(op=0) then
		insert into idioma(idio_idioma,idio_dependencia,cat_id)
			values(idio_idioma_sp,idio_dependencia_sp,cat_id_sp);
	end if;
    
    -- Muestra todos los idiomas
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
    
    -- Modifica los datos de un idioma
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
	IN cat_id_sp int
)
BEGIN
	-- Insertar resumen
	if(op=0) then
		insert into resumen(est_id, idio_id, res_resumen, res_palabras_clave, res_fecha, cat_id)
			values(est_id_sp, idio_id_sp, res_resumen_sp, res_palabras_clave_sp, NOW(), cat_id_sp);
    end if;
    
    -- Muestra un resumen segun el estudiante y el idioma
    if(op=1) then
		SELECT
			r.res_id,
            r.res_resumen,
            r.res_palabras_clave,
            r.res_fecha,
            a.asig_id,
            es.est_nombre,
            i.idio_idioma
		FROM 
			resumen r
        INNER JOIN
			estudiantes_ist17j.estudiante es ON r.est_id = es.est_id
		INNER JOIN 
			idioma i ON i.idio_id = r.idio_id
		INNER JOIN 
			asignacion a ON r.res_id = r.res_id
		WHERE 
			r.res_id=res_id_sp;
    end if;
    
    -- Muestra si un estudiante tiene ASIGNACIONES en proceso segun el idioma
    if(op=2) then
		SELECT a.asig_id
        FROM asignacion a
        JOIN
			resumen r ON a.res_id = r.res_id
        WHERE
			r.est_id=est_id_sp AND r.idio_id=idio_id_sp AND (a.cat_id=5 OR a.cat_id=4);
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
    IN est_id_sp int,
	IN cat_id_sp int
)
BEGIN

	if(op=0) then
		insert into asignacion(res_id, usu_id, asig_fecha, cat_id)
			values(res_id_sp, usu_id_sp, NOW(), cat_id_sp);
    end if;
    
    -- Muestra todas las asignaciones En proceso (cat_id=5)
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
    
    -- Cambiar estado de una asignación
    if(op=2) then
		UPDATE asignacion SET 
            cat_id = cat_id_sp
        WHERE asig_id = asig_id_sp;
    end if;
    
    -- Muestra todas las asignaciones de un estudiante
    if(op=3) then
		SELECT a.asig_id,
			a.asig_fecha,
			ac.usu_nombre,
			i.idio_idioma,
			c.cat_estado
		FROM asignacion a
		LEFT JOIN resumen r ON r.res_id = a.res_id
		LEFT JOIN activos_ist17j.usuario ac ON a.usu_id = ac.usu_id
		LEFT JOIN catalogo_estado c ON c.cat_id = a.cat_id
		LEFT JOIN idioma i ON i.idio_id = r.idio_id
		WHERE 
			r.est_id=est_id_sp
		ORDER BY 
			a.asig_fecha DESC;
    end if;
    
    -- Muestra los datos de una asignacion junto con los datos de su revisión
    if(op=4) then
		SELECT rv.rev_observaciones,
			rv.rev_fecha,
			a.asig_fecha,
			r.res_resumen,
			r.res_palabras_clave,
			i.idio_idioma,
			ac.usu_nombre,
			e.est_nombre,
			rv.cat_id,
			c.cat_estado
		FROM asignacion a
		LEFT JOIN revision rv ON a.asig_id = rv.asig_id
		LEFT JOIN resumen r ON r.res_id = a.res_id
		LEFT JOIN activos_ist17j.usuario ac ON a.usu_id = ac.usu_id
		LEFT JOIN estudiantes_ist17j.estudiante e ON e.est_id = r.est_id
		LEFT JOIN catalogo_estado c ON c.cat_id = rv.cat_id
		LEFT JOIN idioma i ON i.idio_id = r.idio_id
		WHERE 
			a.asig_id=asig_id_sp;
    end if;
END
// DELIMITER ;

-- SP REVISION ------------------------------------------------------------------------------------------------------

DELIMITER //
CREATE PROCEDURE sp_revisiones(
	IN op int,
    IN est_id_sp int,
    IN usu_id_sp int,
    IN idio_id_sp int,
    IN rev_id_sp int,
    IN asig_id_sp int,
    IN rev_observaciones_sp text,
    IN rev_archivo_sp varchar(255),
    IN cat_id_sp int
)
BEGIN

	-- Insertar revision
	if(op=0) then
		insert into revision(asig_id, rev_observaciones, rev_fecha,cat_id)
			values(asig_id_sp, rev_observaciones_sp, NOW(), cat_id_sp);
		call resumenes_ist17j.sp_asignaciones(2, asig_id_sp, 0, 0, 0, 6);
	end if;
    -- Mostrar revision aprobada por idioma y estudiante
    if(op=1) then
		SELECT rev_id FROM revision rv
        LEFT JOIN asignacion a ON a.asig_id = rv.asig_id
		LEFT JOIN resumen r ON r.res_id = a.res_id
        WHERE rv.cat_id=4 AND r.idio_id=idio_id_sp AND r.est_id=est_id_sp
        LIMIT 1;
    end if;
    if(op=2) then 
		SELECT rev_id, rev_archivo FROM revision rv
        LEFT JOIN asignacion a ON a.asig_id = rv.asig_id
        LEFT JOIN resumen r ON r.res_id = a.res_id
        WHERE r.est_id=est_id_sp;
    end if;
END
// DELIMITER ;

-- SP Documentacion -------------------------------------------------------------------------------------------------

DELIMITER //
CREATE PROCEDURE sp_documentacion(
	IN op int,
    IN est_id_sp int,
    IN idio_id_sp int,
    IN rev_id_sp int,
    IN rev_archivo_sp varchar(255)
)
BEGIN
	DECLARE rev_id_asignado INT;
	-- Guardar nombre del archivo(segun su idioma y estudiante)
	if(op=0) then
		SELECT rev_id INTO rev_id_asignado
        FROM revision rv
        LEFT JOIN asignacion a ON a.asig_id = rv.asig_id
        LEFT JOIN resumen r ON r.res_id = a.res_id
        WHERE r.est_id=est_id_sp AND r.idio_id=idio_id_sp AND (rv.cat_id=4 OR rv.cat_id=8);
        
        UPDATE revision SET 
            rev_archivo = rev_archivo_sp
        WHERE rev_id=rev_id_asignado;
	end if;
-- Eliminar archivo(Borra el nombre de la base)
    if(op=1) then
		SELECT rev_id INTO rev_id_asignado
        FROM revision rv
        LEFT JOIN asignacion a ON a.asig_id = rv.asig_id
        LEFT JOIN resumen r ON r.res_id = a.res_id
        WHERE r.est_id=est_id_sp AND rv.rev_archivo=rev_archivo_sp;
        
        UPDATE revision SET 
            rev_archivo = NULL
        WHERE rev_id=rev_id_asignado;
	end if;
-- Listar documentos enviados del estudiante
    if(op=2) then
		SELECT rev_id, rev_archivo FROM revision rv
        LEFT JOIN asignacion a ON a.asig_id = rv.asig_id
        LEFT JOIN resumen r ON r.res_id = a.res_id
        WHERE r.est_id=est_id_sp AND rv.rev_archivo IS NOT NULL AND (rv.cat_id=4 OR rv.cat_id=8);
    end if;
END
// DELIMITER ;

-- SP Crear Asignación cuando se crea un Resumen -> Trigger ---------------------------------------------------------------------------

DELIMITER //

CREATE TRIGGER tr_crearAsignacion
AFTER INSERT ON resumen
FOR EACH ROW
BEGIN
	if exists(SELECT 
			a.asig_id,
			r.res_id,
			a.usu_id
		FROM asignacion a
		LEFT JOIN resumen r ON r.res_id = a.res_id
		WHERE r.est_id=1
	) then
        CALL sp_crearAsigRepeticion(NEW.est_id,NEW.res_id,NEW.idio_id);
	else 
		CALL sp_crearAsigNoRepeticion(NEW.res_id,NEW.idio_id);
	end if;
END //

DELIMITER ;

DELIMITER //

CREATE PROCEDURE sp_crearAsigRepeticion(IN est_id_resumen INT, IN res_id_nuevo INT, IN idio_id_resumen INT)
BEGIN
	DECLARE usu_id_asignado INT;
    
    SELECT a.usu_id INTO usu_id_asignado
	FROM asignacion a
	LEFT JOIN resumen r ON r.res_id = a.res_id
	WHERE 
		r.est_id = 1
	ORDER BY 
		a.asig_fecha DESC
	LIMIT 1;
    
    insert into asignacion(res_id, usu_id, asig_fecha, cat_id)
			values(res_id_nuevo, usu_id_asignado, NOW(), 5);
    
END //

DELIMITER ;

DELIMITER //

CREATE PROCEDURE sp_crearAsigNoRepeticion(IN res_id_nuevo INT, IN idio_id_resumen INT)
BEGIN
    DECLARE total_docentes_idioma INT;
    DECLARE indice_docente INT;
    DECLARE usu_id_asignado INT;
    DECLARE offset_value INT;

    SELECT COUNT(*) INTO total_docentes_idioma FROM docente 
    INNER JOIN activos_ist17j.usuario ds ON docente.usu_id = ds.usu_id 
    WHERE ds.usu_condicion = 1 AND idio_id = idio_id_resumen;
    SET indice_docente = (SELECT COUNT(*) FROM asignacion WHERE res_id IN (SELECT res_id FROM resumen WHERE idio_id = idio_id_resumen)) % total_docentes_idioma + 1;

	SET offset_value = IF(indice_docente - 1 > 0, indice_docente - 1, 0);

    SELECT docente.usu_id INTO usu_id_asignado FROM docente
    INNER JOIN activos_ist17j.usuario ds ON docente.usu_id = ds.usu_id 
		WHERE ds.usu_condicion = 1 AND idio_id = idio_id_resumen
		LIMIT 1
		OFFSET offset_value;
	
    insert into asignacion(res_id, usu_id, asig_fecha, cat_id)
			values(res_id_nuevo, usu_id_asignado, NOW(), 5);

END //

DELIMITER ;
