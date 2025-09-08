<?php

if (!function_exists('defaultFormatBodyEmail')) {
    function defaultFormatBodyEmail($param = null)
    {
        if (!$param) return;

        switch ($param) {
            case 'verify_email_mail':
                $format = format_verify_email_mail();
                break;
            case 'forgot_password_mail':
                $format = format_forgot_password_mail();
                break;
            default:
                $format = '';
                break;
        }


        return $format;
    }
}

if (!function_exists('format_verify_email_mail')) {
    function format_verify_email_mail()
    {
        $format = '
            <p>Halo <b>{name}</b>,</p>
            <p>Kami hanya perlu memastikan bahwa alamat email ini benar-benar milik Anda. 
            Silakan klik tombol di bawah untuk menyelesaikan verifikasi.</p>

            <p>
                <a href="{link_btn_action}" style="display:inline-block;padding:10px 16px;
                background:#2563eb;color:#fff;text-decoration:none;
                border-radius:6px;font-weight:600;">
                Verifikasi Email
                </a>
            </p>

            <p>Link ini berlaku selama {expired} menit. Jika Anda tidak merasa meminta verifikasi, abaikan saja email ini.</p>
        ';

        return $format;
    }
}

if (!function_exists('format_forgot_password_mail')) {
    function format_forgot_password_mail()
    {
        $format = '
            <p>Halo <b>{name}</b>,</p>
            <p>Kami menerima permintaan untuk mereset kata sandi akun Anda. 
            Silakan klik tombol di bawah untuk membuat kata sandi baru.</p>

            <p>
                <a href="{link_btn_action}" style="display:inline-block;padding:10px 16px;
                background:#2563eb;color:#fff;text-decoration:none;
                border-radius:6px;font-weight:600;">
                Reset Password
                </a>
            </p>

            <p>Link ini berlaku selama {expired} menit. 
            Jika Anda tidak merasa meminta reset password, abaikan email ini.</p>
        ';

        return $format;
    }
}

if (!function_exists('maskEmail')) {
    function maskEmail(string $email): string
    {
        [$name, $domain] = explode('@', $email);

        if (strlen($name) > 2) {
            $visible = substr($name, 0, 3);
            $masked  = str_repeat('*', strlen($name) - 3);
        } else {
            $visible = substr($name, 0, 1);
            $masked  = str_repeat('*', max(strlen($name) - 1, 1));
        }

        return $visible . $masked . '@' . $domain;
    }
}
