-- Workshop table
-- Copyright (C) 2025 Workshop Management Development Team
--
-- This program is free software; you can redistribute it and/or modify
-- it under the terms of the GNU General Public License as published by
-- the Free Software Foundation; either version 3 of the License, or
-- (at your option) any later version.
--
-- This program is distributed in the hope that it will be useful,
-- but WITHOUT ANY WARRANTY; without even the implied warranty of
-- MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
-- GNU General Public License for more details.
--
-- You should have received a copy of the GNU General Public License
-- along with this program. If not, see <https://www.gnu.org/licenses/>.

CREATE TABLE llx_workshop (
    rowid           INTEGER AUTO_INCREMENT PRIMARY KEY,
    ref             VARCHAR(30) NOT NULL,
    entity          INTEGER DEFAULT 1 NOT NULL,
    label           VARCHAR(255),
    description     TEXT,
    status          INTEGER DEFAULT 1 NOT NULL,
    date_creation   DATETIME,
    tms             TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    fk_user_author  INTEGER,
    fk_user_modif   INTEGER,
    note_public     TEXT,
    note_private    TEXT,
    import_key      VARCHAR(14),

    UNIQUE KEY uk_workshop_ref (ref, entity),
    KEY idx_workshop_entity (entity),
    KEY idx_workshop_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
