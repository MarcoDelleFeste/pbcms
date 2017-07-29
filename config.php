<?php
/**
 * Questo e' il file di configurazione generale del framework
 *
 * Qui si possono settare informazioni generiche quali.
 *
 * * indirizzo e-mail di sistema
 * * nome dell'ammistratore
 * * nome dell'autore o proprietario
 * * descrizione genereale del sito
 * * indirizzo e-mail dell'amministratore
 * * nome del sito
 *
 * Per le impostazioni avanzate operare sui file presenti nella cartella config
 *
 *
 * @author  Marco Delle Feste <marco.delle.feste@gmail.com>
 *
 * @since 1.0
 *
 * @package pitbullcms
 */
/**
 * indirizzo e-mail di sistema, quello utilizzato come mittente
 */
$email_system = 'marco.delle.feste@gmail.com';
/**
 * nome dell'amministratore dell'installazione
 */
$admin = 'Marco Delle Feste';
/**
 * nome dell'autore del sito, generalmente il proprietario
 */
$author = 'Marco Delle Feste';
/**
 * la descrizione genelare del sito, quella utilizzata in assenza di una descrizione specifica per la pagina
 */
$description = 'Versione test per uso del cms Little John senza supporto db';
/**
 * indirizzo e-mail dell'amministratore per comunicazioni di notifiche da parte del sistema
 */
$email_admin = 'Marco Delle Feste <marco.delle.feste@gmail.com>';
/**
 * Il nome del sito, sarà il prefisso presente all'inizio del metatag title
 */
$site_name	= 'PitbullCms - Default installation';


//SVILUPPO, DEBUG E MANUTENZIONE
$devel = true;

$debug = true;

$maintenance = false;

/*PHP ERRORS CONFIG*/
$display_errors = $debug;

$error_reporting = E_ALL; //& ~E_STRICT;