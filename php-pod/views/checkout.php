<div class="container py-5">
    <h2 class="fw-bold mb-4">💳 Plaćanje i Dostava</h2>

    <!-- Progress -->
    <div class="d-flex align-items-center gap-3 mb-5">
        <div class="d-flex align-items-center gap-2"><span class="badge rounded-circle bg-success p-2">✓</span> <span class="fw-semibold">Košarica</span></div>
        <div style="height:2px; flex:1; background:var(--primary)"></div>
        <div class="d-flex align-items-center gap-2"><span class="badge rounded-circle p-2" style="background:var(--primary)">2</span> <span class="fw-semibold">Podaci</span></div>
        <div style="height:2px; flex:1; background:#e0d9f5"></div>
        <div class="d-flex align-items-center gap-2"><span class="badge rounded-circle bg-secondary p-2">3</span> <span class="text-muted">Potvrda</span></div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <form method="POST" action="/checkout/place">

                <!-- Shipping Info -->
                <div class="bg-white border rounded-3 p-4 mb-4" style="border-color:var(--border)!important">
                    <h5 class="fw-bold mb-3">📦 Podaci za Dostavu</h5>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Ime i Prezime *</label>
                            <input type="text" name="shipping_name" class="form-control" required placeholder="Npr. Miloš Petrović"
                                value="<?= htmlspecialchars(Auth::user()['name'] ?? '') ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Email *</label>
                            <input type="email" name="guest_email" class="form-control" required
                                value="<?= htmlspecialchars(Auth::user()['email'] ?? '') ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Adresa *</label>
                            <input type="text" name="shipping_address" class="form-control" required placeholder="Ulica i broj">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Grad *</label>
                            <input type="text" name="shipping_city" class="form-control" required placeholder="Sarajevo">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Poštanski Broj *</label>
                            <input type="text" name="shipping_zip" class="form-control" required placeholder="71000">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Zemlja</label>
                            <select name="shipping_country" class="form-select">
                                <option>Bosnia and Herzegovina</option>
                                <option>Croatia</option>
                                <option>Serbia</option>
                                <option>Slovenia</option>
                                <option>Montenegro</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Napomena (opciono)</label>
                            <textarea name="note" class="form-control" rows="2" placeholder="Posebne upute za dostavu..."></textarea>
                        </div>
                    </div>
                </div>

                <!-- Payment -->
                <div class="bg-white border rounded-3 p-4 mb-4" style="border-color:var(--border)!important">
                    <h5 class="fw-bold mb-3">💰 Način Plaćanja</h5>
                    <div class="d-flex flex-column gap-3">
                        <label class="d-flex align-items-center gap-3 p-3 border rounded-3 cursor-pointer" style="cursor:pointer; border-color:var(--border)!important">
                            <input type="radio" name="payment_method" value="bank_transfer" checked>
                            <div>
                                <div class="fw-semibold">🏦 Bankovni Transfer</div>
                                <small class="text-muted">Uplatite na naš račun, narudžba se obrađuje po primitku uplate</small>
                            </div>
                        </label>
                        <label class="d-flex align-items-center gap-3 p-3 border rounded-3" style="cursor:pointer; border-color:var(--border)!important">
                            <input type="radio" name="payment_method" value="cash_on_delivery">
                            <div>
                                <div class="fw-semibold">💵 Pouzećem (COD)</div>
                                <small class="text-muted">Plaćate gotovinom kuriru pri preuzimanju</small>
                            </div>
                        </label>
                        <label class="d-flex align-items-center gap-3 p-3 border rounded-3" style="cursor:pointer; border-color:var(--border)!important">
                            <input type="radio" name="payment_method" value="card">
                            <div>
                                <div class="fw-semibold">💳 Kartica (uskoro)</div>
                                <small class="text-muted">Visa, Mastercard, Maestro — u pripremi</small>
                            </div>
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-3 fw-bold fs-5">
                    🛍️ Potvrdi Narudžbu
                </button>
                <p class="text-muted text-center small mt-3">Klikom na "Potvrdi Narudžbu" prihvatate naše Uslove Korišćenja</p>
            </form>
        </div>

        <!-- Order Summary -->
        <div class="col-lg-4">
            <div class="bg-white border rounded-3 p-4 sticky-top" style="top:80px; border-color:var(--border)!important">
                <h5 class="fw-bold mb-3">📋 Vaša Narudžba</h5>
                <?php foreach ($cartItems as $item): ?>
                <div class="d-flex gap-3 align-items-center mb-3 pb-3 border-bottom">
                    <div style="width:50px;height:50px;background:var(--light);border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:1.5rem">
                        <?php $emojis = ['👕','🏠','📚','🎒']; echo $emojis[($item['product']['category_id']-1) % 4]; ?>
                    </div>
                    <div class="flex-grow-1">
                        <div class="small fw-semibold"><?= htmlspecialchars($item['product']['name']) ?></div>
                        <div class="small text-muted"><?= $item['size'] ? $item['size'] : '' ?> <?= $item['color'] ? '· ' . $item['color'] : '' ?></div>
                        <div class="small">×<?= $item['qty'] ?></div>
                    </div>
                    <div class="fw-semibold"><?= number_format($item['subtotal'], 2) ?> KM</div>
                </div>
                <?php endforeach; ?>
                <div class="d-flex justify-content-between mb-2">
                    <span>Dostava:</span><span class="text-success">Besplatno</span>
                </div>
                <div class="d-flex justify-content-between fw-bold fs-5">
                    <span>Ukupno:</span>
                    <span style="color:var(--primary)"><?= number_format($total, 2) ?> KM</span>
                </div>
            </div>
        </div>
    </div>
</div>
