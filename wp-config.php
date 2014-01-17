<?php
/**
 * La configuration de base de votre installation WordPress.
 *
 * Ce fichier contient les réglages de configuration suivants : réglages MySQL,
 * préfixe de table, clefs secrètes, langue utilisée, et ABSPATH.
 * Vous pouvez en savoir plus à leur sujet en allant sur 
 * {@link http://codex.wordpress.org/fr:Modifier_wp-config.php Modifier
 * wp-config.php}. C'est votre hébergeur qui doit vous donner vos
 * codes MySQL.
 *
 * Ce fichier est utilisé par le script de création de wp-config.php pendant
 * le processus d'installation. Vous n'avez pas à utiliser le site web, vous
 * pouvez simplement renommer ce fichier en "wp-config.php" et remplir les
 * valeurs.
 *
 * @package WordPress
 */

// ** Réglages MySQL - Votre hébergeur doit vous fournir ces informations. ** //
/** Nom de la base de données de WordPress. */
define('DB_NAME', 'WP2014');

/** Utilisateur de la base de données MySQL. */
define('DB_USER', 'root');

/** Mot de passe de la base de données MySQL. */
define('DB_PASSWORD', '');

/** Adresse de l'hébergement MySQL. */
define('DB_HOST', 'localhost');

/** Jeu de caractères à utiliser par la base de données lors de la création des tables. */
define('DB_CHARSET', 'utf8');

/** Type de collation de la base de données. 
  * N'y touchez que si vous savez ce que vous faites. 
  */
define('DB_COLLATE', '');

/**#@+
 * Clefs uniques d'authentification et salage.
 *
 * Remplacez les valeurs par défaut par des phrases uniques !
 * Vous pouvez générer des phrases aléatoires en utilisant 
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ le service de clefs secrètes de WordPress.org}.
 * Vous pouvez modifier ces phrases à n'importe quel moment, afin d'invalider tous les cookies existants.
 * Cela forcera également tous les utilisateurs à se reconnecter.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'Vk`=3H-B9_lbN ~?g% tJeb!/9E%VrK#,0jdNZ7;Tw.dR)hSk,!F,^ZRaz$r>/r+');
define('SECURE_AUTH_KEY',  '?$x|.[$zUr+c <V-1G+G,;Ze+/rHkr$-_d*WJHor&1OOlZ-gHDZp,v0uxi$rZdm|');
define('LOGGED_IN_KEY',    '+8&TuU6~AeVYc^#}+Y+R[K_9i}fE$CKw|bd5Tz^tFZ3Q.71 YOaw|x^ac-.pww[^');
define('NONCE_KEY',        'xzp70:8Y{9@{fC--,!OT{x+keE^_hAa9PE/@XMB>98p{vI*.1|1ih.Y%ah+I@G6/');
define('AUTH_SALT',        'Jtg|Q2lCbDE80k;e;mSMs$8P||HH:dSzh&*-|1bS:x+K|,>bEL<)FR-L,W<A,ilZ');
define('SECURE_AUTH_SALT', '9-+qdYV%g%8 )r)dv0;0-LA3of3=][(R1Y=@qZbGoiGbaDo~|fhMt>}|-)>sW76_');
define('LOGGED_IN_SALT',   'l13n>bF|Q)ZcuWvZ|r~t}USt$YgDIg*PV<Q;hr~OQ.^z,O=VQItvH!)7UICFCT|Z');
define('NONCE_SALT',       '2.+=#MmlIB.}%n]us:ew`|>4/^Tte+hKu+You%1-i17Cj-7@FA{/%<Gi)V^}/7zh');
/**#@-*/

/**
 * Préfixe de base de données pour les tables de WordPress.
 *
 * Vous pouvez installer plusieurs WordPress sur une seule base de données
 * si vous leur donnez chacune un préfixe unique. 
 * N'utilisez que des chiffres, des lettres non-accentuées, et des caractères soulignés!
 */
$table_prefix  = 'wp_';

/**
 * Langue de localisation de WordPress, par défaut en Anglais.
 *
 * Modifiez cette valeur pour localiser WordPress. Un fichier MO correspondant
 * au langage choisi doit être installé dans le dossier wp-content/languages.
 * Par exemple, pour mettre en place une traduction française, mettez le fichier
 * fr_FR.mo dans wp-content/languages, et réglez l'option ci-dessous à "fr_FR".
 */
define('WPLANG', 'fr_FR');

/** 
 * Pour les développeurs : le mode deboguage de WordPress.
 * 
 * En passant la valeur suivante à "true", vous activez l'affichage des
 * notifications d'erreurs pendant votre essais.
 * Il est fortemment recommandé que les développeurs d'extensions et
 * de thèmes se servent de WP_DEBUG dans leur environnement de 
 * développement.
 */ 
define('WP_DEBUG', false); 

/* C'est tout, ne touchez pas à ce qui suit ! Bon blogging ! */

/** Chemin absolu vers le dossier de WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Réglage des variables de WordPress et de ses fichiers inclus. */
require_once(ABSPATH . 'wp-settings.php');