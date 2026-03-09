<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin:0; padding:0; background-color:#f1f5f9; font-family:'Segoe UI', Arial, sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f1f5f9; padding: 48px 20px;">
        <tr>
            <td align="center">
                <table width="100%" cellpadding="0" cellspacing="0" style="max-width:520px;">

                    <tr>
                        <td align="center" style="padding-bottom: 28px;">
                            <table cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="background:#222A60; border-radius:16px; padding: 12px 24px;">
                                        <span style="font-family: monospace; font-size: 22px; font-weight: 700; color: white; letter-spacing: -0.5px;">Usuel</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="background: white; border-radius: 24px; overflow: hidden; box-shadow: 0 4px 32px rgba(34,42,96,0.10);">

                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="background: linear-gradient(135deg, #222A60 0%, #1a3a6b 100%); padding: 36px 40px 32px;">
                                        <table cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td style="background: rgba(255,255,255,0.12); border-radius: 14px; padding: 12px; width: 48px; height: 48px; text-align: center; vertical-align: middle;">
                                                    <span style="font-size: 24px;">🔐</span>
                                                </td>
                                                <td style="padding-left: 16px;">
                                                    <p style="margin:0; font-size:11px; font-weight:700; color:rgba(255,255,255,0.5); text-transform:uppercase; letter-spacing: 2px;">Sécurité du compte</p>
                                                    <p style="margin:4px 0 0; font-size:22px; font-weight:700; color:white; font-family: monospace;">Réinitialisation</p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="padding: 36px 40px 16px;">
                                        <p style="margin:0 0 12px; font-size:15px; color:#374151; line-height:1.6;">
                                            Nous avons reçu une demande de réinitialisation du mot de passe associé à votre compte Usuel.
                                        </p>
                                        <p style="margin:0 0 28px; font-size:14px; color:#9ca3af; line-height:1.6;">
                                            Cliquez sur le bouton ci-dessous pour choisir un nouveau mot de passe. Ce lien est valable <strong style="color:#374151;">60 minutes</strong>.
                                        </p>

                                        <table cellpadding="0" cellspacing="0" width="100%">
                                            <tr>
                                                <td align="center" style="padding-bottom: 28px;">
                                                    <a href="{{ url('/reinitialiser/' . $token . '/' . urlencode($email)) }}"
                                                       style="display:inline-block; background:#16987C; color:white; font-weight:700; font-size:15px; padding:16px 36px; border-radius:14px; text-decoration:none; letter-spacing:0.2px;">
                                                        Réinitialiser mon mot de passe →
                                                    </a>
                                                </td>
                                            </tr>
                                        </table>

                                        <table width="100%" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td style="border-top: 1px solid #f3f4f6; padding-top: 24px;">
                                                    <p style="margin:0 0 10px; font-size:12px; font-weight:600; color:#9ca3af; text-transform:uppercase; letter-spacing:1px;">Ou copiez ce lien</p>
                                                    <p style="margin:0; font-size:11px; color:#d1d5db; word-break:break-all; background:#f9fafb; border-radius:8px; padding: 10px 12px; font-family:monospace;">
                                                        {{ url('/reinitialiser/' . $token . '/' . urlencode($email)) }}
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="background:#f9fafb; border-top:1px solid #f3f4f6; padding:20px 40px; border-radius: 0 0 24px 24px;">
                                        <p style="margin:0; font-size:12px; color:#d1d5db; line-height:1.6;">
                                            🔒 Si vous n'avez pas demandé cette réinitialisation, ignorez cet email — votre mot de passe restera inchangé.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="padding-top: 28px;">
                            <p style="margin:0; font-size:12px; color:#9ca3af;">© {{ date('Y') }} Usuel — Plateforme d'évaluation des compétences numériques</p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>