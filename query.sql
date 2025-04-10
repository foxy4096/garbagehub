USE garbagehub;

-- USERS TABLE (For authentication & tracking bad decisions)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL, -- Storing hashes, not plaintext (we have some dignity)
    avatar VARCHAR(255) DEFAULT NULL, -- Path to user’s cursed avatar
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- REPOSITORIES TABLE (Because we pretend to organize code)
CREATE TABLE repositories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    repo_name VARCHAR(255) NOT NULL,
    description TEXT,
    is_public BOOLEAN DEFAULT TRUE, -- Private repos? Nah, but okay, fine.
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- COMMITS TABLE (Tracking questionable code changes)
CREATE TABLE commits (
    id INT AUTO_INCREMENT PRIMARY KEY,
    repo_id INT NOT NULL,
    user_id INT NOT NULL,
    commit_hash VARCHAR(64) NOT NULL, -- SHA256 because why not
    message TEXT NOT NULL, -- Nobody reads these
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (repo_id) REFERENCES repositories(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- FILES TABLE (Mapping files to commits)
CREATE TABLE files (
    id INT AUTO_INCREMENT PRIMARY KEY,
    commit_id INT NOT NULL,
    file_name VARCHAR(255) NOT NULL,
    file_path VARCHAR(255) NOT NULL, -- Actual file storage is in /garbage_data/
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (commit_id) REFERENCES commits(id) ON DELETE CASCADE
);

-- TOKENS TABLE (API tokens for our garbage VCS CLI)
CREATE TABLE tokens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    token VARCHAR(64) UNIQUE NOT NULL, -- Once generated, cannot be revoked 💀
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
