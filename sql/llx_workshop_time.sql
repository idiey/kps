-- Workshop Time tracking table
-- Copyright (C) 2025 Workshop Management Development Team

CREATE TABLE llx_workshop_time (
    rowid              INTEGER AUTO_INCREMENT PRIMARY KEY,
    fk_workshop_job    INTEGER NOT NULL,
    fk_user            INTEGER NOT NULL,
    date_time          DATETIME NOT NULL,
    duration           INTEGER NOT NULL COMMENT 'Duration in seconds',
    description        TEXT,
    hourly_rate        DOUBLE(24,8),
    total_ht           DOUBLE(24,8),
    date_creation      DATETIME,
    tms                TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    KEY idx_workshop_time_job (fk_workshop_job),
    KEY idx_workshop_time_user (fk_user),
    KEY idx_workshop_time_date (date_time)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
