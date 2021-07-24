<?php
/**
 * La configuration de base de votre installation WordPress.
 *
 * Ce fichier est utilisé par le script de création de wp-config.php pendant
 * le processus d’installation. Vous n’avez pas à utiliser le site web, vous
 * pouvez simplement renommer ce fichier en « wp-config.php » et remplir les
 * valeurs.
 *
 * Ce fichier contient les réglages de configuration suivants :
 *
 * Réglages MySQL
 * Préfixe de table
 * Clés secrètes
 * Langue utilisée
 * ABSPATH
 *
 * @link https://fr.wordpress.org/support/article/editing-wp-config-php/.
 *
 * @package WordPress
 */

// ** Réglages MySQL - Votre hébergeur doit vous fournir ces informations. ** //
/** Nom de la base de données de WordPress. */
define( 'DB_NAME', 'tacos' );

/** Utilisateur de la base de données MySQL. */
define( 'DB_USER', 'root' );

/** Mot de passe de la base de données MySQL. */
define( 'DB_PASSWORD', '' );

/** Adresse de l’hébergement MySQL. */
define( 'DB_HOST', 'localhost' );

/** Jeu de caractères à utiliser par la base de données lors de la création des tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/**
 * Type de collation de la base de données.
 * N’y touchez que si vous savez ce que vous faites.
 */
define( 'DB_COLLATE', '' );

/**#@+
 * Clés uniques d’authentification et salage.
 *
 * Remplacez les valeurs par défaut par des phrases uniques !
 * Vous pouvez générer des phrases aléatoires en utilisant
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ le service de clés secrètes de WordPress.org}.
 * Vous pouvez modifier ces phrases à n’importe quel moment, afin d’invalider tous les cookies existants.
 * Cela forcera également tous les utilisateurs à se reconnecter.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         ':C6=0.V|y&(P~SU8sYrWB0=D|2w9#oO|PX{$m,;jj!O9qp6Z+J+(5%~8J*ZWn]fK' );
define( 'SECURE_AUTH_KEY',  'N=ty-6sFdSalqbC{8oZiVp5vB&E4~&$4U3bd<<g=/PreN^#Gad1IO$ /qOz7>9X!' );
define( 'LOGGED_IN_KEY',    'on?.X=S>&JKO1a]zut:&?%I8iW8{:LPv|pn~r0]^ &E @6h+<p#2 IctjL*.gf,R' );
define( 'NONCE_KEY',        '9)y*1yh5*&Z!Owzq2;cb.|Emo%#[;w#w~!5y.aT$lhg(<!IHVr=H,/ ZWH]RNgz9' );
define( 'AUTH_SALT',        '1z)gP)4VbDZCWvUW4#Mp2K=OO>Hn|-yZ^b95SYYePv;[5t:?&6NE{={H^A%e)9C;' );
define( 'SECURE_AUTH_SALT', 'T[t)i}51g68 x$6J|ma5gQBS!1ICIfA=?F.;p9!F0/>:i:pt,:2g`-wR4|d[W$cs' );
define( 'LOGGED_IN_SALT',   'HUd.+To[IBHSV3YY+]t=8znV(fLPK&p@VV{;Csn9R]37/[k!6E`CKJ.xiep=?P=U' );
define( 'NONCE_SALT',       '|R#kWtiMQ&=]Osb{#J1$?y6AI%43av:q$n*i[M!A+Nmf>BbP,tzl  lR^boV>*MB' );
/**#@-*/

/**
 * Préfixe de base de données pour les tables de WordPress.
 *
 * Vous pouvez installer plusieurs WordPress sur une seule base de données
 * si vous leur donnez chacune un préfixe unique.
 * N’utilisez que des chiffres, des lettres non-accentuées, et des caractères soulignés !
 */
$table_prefix = 'wp_';

/**
 * Pour les développeurs : le mode déboguage de WordPress.
 *
 * En passant la valeur suivante à "true", vous activez l’affichage des
 * notifications d’erreurs pendant vos essais.
 * Il est fortement recommandé que les développeurs d’extensions et
 * de thèmes se servent de WP_DEBUG dans leur environnement de
 * développement.
 *
 * Pour plus d’information sur les autres constantes qui peuvent être utilisées
 * pour le déboguage, rendez-vous sur le Codex.
 *
 * @link https://fr.wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* C’est tout, ne touchez pas à ce qui suit ! Bonne publication. */

/** Chemin absolu vers le dossier de WordPress. */
if ( ! defined( 'ABSPATH' ) )
  define( 'ABSPATH', dirname( __FILE__ ) . '/' );

/** Réglage des variables de WordPress et de ses fichiers inclus. */
require_once( ABSPATH . 'wp-settings.php' );
