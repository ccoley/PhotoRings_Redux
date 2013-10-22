<?php
$allowed = array('png', 'jpg', 'jpeg', 'gif');

if (isset($_FILES['upl']) && $_FILES['upl']['error'] == 0) {
    $ext = pathinfo($_FILES['upl']['name'], PATHINFO_EXTENSION);
    if (!in_array(strtolower($ext), $allowed)) {
        echo '{"status":"error"}';
        exit;
    }

    if (move_uploaded_file($_FILES['upl']['tmp_name'], '/photorings/images/test/'.$_FILES['upl']['name'])) {
        echo '{"status":"success"}';
        exit;
    }
}

echo '{"status":"error"}';
exit;
?>
