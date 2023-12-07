<?php

/**
 * Sprawdzenie czy wtyczka została zainicjowana przez WordPressa. Jeżeli nie zwróci błąd 404.
 */
if ( !function_exists( 'add_action' ) ) {

    header("HTTP/1.0 404 Not Found");
    die();
}

?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script type="text/javascript">

    var count = 0;
    var today = new Date().toISOString().slice(0, 10);

    function drawTask(id, task) {

        count++;
        jQuery("#tasks").append(
            '<tr id="task_'+id+'">'+
            '<th scope="row">'+count+'</th>'+
            '<td class="text-nowrap">'+today+'</td>'+
            '<td>'+task+'</td>'+
            '<td class="text-nowrap m-0 p-0 text-end"><button class="btn btn-success" type="button" onclick="hideTask('+id+')"><i class="bi bi-check2-square"></i></button></td>'+
            '</tr>'
        );
    }

    function saveTask(task) {

        if (task.length === 0) return;

        jQuery.ajax({
            method: 'POST',
            dataType: 'json',
            url: ajaxurl,
            data: {
                'task': task,
                'action': '<?php echo wp_create_nonce('saveTask'); ?>'
            },
            success: function (result) {
                jQuery("#new_task").val("");
                drawTask (result['id'], result['task']);

            },
            error: function (result) {
                alert ('Nie udało się zapisać');
            }
        });
    }

    function hideTask(id) {

        jQuery.ajax({
            method: 'POST',
            dataType: 'json',
            url: ajaxurl,
            data: {
                'id': id,
                'action': '<?php echo wp_create_nonce('hideTask'); ?>'
            },
            success: function (result) {

                if (result == id) {

                    jQuery('#task_'+id).hide();

                    var count = 0;
                    let elements = jQuery('tr[id^="task_"]').children('th');
                    jQuery.each( elements, function( index, item ){

                        if (!jQuery(this).is(":hidden")) {
                            count++;
                            jQuery(this).html(count);
                        }
                    });
                }
            },
            error: function (result) {
                alert ('Nie udało się zapisać');
            }
        });
    }

</script>

<div class="container-fluid mt-4">

    <p class="h5 mx-1">Lista zadań TO DO</p>
    <div class="row mt-4 g-3 justify-content-center">
        <div class="w-50">
            <div class="input-group mb-3">
                <input type="text" id="new_task" class="form-control" placeholder="Zadanie">
                <button class="btn btn-primary" type="button" onclick="saveTask(getElementById('new_task').value.trim())"><i class="bi-plus-square"></i></button>
            </div>
<table class="table">
    <thead>
    <tr>
        <th scope="col">#</th>
        <th scope="col">Data</th>
        <th scope="col">Zadanie</th>
        <th scope="col"></th>
    </tr>
    </thead>
    <tbody id="tasks">

    <?php
        $db = new eskim_pl_example_to_do_DB();
        $count = 0;
        foreach ($db->getActiveTasks() as $entity) {

            $count++;
            echo "
            <tr id='task_$entity->id'>
                <th scope='row'>$count</th>
                <td class='text-nowrap'>$entity->created</td>
                <td>$entity->task</td>
                <td class='text-nowrap m-0 p-0 text-end'><button class='btn btn-success' type='button' onclick='hideTask($entity->id)'><i class='bi bi-check2-square'></i></button></td>
            </tr>
            ";
        }
    ?>
    </tbody>
</table>
        </div>
    </div>

</div>