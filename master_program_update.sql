-- Update for Master Program and Rumpun Program
ALTER TABLE program_groups ADD COLUMN IF NOT EXISTS status ENUM('y', 'n') DEFAULT 'y';
ALTER TABLE campaigns ADD COLUMN IF NOT EXISTS is_active ENUM('y', 'n') DEFAULT 'y';

-- Ensure foreign key exists if not already there
-- (Assuming campaigns already has group_id from previous updates or initial schema)
-- ALTER TABLE campaigns ADD COLUMN IF NOT EXISTS group_id INT NULL;
-- ALTER TABLE campaigns ADD CONSTRAINT fk_campaign_group FOREIGN KEY (group_id) REFERENCES program_groups(id) ON DELETE SET NULL;
