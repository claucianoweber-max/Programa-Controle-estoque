<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
define('BASE_URL', '/xxx--sua_base--xxx/public/');