<?php
session_start();
session_unset();
session_destroy();
echo '<script type="text/javascript">
        setTimeout(function() {
            window.location.href = "?secao=login";
        }, 1);
    </script>';
exit;