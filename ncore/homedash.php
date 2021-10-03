<?php

if (!defined('_ECRIRE_INC_VERSION')) {
	return;
}


// -----------------------------------------------------------------------
// ------------------------- TYPES DE NOISETTE ---------------------------
// -----------------------------------------------------------------------

/**
 * Renvoie la configuration par défaut du dossier relatif où trouver les types de noisettes.
 * Cette information est utilisée a minima au chargement des types de noisettes disponibles.
 *
 * Homedash stocke les noisettes dans le dossier du privé nommé 'prive/squelettes/noisettes/'.
 *
 * @package SPIP\HOMEDASH\TYPE_NOISETTE\SERVICE
 *
 * @uses ncore_chercher_service()
 *
 * @param string $plugin
 *        Identifiant qui permet de distinguer le module appelant qui peut-être un plugin comme le noiZetier ou
 *        un script. Pour un plugin, le plus pertinent est d'utiliser le préfixe.
 *
 * @return string
 * 		Chemin relatif du dossier où chercher les types de noisette.
 */
function homedash_type_noisette_initialiser_dossier($plugin) {

	// Le lieu de stockage correspond à l'arborescence SPIP du privé.
	$dossier = 'prive/squelettes/noisettes/';

	return $dossier;
}


// -----------------------------------------------------------------------
// ----------------------------- CONTENEURS ------------------------------
// -----------------------------------------------------------------------

/**
 * Vérifie la conformité des index du tableau représentant le conteneur et supprime les index inutiles, si besoin.
 * Pour Homedash, la vérification concerne uniquement les conteneurs spécifiques (hors noisette conteneur) qui sont
 * toujours des blocs Z de la page d'accueil du privé associés à un auteur donné.
 *
 * @package SPIP\HOMEDASH\CONTENEUR\SERVICE
 *
 * @param string $plugin
 *        Identifiant qui permet de distinguer le module appelant qui peut-être un plugin comme le noiZetier ou
 *        un script. Pour un plugin, le plus pertinent est d'utiliser le préfixe.
 * @param array  $conteneur
 *        Tableau associatif descriptif du conteneur dont les index doivent être vérifiés.
 *
 * @return array
 *         Tableau du conteneur dont tous les index sont conformes (`squelette`, `auteur`, `id_auteur`)
 *         ou tableau vide si non conforme.
 */
function homedash_conteneur_verifier($plugin, $conteneur) {

	// Liste des index autorisés.
	static $index_conteneur = array('squelette', 'auteur', 'id_auteur');

	// On vérifie que les index autorisés sont les seuls retournés.
	include_spip('homedash_fonctions');
	$conteneur_verifie = array();
	if ($conteneur) {
		if (
			isset($conteneur['squelette'], $conteneur['auteur'], $conteneur['id_auteur'])
			and (in_array($conteneur['squelette'], homedash_bloc_lister_defaut()))
			and $conteneur['auteur']
			and intval($conteneur['id_auteur'])
		) {
			// Le conteneur contient les index minimaux, on le réduit à ces seuls index.
			$conteneur_verifie = array_intersect_key($conteneur, array_flip($index_conteneur));
		}
	}

	return $conteneur_verifie;
}

/**
 * Construit un identifiant unique pour le conteneur de noisettes hors les noisettes conteneur.
 * Pour Homedash, un conteneur est toujours un bloc Z de la page d'accueil du privé associé à un auteur donné.
 *
 * @package SPIP\HOMEDASH\CONTENEUR\SERVICE
 *
 * @param string $plugin
 *        Identifiant qui permet de distinguer le module appelant qui peut-être un plugin comme le noiZetier ou
 *        un script. Pour un plugin, le plus pertinent est d'utiliser le préfixe.
 * @param array $conteneur
 *        Tableau associatif descriptif du conteneur. Pour Homedash, les seuls index autorisés sont
 *        `squelette`, `auteur` et `id_auteur`.
 *
 * @return string
 *         L'identifiant calculé à partir du tableau.
 */
function homedash_conteneur_identifier($plugin, $conteneur) {

	// On initialise l'identifiant à vide.
	$id_conteneur = '';

	// Les noisettes conteneur ont été identifiées par N-Core, inutile donc de s'en préoccuper.
	// La conteneur a préalablement été vérifié et est donc conforme. Tous les index existent donc.
	if ($conteneur) {
		$id_conteneur .= "{$conteneur['squelette']}|{$conteneur['auteur']}|{$conteneur['id_auteur']}";
	}

	return $id_conteneur;
}

/**
 * Reconstruit le conteneur sous forme de tableau à partir de son identifiant unique (fonction inverse
 * de `homedash_conteneur_identifier`).
 * N-Core ne fournit le conteneur pour les noisettes conteneur.
 * Pour les autres conteneurs, c'est à Homedash de s'en occuper.
 *
 * @package SPIP\HOMEDASH\CONTENEUR\SERVICE
 *
 * @param string $plugin
 *        Identifiant qui permet de distinguer le module appelant qui peut-être un plugin comme le noiZetier ou
 *        un script. Pour un plugin, le plus pertinent est d'utiliser le préfixe.
 * @param string $id_conteneur
 *        Identifiant unique du conteneur. Si l'id correspond à une noisette conteneur le traitement sera fait
 *        par N-Core, sinon par le plugin utilisateur
 *
 * @return array
 *        Tableau représentatif du conteneur ou tableau vide en cas d'erreur.
 */
function homedash_conteneur_construire($plugin, $id_conteneur) {

	// Il faut recomposer le tableau du conteneur à partir de son id.
	// N-Core s'occupe des noisettes conteneur; Homedash n'a donc plus qu'à traiter les autres conteneur,
	// à savoir ses conteneurs spécifiques (blocs Z de la page d'accueil du privé associés à un auteur donné).
	$conteneur = array();
	if ($id_conteneur) {
		$elements = explode('|', $id_conteneur);
		if (count($elements) === 3) {
			// -- le type d'objet auteur et son id
			$conteneur['auteur'] = $elements[1];
			$conteneur['id_auteur'] = $elements[2];
			// -- le squelette
			$conteneur['squelette'] = $elements[0];
		}
	}

	return $conteneur;
}
