-- ============================================
-- Rahhal travel website - database setup
-- Import this file in phpMyAdmin (Import tab).
-- It creates the database, all tables and demo data.
-- ============================================

CREATE DATABASE IF NOT EXISTS rahhal CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE rahhal;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  is_admin TINYINT NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE flights (
  id INT AUTO_INCREMENT PRIMARY KEY,
  from_city VARCHAR(100) NOT NULL,
  to_city VARCHAR(100) NOT NULL,
  flight_date DATE NOT NULL,
  airline VARCHAR(100) NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  seats INT NOT NULL DEFAULT 0
);

CREATE TABLE hotels (
  id INT AUTO_INCREMENT PRIMARY KEY,
  city VARCHAR(100) NOT NULL,
  name VARCHAR(150) NOT NULL,
  price_per_night DECIMAL(10,2) NOT NULL,
  rating DECIMAL(2,1) NOT NULL DEFAULT 0,
  description TEXT
);

CREATE TABLE homes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  city VARCHAR(100) NOT NULL,
  country VARCHAR(100) NOT NULL,
  host_name VARCHAR(100) NOT NULL,
  title VARCHAR(150) NOT NULL,
  experience_description TEXT,
  price_per_night DECIMAL(10,2) NOT NULL
);

CREATE TABLE restaurants (
  id INT AUTO_INCREMENT PRIMARY KEY,
  city VARCHAR(100) NOT NULL,
  name VARCHAR(150) NOT NULL,
  cuisine VARCHAR(100) NOT NULL,
  avg_price DECIMAL(10,2) NOT NULL,
  description TEXT
);

CREATE TABLE attractions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  city VARCHAR(100) NOT NULL,
  name VARCHAR(150) NOT NULL,
  ticket_price DECIMAL(10,2) NOT NULL,
  description TEXT
);

CREATE TABLE bookings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  item_type VARCHAR(20) NOT NULL,
  item_id INT NOT NULL,
  booking_date DATE NOT NULL,
  quantity INT NOT NULL DEFAULT 1,
  total_price DECIMAL(10,2) NOT NULL,
  confirmation_code VARCHAR(20) NOT NULL UNIQUE,
  status VARCHAR(20) NOT NULL DEFAULT 'confirmed',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- Demo accounts
-- Admin: admin@rahhal.com / admin123
-- User:  demo@rahhal.com  / demo123
-- ============================================
INSERT INTO users (name, email, password, is_admin) VALUES
('Site Admin', 'admin@rahhal.com', '$2y$12$pTK8JbcSBLOd.ToAjkerOO1OYTMvGEvhwPLbU/oB6QIzDOWDq2Kw.', 1),
('Demo User', 'demo@rahhal.com', '$2y$12$VYjC515ilLwvdL/DG3UNseeNgB.LEQNBnqCwL7OCpZow6FZy6wr66', 0);

-- ============================================
-- Demo data
-- ============================================
INSERT INTO flights (from_city, to_city, flight_date, airline, price, seats) VALUES
('Cairo', 'Istanbul', '2026-12-10', 'EgyptAir', 9500, 42),
('Cairo', 'Dubai', '2026-12-12', 'Emirates', 12000, 30),
('Cairo', 'London', '2026-12-15', 'British Airways', 21000, 25),
('Cairo', 'Aswan', '2026-12-05', 'EgyptAir', 3200, 50),
('Cairo', 'Luxor', '2026-12-06', 'Air Cairo', 2900, 45),
('Cairo', 'Marrakech', '2026-12-18', 'Royal Air Maroc', 15500, 20);

