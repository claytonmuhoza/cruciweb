<?php ob_start(); ?>
<table class="container">
    <thead>
        <tr>
            <th>Nom utilisateurs</th>
            <th>Rôle</th>
            <th>Supprimer</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?= $user['username'] ?></td>
                <td><?= $user['role'] ?></td>
                <td>
                   <?php if($user['role']!=='admin'):?>
                        <a class="btn-red" href="/admin/utilisateurs/delete/<?= $user['id'] ?>" onclick="return confirm('Voulez-vous vraiment supprimer cette grille ?')">Supprimer</a>
                    <?php endif;?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody> 
</table>
<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layout.php'; ?>