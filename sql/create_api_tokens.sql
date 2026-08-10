-- ─────────────────────────────────────────────────────────────────────────
-- SGOD Mobile API — Phase 0 migration
-- Adds the api_tokens table used by the Api_auth library for bearer-token
-- authentication. Additive only — no existing tables are modified.
-- ─────────────────────────────────────────────────────────────────────────

CREATE TABLE IF NOT EXISTS api_tokens (
  id            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_key      VARCHAR(100) NOT NULL,            -- 'users:<id>' or 'sgod:<username>'
  position      VARCHAR(45)  NOT NULL,            -- normalized lowercase role
  token_hash    CHAR(40)     NOT NULL,            -- sha1 of the bearer token
  device_id     VARCHAR(120) NULL DEFAULT NULL,
  created_at    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  last_seen_at  DATETIME     NULL DEFAULT NULL,
  expires_at    DATETIME     NULL DEFAULT NULL,
  PRIMARY KEY (id),
  KEY api_tokens_user (user_key),
  KEY api_tokens_hash (token_hash)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Optional: clean up tokens older than 90 days on a schedule.
-- DELETE FROM api_tokens WHERE last_seen_at IS NOT NULL
--   AND last_seen_at < DATE_SUB(NOW(), INTERVAL 90 DAY);
