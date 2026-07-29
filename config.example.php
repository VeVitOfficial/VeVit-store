<?php

// Copy this file as config_secret.php beside config.php. The included .htaccess
// blocks direct HTTP access; never commit the copied file or expose it in backups.
// Production requires HTTPS and SESSION_COOKIE_SECURE=true.

define('APP_ENV', 'development'); // development | staging | production | test
define('APP_URL', 'http://localhost:8000');
define('APP_STORAGE_PATH', '/absolute/path/outside/public_html/vevit-store');

// Prefer DB_DSN. The legacy DB_HOST/DB_PORT/DB_NAME form remains supported.
define('DB_DSN', 'pgsql:host=127.0.0.1;port=5432;dbname=vevit_store');
define('DB_USER', 'replace-me');
define('DB_PASS', 'replace-me');

define('SESSION_NAME', 'vevit_store_session');
define('SESSION_COOKIE_SECURE', false);
define('SESSION_COOKIE_SAMESITE', 'Lax');
define('STORE_ALLOWED_ORIGINS', '');

// Required for the existing administration login. Generate with password_hash()
// and never commit the resulting production hash.
define('ADMIN_PASSWORD_HASH', '');

// Required only by the payment endpoints. They fail closed when missing.
define('STRIPE_SECRET_KEY', '');
define('STRIPE_WEBHOOK_SECRET', '');
define('STRIPE_ACCOUNT_ID', ''); // Optional acct_... guard for Stripe Connect/platform setups.
