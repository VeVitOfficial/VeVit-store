-- VeVit Store — Database Schema (PostgreSQL)

-- Kategorie
CREATE TABLE IF NOT EXISTS store_categories (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    icon VARCHAR(50) NULL,
    sort_order INT NOT NULL DEFAULT 0
);

-- Produkty
CREATE TABLE IF NOT EXISTS store_products (
    id SERIAL PRIMARY KEY,
    category_id INT NULL,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    description TEXT,
    short_desc VARCHAR(255),
    price DECIMAL(10,2) NOT NULL,
    sale_price DECIMAL(10,2) NULL,
    type VARCHAR(20) NOT NULL DEFAULT 'physical' CHECK (type IN ('physical','digital')),
    stock INT NULL,
    images TEXT,
    download_file VARCHAR(500) NULL,
    stripe_price_id VARCHAR(100) NULL,
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    featured BOOLEAN NOT NULL DEFAULT FALSE,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES store_categories(id) ON DELETE SET NULL
);

-- Objednávky
CREATE TABLE IF NOT EXISTS store_orders (
    id SERIAL PRIMARY KEY,
    order_number VARCHAR(20) NOT NULL UNIQUE,
    user_id TEXT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'pending' CHECK (status IN ('pending','paid','processing','shipped','delivered','cancelled','refunded')),
    total DECIMAL(10,2) NOT NULL,
    currency VARCHAR(3) NOT NULL DEFAULT 'czk',
    stripe_session_id VARCHAR(255) NULL,
    stripe_payment_intent VARCHAR(255) NULL,
    customer_email VARCHAR(255) NOT NULL,
    customer_name VARCHAR(255) NOT NULL,
    shipping_address TEXT,
    notes TEXT,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Trigger pro updated_at (ekvivalent ON UPDATE CURRENT_TIMESTAMP)
CREATE OR REPLACE FUNCTION update_updated_at_column()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$ language 'plpgsql';

DROP TRIGGER IF EXISTS update_store_orders_updated_at ON store_orders;
CREATE TRIGGER update_store_orders_updated_at
    BEFORE UPDATE ON store_orders
    FOR EACH ROW
    EXECUTE FUNCTION update_updated_at_column();

-- Položky objednávky
CREATE TABLE IF NOT EXISTS store_order_items (
    id SERIAL PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    product_name VARCHAR(255) NOT NULL,
    product_type VARCHAR(20) NOT NULL CHECK (product_type IN ('physical','digital')),
    quantity INT NOT NULL DEFAULT 1,
    unit_price DECIMAL(10,2) NOT NULL,
    download_token VARCHAR(64) NULL,
    download_expires_at TIMESTAMP NULL,
    download_count INT NOT NULL DEFAULT 0,
    FOREIGN KEY (order_id) REFERENCES store_orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES store_products(id) ON DELETE RESTRICT
);

-- Demo Data
INSERT INTO store_categories (name, slug, icon, sort_order) VALUES
('Merch', 'merch', 'shirt', 1),
('Digitální produkty', 'digitalni-produkty', 'download', 2),
('Elektronika', 'elektronika', 'cpu', 3)
ON CONFLICT (slug) DO NOTHING;

INSERT INTO store_products (category_id, name, slug, description, short_desc, price, sale_price, type, stock, images, download_file, stripe_price_id, is_active, featured, created_at) VALUES
(1, 'VeVit Tričko', 'vevit-tricko', 'Originální VeVit tričko z prémiové bavlny. Černá barva s fialovým potiskem loga.', 'Prémiové tričko s fialovým logem', 499.00, NULL, 'physical', 50, '["tricko.jpg"]', NULL, NULL, TRUE, TRUE, NOW()),
(1, 'VeVit Hrnek', 'vevit-hrnek', 'Keramický hrnek o objemu 330 ml. Odolný potisk vhodný do myčky.', 'Keramický hrnek 330 ml', 349.00, 299.00, 'physical', 30, '["hrnek.jpg"]', NULL, NULL, TRUE, FALSE, NOW()),
(1, 'VeVit Hoodie', 'vevit-hoodie', 'Pohodlná mikina s kapucí. Teplý fleece uvnitř, minimalistický design.', 'Mikina s kapucí a fleecem', 1299.00, NULL, 'physical', 20, '["hoodie.jpg"]', NULL, NULL, TRUE, TRUE, NOW()),
(2, 'VeVit UI Kit', 'vevit-ui-kit', 'Kompletní UI kit pro Figma. 200+ komponentů, ikony, typografie a barevné palety.', 'UI kit pro Figma — 200+ komponentů', 1499.00, 999.00, 'digital', NULL, '["uikit.jpg"]', 'downloads/ui-kit.zip', NULL, TRUE, TRUE, NOW()),
(2, 'VeVit Ikonky', 'vevit-ikonky', 'Sada 500+ vektorových ikon v SVG a PNG. Různé styly a velikosti.', '500+ vektorových ikon SVG/PNG', 599.00, NULL, 'digital', NULL, '["ikonky.jpg"]', 'downloads/ikonky.zip', NULL, TRUE, FALSE, NOW()),
(3, 'VeVit Mini PC', 'vevit-mini-pc', 'Kompaktní Mini PC pro vývojáře. 16GB RAM, 512GB SSD, tichý provoz.', 'Mini PC 16GB/512GB pro vývojáře', 8990.00, NULL, 'physical', 5, '["minipc.jpg"]', NULL, NULL, TRUE, FALSE, NOW())
ON CONFLICT (slug) DO NOTHING;
