<?php
include("Header_YM.php");
include("RF_Panel_Admin_YM.php");
?>
<!-- Botón de regreso -->
<a href="Home_YM.php" class="admin-back-btn" title="Volver a Home">
    <i class="bi bi-house-fill"></i>
</a>

<div class="admin-panel">
    <h2 class="admin-title">Panel de Administración</h2>
    
    <!-- Artistas -->
    <div class="admin-card">
        <div class="admin-card-header">
            <h3 class="admin-card-title">
                <i class="bi bi-music-note-beamed admin-icon"></i>
                Artistas Pendientes
            </h3>
            <span class="admin-badge">
                <?php echo $artistas->num_rows; ?> pendientes
            </span>
        </div>
        <div class="admin-card-body">
            <div class="admin-table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th><i class="bi bi-envelope admin-icon"></i>Correo</th>
                            <th><i class="bi bi-person admin-icon"></i>Nombre</th>
                            <th><i class="bi bi-calendar admin-icon"></i>Fecha de Nacimiento</th>
                            <th><i class="bi bi-check-circle admin-icon"></i>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($artista = $artistas->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($artista['CorrArti']); ?></td>
                            <td><?php echo htmlspecialchars($artista['NombArtis']); ?></td>
                            <td><?php echo htmlspecialchars($artista['FechNacA']); ?></td>
                            <td>
                                <form method="POST" class="d-inline">
                                    <input type="hidden" name="tipo" value="artista">
                                    <input type="hidden" name="correo" value="<?php echo htmlspecialchars($artista['CorrArti']); ?>">
                                    <button type="submit" name="confirmar" class="admin-btn-confirm">
                                        <i class="bi bi-check-lg"></i>Confirmar
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Discográficas -->
    <div class="admin-card">
        <div class="admin-card-header">
            <h3 class="admin-card-title">
                <i class="bi bi-building admin-icon"></i>
                Discográficas Pendientes
            </h3>
            <span class="admin-badge">
                <?php echo $discograficas->num_rows; ?> pendientes
            </span>
        </div>
        <div class="admin-card-body">
            <div class="admin-table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th><i class="bi bi-envelope admin-icon"></i>Correo</th>
                            <th><i class="bi bi-building admin-icon"></i>Nombre</th>
                            <th><i class="bi bi-check-circle admin-icon"></i>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($discografica = $discograficas->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($discografica['CorrDisc']); ?></td>
                            <td><?php echo htmlspecialchars($discografica['NombDisc']); ?></td>
                            <td>
                                <form method="POST" class="d-inline">
                                    <input type="hidden" name="tipo" value="discografica">
                                    <input type="hidden" name="correo" value="<?php echo htmlspecialchars($discografica['CorrDisc']); ?>">
                                    <button type="submit" name="confirmar" class="admin-btn-confirm">
                                        <i class="bi bi-check-lg"></i>Confirmar
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Oyentes -->
    <div class="admin-card">
        <div class="admin-card-header">
            <h3 class="admin-card-title">
                <i class="bi bi-people-fill admin-icon"></i>
                Oyentes sin Permisos
            </h3>
            <span class="admin-badge">
                <?php echo $oyentes->num_rows; ?> pendientes
            </span>
        </div>
        <div class="admin-card-body">
            <div class="admin-table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th><i class="bi bi-envelope admin-icon"></i>Correo</th>
                            <th><i class="bi bi-person admin-icon"></i>Nombre</th>
                            <th><i class="bi bi-check-circle admin-icon"></i>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($oyente = $oyentes->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($oyente['CorrOyen']); ?></td>
                            <td><?php echo htmlspecialchars($oyente['NomrUsua']); ?></td>
                            <td>
                                <form method="POST" class="d-inline">
                                    <input type="hidden" name="tipo" value="oyente">
                                    <input type="hidden" name="correo" value="<?php echo htmlspecialchars($oyente['CorrOyen']); ?>">
                                    <button type="submit" name="confirmar" class="admin-btn-confirm">
                                        <i class="bi bi-check-lg"></i>Otorgar Permisos
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
include("Footer_YM.php");
?>