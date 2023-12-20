<?php

/**
 * Sprawdzenie czy wtyczka została zaincjowana przez WordPressa. Jeżeli nie zwróci błąd 404.
 */
if ( !function_exists( 'add_action' ) ) {

    header("HTTP/1.0 404 Not Found");
    die();
}

/**
 * Daje możliwość korzystania z funkcji dbDelta()
 */
require_once (ABSPATH . 'wp-admin/includes/upgrade.php');

/**
 * Klasa do obsługi bazy danych
 */
class eskim_pl_example_to_do_DB {


    /**
     * Tworzy tabelę w bazie danych
     */
    static public function CREATE () : void {

        global $wpdb;
        $table = $wpdb->prefix . "eskim_pl_example_to_do";

        $sql = "CREATE TABLE IF NOT EXISTS $table (
                    id INT NOT NULL AUTO_INCREMENT,
					task VARCHAR (200),
					created TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
					closed INT NULL,
					PRIMARY KEY (id)
				);";

        dbDelta( $sql );
    }

    /**
     * Usuwa tabelę z bazy danych
     */
    static public function DROP () : void {

        global $wpdb;
        $table = $wpdb->prefix . "eskim_pl_example_to_do";

        $wpdb->query( "DROP TABLE IF EXISTS $table" );
    }

    /**
     * Dodaje zadanie
     *
     * @param string $task Zadanie do zapisania
     * @return int id zadania w bazie
     */
    public function addTask (string $task) : int {


        global $wpdb;
        $table = $wpdb->prefix . "eskim_pl_example_to_do";

        $wpdb->insert( $table,
            [
                'task' => $task
            ]);
        return $wpdb->insert_id;
    }

    /**
     * Ukrywa zadanie
     *
     * @param int $id Id zadania
     * @return void
     */
    public function hideTask (int $id) : void {

        global $wpdb;
        $table = $wpdb->prefix . "eskim_pl_example_to_do";

        $wpdb->update( $table,

            ['closed' => time()],
            ['id' => $id],
            ['%d'],
            ['%d']
        );
    }

    /**
     * Sprawdza czy pole jest ukryte
     *
     * @param int $id
     * @return bool
     */
    public function isHidden (int $id): bool {

        global $wpdb;
        $table = $wpdb->prefix . "eskim_pl_example_to_do";

        $query = $wpdb->prepare("
            SELECT closed
            FROM $table 
            WHERE id = %d",
            $id
        );

        $hidden = $wpdb->get_var ($query);
        return $hidden > 0;
    }

    /**
     * Pobiera zadanie
     *
     * @param int $id Identyfikator zadania
     * @return string|null
     */
    public function getTask (int $id) : ?string {

        global $wpdb;
        $table = $wpdb->prefix . "eskim_pl_example_to_do";

        $query = $wpdb->prepare ("
			SELECT task
			FROM $table 
			WHERE id = %d",
            $id
        );

        return $wpdb->get_var ($query);
    }


    /**
     * Pobiera aktywne zadania
     *
     * @return array|null
     */
    public function getActiveTasks () : ?array {

        global $wpdb;
        $table = $wpdb->prefix . "eskim_pl_example_to_do";

        $query = $wpdb->prepare ("
			SELECT id, task, DATE_FORMAT(created, '%%Y-%%m-%%d') AS created
			FROM $table 
			WHERE closed IS NULL
            ORDER BY created
        ");

        return $wpdb->get_results ($query);
    }

}

?>
