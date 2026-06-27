<div class="d-flex justify-content-between mb-4">
    <div></div>
    <a href="/admin/products/new" class="btn btn-primary">➕ Novi Proizvod</a>
</div>

<?php if (!empty($form)): ?>
<!-- Product Form -->
<div class="bg-white rounded-3 p-4 mb-4">
    <h5 class="fw-bold mb-4"><?= empty($editProduct) ? '➕ Novi Proizvod' : '✏️ Uredi: ' . htmlspecialchars($editProduct['name']) ?></h5>
    <form method="POST" action="/admin/products/save" enctype="multipart/form-data">
        <?php if (!empty($editProduct)): ?><input type="hidden" name="id" value="<?= $editProduct['id'] ?>"><?php endif; ?>
        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label">Naziv Proizvoda *</label>
                <input type="text" name="name" class="form-control" required value="<?= htmlspecialchars($editProduct['name'] ?? '') ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Kategorija *</label>
                <select name="category_id" class="form-select" required>
                    <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= ($editProduct['category_id'] ?? '') == $cat['id'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-12">
                <label class="form-label">Opis</label>
                <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($editProduct['description'] ?? '') ?></textarea>
            </div>
            <div class="col-md-3">
                <label class="form-label">Cijena (KM) *</label>
                <input type="number" name="base_price" class="form-control" step="0.01" required value="<?= $editProduct['base_price'] ?? '' ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Zalihe</label>
                <input type="number" name="stock" class="form-control" value="<?= $editProduct['stock'] ?? 100 ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Veličine (JSON)</label>
                <input type="text" name="sizes" class="form-control" placeholder='["S","M","L"]' value="<?= htmlspecialchars($editProduct['sizes'] ?? '[]') ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Boje (JSON)</label>
                <input type="text" name="colors" class="form-control" placeholder='["Crna","Bijela"]' value="<?= htmlspecialchars($editProduct['colors'] ?? '[]') ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Slika (upload)</label>
                <input type="file" name="image" class="form-control" accept="image/*">
            </div>
            <div class="col-md-4 d-flex align-items-end gap-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="allow_custom_design" id="acd" <?= !empty($editProduct['allow_custom_design']) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="acd">Custom Design dostupan</label>
                </div>
            </div>
            <div class="col-md-4 d-flex align-items-end gap-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="active" id="act" <?= !isset($editProduct) || !empty($editProduct['active']) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="act">Aktivan (vidljiv na sajtu)</label>
                </div>
            </div>
        </div>
        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-primary">💾 Sačuvaj</button>
            <a href="/admin/products" class="btn btn-outline-secondary">Odustani</a>
        </div>
    </form>
</div>
<?php endif; ?>

<!-- Products Table -->
<div class="bg-white rounded-3 p-4">
    <h5 class="fw-bold mb-3">Svi Proizvodi (<?= count($products) ?>)</h5>
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr><th>#</th><th>Naziv</th><th>Kategorija</th><th>Cijena</th><th>Zalihe</th><th>Status</th><th>Akcije</th></tr>
            </thead>
            <tbody>
                <?php foreach ($products as $p): ?>
                <tr>
                    <td><?= $p['id'] ?></td>
                    <td>
                        <strong><?= htmlspecialchars($p['name']) ?></strong>
                        <?php if ($p['allow_custom_design']): ?><br><span class="badge bg-info text-dark" style="font-size:0.7rem">Custom</span><?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($p['category_name']) ?></td>
                    <td class="fw-semibold"><?= number_format($p['base_price'], 2) ?> KM</td>
                    <td><?= $p['stock'] ?></td>
                    <td>
                        <?php if ($p['active']): ?>
                        <span class="badge bg-success">Aktivan</span>
                        <?php else: ?>
                        <span class="badge bg-danger">Neaktivan</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="/admin/products/<?= $p['id'] ?>/edit" class="btn btn-xs btn-sm btn-outline-primary me-1">✏️</a>
                        <form method="POST" action="/admin/products/<?= $p['id'] ?>/delete" class="d-inline" onsubmit="return confirm('Obrisati?')">
                            <button class="btn btn-xs btn-sm btn-outline-danger">🗑️</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
