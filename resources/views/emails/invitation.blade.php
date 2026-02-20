<!DOCTYPE html>
<html lang="fr">
<body style="font-family: sans-serif; background:#f3f4f6; padding: 40px;">
    <div style="max-width:520px; margin:auto; background:white; border-radius:16px; padding:40px;">
        <h1 style="color:#1e3a5f; font-size:24px;">Bienvenue sur Usuel</h1>
        <p>Bonjour {{ $user->firstname }} {{ $user->name }},</p>
        <p>Un compte a été créé pour vous. Cliquez sur le bouton ci-dessous pour finaliser votre inscription et choisir votre mot de passe.</p>
        <a href="{{ route('inscription', ['token' => $token]) }}"
           style="display:inline-block; margin-top:20px; padding:14px 28px; background:#22c55e; color:white; border-radius:12px; text-decoration:none; font-weight:bold;">
            Finaliser mon inscription
        </a>
        <p style="margin-top:32px; color:#9ca3af; font-size:12px;">Ce lien est personnel, ne le partagez pas.</p>
    </div>
</body>
</html>
