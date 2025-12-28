-- Workshop Item table (parts/products used in jobs)
-- Copyright (C) 2025 Workshop Management Development Team

CREATE TABLE llx_workshop_item (
    rowid              INTEGER AUTO_INCREMENT PRIMARY KEY,
    fk_workshop_job    INTEGER NOT NULL,
    fk_product         INTEGER,
    description        TEXT,
    qty                DOUBLE DEFAULT 1,
    price_unit         DOUBLE(24,8),
    total_ht           DOUBLE(24,8),
    tva_tx             DOUBLE(7,4),
    total_tva          DOUBLE(24,8),
    total_ttc          DOUBLE(24,8),
    date_creation      DATETIME,
    tms                TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    KEY idx_workshop_item_job (fk_workshop_job),
    KEY idx_workshop_item_product (fk_product)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
