<div class="min-vh-100 d-flex align-items-center" style="background:var(--light)">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="text-center mb-4">
                    <a href="/" class="text-decoration-none" style="font-size:2rem; font-weight:900; color:var(--dark)">Print<span style="color:var(--accent)">Craft</span></a>
                    <p class="text-muted mt-1">Prijavite se u vaš račun</p>
                </div>
                <div class="bg-white border rounded-4 p-4" style="border-color:var(--border)!important">
                    <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>
                    <form method="POST" action="/login">
                        <div class="mb-3">
                            <label class="form-label">Email adresa</label>
                            <input type="email" name="email" class="form-control" required autofocus placeholder="vasemail@example.com">
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Lozinka</label>
                            <input type="password" name="password" class="form-control" required placeholder="••••••••">
                        </div>
                        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">Prijava</button>
                    </form>
                    <hr>
                    <div class="text-center">
                        <p class="mb-2">Nemate račun? <a href="/register" class="fw-semibold" style="color:var(--primary)">Registrujte se</a></p>
                        <div class="p-3 rounded-3 mt-3" style="background:var(--light)">
                            <small class="text-muted">Admin pristup:<br><strong>admin@pod.ba</strong> / <strong>admin123</strong></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
