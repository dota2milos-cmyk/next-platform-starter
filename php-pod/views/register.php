<div class="min-vh-100 d-flex align-items-center" style="background:var(--light)">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="text-center mb-4">
                    <a href="/" class="text-decoration-none" style="font-size:2rem; font-weight:900; color:var(--dark)">Print<span style="color:var(--accent)">Craft</span></a>
                    <p class="text-muted mt-1">Kreirajte besplatan račun</p>
                </div>
                <div class="bg-white border rounded-4 p-4" style="border-color:var(--border)!important">
                    <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>
                    <form method="POST" action="/register">
                        <div class="mb-3">
                            <label class="form-label">Ime i Prezime</label>
                            <input type="text" name="name" class="form-control" required autofocus placeholder="Vaše ime">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email adresa</label>
                            <input type="email" name="email" class="form-control" required placeholder="vasemail@example.com">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Lozinka</label>
                            <input type="password" name="password" class="form-control" required placeholder="Minimum 6 znakova" minlength="6">
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Potvrdi Lozinku</label>
                            <input type="password" name="password_confirm" class="form-control" required placeholder="Ponovite lozinku">
                        </div>
                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" id="terms" required>
                            <label class="form-check-label small" for="terms">Prihvaćam <a href="#" style="color:var(--primary)">Uvjete Korišćenja</a> i <a href="#" style="color:var(--primary)">Politiku Privatnosti</a></label>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">Kreiraj Račun 🚀</button>
                    </form>
                    <hr>
                    <p class="text-center mb-0">Već imate račun? <a href="/login" class="fw-semibold" style="color:var(--primary)">Prijavite se</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
