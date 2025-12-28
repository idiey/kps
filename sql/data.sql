-- Initial data for Workshop Management module
-- Copyright (C) 2025 Workshop Management Development Team

-- Insert default status values
INSERT INTO llx_c_workshop_status (code, label, active) VALUES
('DRAFT', 'Draft', 1),
('OPEN', 'Open', 1),
('IN_PROGRESS', 'In Progress', 1),
('COMPLETED', 'Completed', 1),
('INVOICED', 'Invoiced', 1),
('CANCELLED', 'Cancelled', 1);
