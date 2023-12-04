<?php
function isAdmin() {
    return isset($_SESSION['user_type']) && $_SESSION['user_type'] == 1;
}

?>