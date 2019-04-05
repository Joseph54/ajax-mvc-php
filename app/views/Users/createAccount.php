

<?php require APPROOT . '/views/inc/header.php'; ?>
    <form method="post" action="createAccount">
        <input type="text" name="username" placeholder="username">
        <input type="password" name="password" placeholder="password">
        <input type="submit" name="submit">
    </form>
<?php require APPROOT . '/views/inc/footer.php'; ?>