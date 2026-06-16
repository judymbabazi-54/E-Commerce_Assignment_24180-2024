CREATE TABLE IF NOT EXISTS settings (
    setting_key VARCHAR(50) PRIMARY KEY,
    setting_value TEXT
);
INSERT IGNORE INTO settings (setting_key, setting_value) VALUES 
('hero_image', 'hero_banner.jpg'),
('hero_title', 'Elevate your everyday style'),
('hero_subtitle', 'Discover the latest trends in urban fashion. Premium quality clothing designed for comfort and style.');
