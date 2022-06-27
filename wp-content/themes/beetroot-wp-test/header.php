<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package beetroot-wp-test
 */

if (class_exists('ACF')) {
    //Services
    $google_analytics = get_field('google_analytics', 'options');
    $google_tag_manager_head = get_field('google_tag_manager_head', 'options');
    $google_tag_manager_body = get_field('google_tag_manager_body', 'options');
}
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">
    <?php wp_head(); ?>
    <?php echo !empty($google_analytics) ? $google_analytics : false; ?>
    <?php echo !empty($google_tag_manager_head) ? $google_tag_manager_head : false; ?>
    <script crossorigin="anonymous" nomodule src="https://polyfill.io/v3/polyfill.min.js?features=blissfuljs%2Cdefault%2Ces2015%2Ces2016%2Ces2017%2Ces2018%2Ces5%2Ces6%2Ces7%2CNodeList.prototype.forEach%2CIntersectionObserver%2CIntersectionObserverEntry"></script>
</head>

<?php
//Template data page names
$homePage = is_front_page() ? 'homePage' : '';
$blogPage = is_home() ? 'blogPage' : '';
$policyPage = is_page_template('template-policy.php') ? 'policyPage' : '';
$contactPage = is_page_template('template-contact.php') ? 'contactPage' : '';

$pageNameArr = [
    $homePage,
    $blogPage,
    $policyPage,
    $contactPage,
];
$pageNameArrFilter   = array_filter( $pageNameArr );
$pageNameArrFilter   = array_shift( $pageNameArrFilter );
$GLOBALS['pageName'] = $pageNameArrFilter;
$pageClass           = $GLOBALS['pageName'];
?>

<body <?php body_class($pageClass); ?> data-page-name="<?php echo $GLOBALS['pageName']; ?>">
<?php echo !empty($google_tag_manager_body) ? $google_tag_manager_body : false; ?>
<?php get_template_part('template-parts/components/header-inner'); ?>
<main id="main">
