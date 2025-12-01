Create database healthcare_db;

use healthcare_db;

CREATE TABLE users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE health_checkins (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    water INT(11),
    sleep INT(11),
    exercise TINYINT(1),
    mood INT(11),
    checkin_date DATE NOT NULL,
    FOREIGN KEY (user_id) REFEREN   CES users(id) ON DELETE CASCADE
);

CREATE TABLE faskes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    tipe ENUM('Rumah Sakit', 'Klinik', 'Puskesmas', 'Apotek') NOT NULL,
    alamat TEXT NOT NULL,
    kecamatan VARCHAR(50) NOT NULL,
    kontak VARCHAR(20)
);

CREATE TABLE faskes_ratings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    faskes_id INT NOT NULL,
    rating INT NOT NULL, 
    review TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (faskes_id) REFERENCES faskes(id) ON DELETE CASCADE
);