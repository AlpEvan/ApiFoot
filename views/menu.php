<nav class="navbar navbar-expand-lg bg-transparent">
    <div class="container-fluid">
        <a class="navbar-brand" href="">Api Foot</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-toggle"
            aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbar-toggle">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="/">Matchs</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/operations">Opérations</a>
                </li>
            </ul>

            <form method="POST" action="/search">
                <div class="input-group">
                    <input type="text" name="search" class="form-control"
                        placeholder="Chercher une équipe, joueur, ligue..."
                        value="<?= htmlspecialchars($data['search'] ?? '') ?>">
                    <button type="submit" class="btn btn-primary">🔍 Rechercher</button>
                </div>
                <?php if (isset($errors['search'])): ?>
                    <small class="text-danger">
                        <?= $errors['search'] ?>
                    </small>
                <?php endif; ?>
            </form>
        </div>
    </div>
</nav>