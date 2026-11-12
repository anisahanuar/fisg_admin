USE fisg_website;

-- Run once after database.sql has been imported.
-- If the role column already exists, skip the ALTER TABLE statement.
ALTER TABLE users
  ADD COLUMN role ENUM('user','admin') NOT NULL DEFAULT 'user' AFTER password_hash;

-- First register your chosen admin account via register.php.
-- Then run this separately after replacing the email with your exact registered email:
-- UPDATE users SET role = 'admin' WHERE email = 'your-admin-email@example.com';
