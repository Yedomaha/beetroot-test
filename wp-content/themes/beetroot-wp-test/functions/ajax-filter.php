<?php

add_action( 'wp_ajax_nopriv_jobs_filter_ajax', 'jobs_filter_ajax' );
add_action( 'wp_ajax_jobs_filter_ajax', 'jobs_filter_ajax' );

function jobs_filter_ajax() {

	$paged          = $_POST['page'];
	$posts_per_page = $_POST['postsPerPage'];
	$posts_html     = '';

	$search_query      = $_POST['search'];
	$departments       = $_POST['departments'];
	$locations_office  = $_POST['locations_office'];
	$locations_academy = $_POST['locations_academy'];
	$technologies      = $_POST['technologies'];

	$args = array(
		'paged'          => $paged,
		'posts_per_page' => $posts_per_page,
		'post_type'      => 'jobs',
		'orderby'        => 'date',
		'post_status'    => 'publish',
		'tax_query'      => array(
			'relation' => 'AND',
		),
	);

	if ( isset( $search_query ) && $search_query !== '' ) {
		$args['s'] = $search_query;
	}
	if ( isset( $departments ) && is_array( $departments ) && count( $departments ) > 0 ) {
		$args['tax_query'][] = array(
			'relation' => 'OR',
			'taxonomy' => 'job_departments',
			'field'    => 'term_id',
			'terms'    => $departments,
		);
	}
	if ( isset( $locations_office ) && is_array( $locations_office ) && count( $locations_office ) > 0 ) {
		$args['tax_query'][] = array(
			'relation' => 'OR',
			'taxonomy' => 'job_locations',
			'field'    => 'term_id',
			'terms'    => $locations_office,
		);
	}
	if ( isset( $locations_academy ) && is_array( $locations_academy ) && count( $locations_academy ) > 0 ) {
		$args['tax_query'][] = array(
			'relation' => 'OR',
			'taxonomy' => 'job_locations_academy',
			'field'    => 'term_id',
			'terms'    => $locations_academy,
		);
	}
	if ( isset( $technologies ) && is_array( $technologies ) && count( $technologies ) > 0 ) {
		$args['tax_query'][] = array(
			'relation' => 'OR',
			'taxonomy' => 'job_technologies',
			'field'    => 'term_id',
			'terms'    => $technologies,
		);
	}

	$posts = new WP_Query( $args );

	if ( $posts->have_posts() ) {
		while ( $posts->have_posts() ) {
			$posts->the_post();
			$posts_html = $posts_html . beetrootTestClass::load_template_part( "template-parts/loop/cpt-item" );
		}
		wp_reset_postdata();
	};



	$response = array(
		"posts"     => $posts_html,
		"max_pages" => $posts->max_num_pages,
		"posts_count" => $posts->found_posts,
		"args"      => $args,

	);

	wp_send_json( $response );

	wp_die();

}
