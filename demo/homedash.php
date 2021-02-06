<?php

if (!defined('_ECRIRE_INC_VERSION')) {
	return;
}

include_spip('inc/config');
include_spip('inc/ncore_type_noisette');
include_spip('inc/ncore_noisette');
include_spip("ncore/ncore");
include_spip('ncore/dashboard');

foreach (array('dashboard') as $_plugin) {
	var_dump("PLUGIN : $_plugin");

	$timestamp_debut = microtime(true);

//	type_noisette_charger($_plugin, true);
//	var_dump(type_noisette_repertorier($_plugin));
	var_dump(lire_config("${_plugin}_noisettes"));

	$conteneur = array('auteur' => 1, 'bloc' => 'contenu');

	echo '<h1>Ajout noisettes</h1>';
	$retour = noisette_ajouter($_plugin, 'bloctexte', $conteneur);
	echo '<h2>Noisette bloctexte</h2>';
	var_dump(ncore_noisette_lister($_plugin));

	$retour = noisette_ajouter($_plugin, 'conteneur', $conteneur);
	echo '<h2>Noisette conteneur</h2>';
	var_dump(ncore_noisette_lister($_plugin));

	$retour = noisette_ajouter($_plugin, 'codespip', $conteneur, 2);
	echo '<h2>Noisette codespip</h2>';
	var_dump(ncore_noisette_lister($_plugin));

	echo '<h1>Suppression noisettes</h1>';
	noisette_supprimer($_plugin, array('id_conteneur' => 'auteur/1|contenu', 'rang_noisette' => 2));
	echo '<h2>Noisette codespip</h2>';
	var_dump(ncore_noisette_lister($_plugin, $conteneur));
	noisette_supprimer($_plugin, array('id_conteneur' => 'auteur/1|contenu', 'rang_noisette' => 2));
	echo '<h2>Noisette conteneur</h2>';
	var_dump(ncore_noisette_lister($_plugin, $conteneur));
	noisette_supprimer($_plugin, array('id_conteneur' => 'auteur/1|contenu', 'rang_noisette' => 1));
	echo '<h2>Noisette bloctexte</h2>';
	var_dump(ncore_noisette_lister($_plugin, $conteneur));

//	noisette_deplacer($_plugin, array('squelette' => 'content/article', 'contexte' => array(), 'rang' => 3), 1);
//	noisette_deplacer($_plugin, array('squelette' => 'content/article', 'contexte' => $contexte, 'rang' => 3), 1);

//	$retour = noisette_lire($_plugin, array('squelette' => 'content/article', 'contexte' => array(), 'rang' => 3), '');
//	var_dump($retour);
//	$retour = noisette_lire($_plugin, array('squelette' => 'content/article', 'contexte' => array(), 'rang' => 3), 'id_noisette');
//	var_dump($retour);

//	$retour = noisette_lire($_plugin, array('squelette' => 'content/article', 'contexte' => $contexte, 'rang' => 3), '');
//	var_dump($retour);
//	$retour = noisette_lire($_plugin, array('squelette' => 'content/article', 'contexte' => $contexte, 'rang' => 3), 'id_noisette');
//	var_dump($retour);

//	noisette_vider($_plugin, 'content/article', array());
//	noisette_vider($_plugin, 'content/article', $contexte);


	$timestamp_fin = microtime(true);
	$duree = $timestamp_fin - $timestamp_debut;
	var_dump($duree*1000);
}
