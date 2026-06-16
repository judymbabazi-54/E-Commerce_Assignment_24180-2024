CREATE TABLE IF NOT EXISTS settings (
    setting_key VARCHAR(50) PRIMARY KEY,
    setting_value TEXT
);
INSERT IGNORE INTO settings (setting_key, setting_value) VALUES 
('hero_image', 'https://images.unsplash.com/photo-1483985988355-763728e1935b?auto=format&fit=crop&w=1200&q=80'),
('hero_title', 'Elevate your everyday style'),
('hero_subtitle', 'Discover the latest trends in urban fashion. Premium quality clothing designed for comfort and style.');
