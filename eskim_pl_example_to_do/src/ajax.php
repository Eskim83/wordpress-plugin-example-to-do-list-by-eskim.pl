<?php

/**
 * Sprawdzenie czy wtyczka została zainicjowana przez WordPressa. Jeżeli nie zwróci błąd 404.
 */
if ( !function_exists( 'add_action' ) ) {

    header("HTTP/1.0 404 Not Found");
    die();
}

if ( isset ($_POST['action']) && wp_verify_nonce($_POST['action'], 'saveTask' ) ) {

    $db = new eskim_pl_example_to_do_DB();
    $id = $db->addTask($_POST['task']);
    $task = $db->getTask($id);

    echo json_encode([
        'id' => $id,
        'task' => $task
    ]);
    wp_die();
}

elseif ( isset ($_POST['action']) && wp_verify_nonce($_POST['action'], 'hideTask' ) ) {

    $db = new eskim_pl_example_to_do_DB();
    $db->hideTask($_POST['id']);
    $hidden  = $db->isHidden($_POST['id']);

    if ($hidden) {
        echo json_encode($_POST['id']);
        wp_die();
    }
}

?>