INSERT INTO hotels (city, name, price_per_night, rating, description) VALUES
('Istanbul', 'Sultanahmet Palace Hotel', 2500, 4.5, 'Steps away from the Blue Mosque with a rooftop breakfast terrace overlooking the Bosphorus.'),
('Dubai', 'Marina Bay Hotel', 4200, 4.8, 'Modern tower in Dubai Marina, 5 minutes from the beach, with an infinity pool.'),
('London', 'Covent Garden Inn', 5500, 4.3, 'Classic hotel in the theatre district, walking distance to the West End.'),
('Aswan', 'Nile View Hotel', 1800, 4.6, 'Rooms face the Nile and Elephantine Island, with a felucca dock right outside.'),
('Luxor', 'Pharaohs Hotel', 1500, 4.2, 'Family-run hotel near Luxor Temple with a shaded courtyard.'),
('Marrakech', 'Medina Garden Hotel', 2200, 4.4, 'Quiet garden hotel inside the old medina, close to Jemaa el-Fnaa.');

INSERT INTO homes (city, country, host_name, title, experience_description, price_per_night) VALUES
('Aswan', 'Egypt', 'Ahmed Hamza', 'Nubian Family House', 'Live with a Nubian family: home-cooked meals, a henna night, Nubian music after dinner, and a boat trip to Gharb Soheil village.', 900),
('Siwa', 'Egypt', 'Fatma Soliman', 'Siwan Eco Home', 'A traditional karshif mud-brick home. Bake bread in a clay oven, watch the sunset over the salt lakes, and join a date-farm dinner.', 700),
('Marrakech', 'Morocco', 'Youssef El Amrani', 'Riad with a Moroccan Family', 'Share mint tea on the rooftop, learn to cook tagine with the family, visit the hammam, and walk the souk with your host.', 1100),
('Istanbul', 'Turkey', 'Aylin Demir', 'Ottoman House in Balat', 'A wooden Ottoman house in the old Balat quarter. Turkish breakfast every morning, cay evenings, and a beginner calligraphy workshop.', 1300),
('Luxor', 'Egypt', 'Mahmoud Ali', 'West Bank Farmhouse', 'Farm life on the West Bank: felucca ride at sunset, fresh bread from the clay oven, and sugarcane juice from the field.', 800),
('London', 'United Kingdom', 'Sarah Collins', 'Cozy Townhouse Host', 'Stay with a local family in Notting Hill: Sunday roast dinner, a pub quiz night, and a walk through Portobello Market.', 2600);

INSERT INTO restaurants (city, name, cuisine, avg_price, description) VALUES
('Istanbul', 'Hunkar Lokantasi', 'Turkish', 350, 'Classic Istanbul esnaf lokantasi serving slow-cooked stews and baklava since 1950.'),
('Dubai', 'Al Fanar', 'Emirati', 500, 'Traditional Emirati dishes like machboos and luqaimat in a 1960s Dubai setting.'),
('London', 'The Churchill Arms', 'British', 600, 'Historic pub famous for its flower-covered facade and hearty Sunday roasts.'),
('Aswan', 'El Dokka', 'Nubian', 250, 'Nubian restaurant on an island, known for its tagines and Nile views.'),
('Luxor', 'Sofra', 'Egyptian', 300, 'Traditional Egyptian home cooking in a restored 1930s house.'),
('Marrakech', 'Jemaa el-Fnaa Stalls', 'Moroccan', 200, 'The famous night food market: grilled meats, harira soup, and fresh orange juice.');

INSERT INTO attractions (city, name, ticket_price, description) VALUES
('Istanbul', 'Hagia Sophia', 800, 'The legendary Byzantine cathedral and mosque, 1,500 years of history in one building.'),
('Dubai', 'Burj Khalifa - At The Top', 2200, 'Observation deck on the 124th floor of the tallest building in the world.'),
('London', 'British Museum', 0, 'Free entry to one of the greatest museums on Earth, including the Rosetta Stone.'),
('Aswan', 'Philae Temple', 300, 'The island temple of Isis, reached by a short boat ride across the Nile.'),
('Luxor', 'Karnak Temple', 350, 'The largest religious complex of the ancient world, with its forest of giant columns.'),
('Marrakech', 'Jardin Majorelle', 500, 'The famous blue villa and botanical garden restored by Yves Saint Laurent.');
