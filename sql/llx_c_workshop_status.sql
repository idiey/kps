-- Workshop Status dictionary table
-- Copyright (C) 2025 Workshop Management Development Team

CREATE TABLE llx_c_workshop_status (
    rowid    INTEGER AUTO_INCREMENT PRIMARY KEY,
    code     VARCHAR(16) NOT NULL,
    label    VARCHAR(255),
    active   TINYINT DEFAULT 1 NOT NULL,
    UNIQUE KEY uk_c_workshop_status_code (code)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
