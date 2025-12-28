-- Workshop Job table
-- Copyright (C) 2025 Workshop Management Development Team

CREATE TABLE llx_workshop_job (
    rowid                INTEGER AUTO_INCREMENT PRIMARY KEY,
    ref                  VARCHAR(30) NOT NULL,
    fk_workshop          INTEGER NOT NULL,
    fk_soc               INTEGER,
    fk_project           INTEGER,
    fk_user_assigned     INTEGER,
    date_start           DATETIME,
    date_end             DATETIME,
    estimated_hours      DOUBLE(24,8),
    status               INTEGER DEFAULT 0 NOT NULL,
    description          TEXT,
    note_public          TEXT,
    note_private         TEXT,
    total_ht             DOUBLE(24,8) DEFAULT 0,
    total_tva            DOUBLE(24,8) DEFAULT 0,
    total_ttc            DOUBLE(24,8) DEFAULT 0,
    date_creation        DATETIME,
    tms                  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    fk_user_author       INTEGER,
    fk_user_modif        INTEGER,
    import_key           VARCHAR(14),

    UNIQUE KEY uk_workshop_job_ref (ref),
    KEY idx_workshop_job_fk_workshop (fk_workshop),
    KEY idx_workshop_job_fk_soc (fk_soc),
    KEY idx_workshop_job_status (status),
    KEY idx_workshop_job_assigned (fk_user_assigned)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
