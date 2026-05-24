<?php

class Auth {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // 🔑 Login
    public function login($email, $senha) {

        if (empty($email) || empty($senha)) {
            return "Preencha todos os campos.";
        }

        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return "Usuário não encontrado.";
        }

        // Verifica bloqueio por tentativas excessivas
        if ($user['lock_until'] && strtotime($user['lock_until']) > time()) {
            return "Conta bloqueada temporariamente.";
        }

        // ✅ CORREÇÃO: Usar password_verify para comparar com o hash do banco
        if (password_verify($senha, $user['senha'])) {
            
            $this->resetAttempts($user['id']);

            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            // Padronizado para 'user_id' conforme seu método check()
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['usuario_id'] = $user['id']; // Criado para compatibilidade com outras telas

            return true;

        } else {
            $this->registerFailedAttempt($user);
            return "Email ou senha inválidos.";
        }
    }

    // ❌ Tentativa falha
    private function registerFailedAttempt($user) {
        $tentativas = $user['failed_attempts'] + 1;

        if ($tentativas >= 3) {
            $bloqueio = date('Y-m-d H:i:s', strtotime('+10 minutes'));
            $stmt = $this->pdo->prepare("UPDATE usuarios SET failed_attempts = ?, lock_until = ? WHERE id = ?");
            $stmt->execute([$tentativas, $bloqueio, $user['id']]);
        } else {
            $stmt = $this->pdo->prepare("UPDATE usuarios SET failed_attempts = ? WHERE id = ?");
            $stmt->execute([$tentativas, $user['id']]);
        }
    }

    // 🔄 Reset tentativas
    private function resetAttempts($id) {
        $stmt = $this->pdo->prepare("UPDATE usuarios SET failed_attempts = 0, lock_until = NULL WHERE id = ?");
        $stmt->execute([$id]);
    }

    // 🔐 Verifica login
    public static function check() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
             header("Location: /eestoque/public/login.php");
            exit;
        }
    }

    // 🚪 Logout
    public static function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();
        header("Location: /eestoque/public/login.php");
        exit;
    }
}