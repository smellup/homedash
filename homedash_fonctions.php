<?php
/**
 * Ce fichier contient les filtres du plugin Homedash.
 *
 */
if (!defined('_ECRIRE_INC_VERSION')) {
	return;
}


// -----------------------------------------------------------------------
// --------------------- FILTRES BLOCS -----------------------
// -----------------------------------------------------------------------

/**
 * Renvoie la liste par défaut des identifiants des blocs de la page d'accueil du privé.
 *
 * @api
 *
 * @return array
 */
function homedash_bloc_lister_defaut() {

	// Stocker la liste des blocs par défaut pour éviter le recalcul sur le même hit.
	static $blocs_defaut = null;

	if (is_null($blocs_defaut)) {
		// Le privé de SPIP est en version Z v1
		$blocs_defaut = array('contenu', 'navigation', 'extra');
	}

	return $blocs_defaut;
}
