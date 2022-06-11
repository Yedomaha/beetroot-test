<?php
/**
 * Class CtfFeedPro
 *
 * Extension of the CtfFeed class to add additional functionality to
 * the display of the Twitter feed
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

class CtfFeedPro extends CtfFeed
{
	/**
	 * @var array
	 */
	private $db_twitter_cards = array();

	private $check_for_duplicates = false;

	private $persistent_index;

	/**
	 * creates and returns all of the data needed to generate the output for the feed
	 *
	 * @param array $atts           data from the shortcode
	 * @param string $last_id_data  the last visible tweet on the feed, empty string if first set
	 * @param int $num_needed_input this number represents the number left to retrieve after the first set
	 * @return CtfFeedPro           the complete object for the feed
	 */
	public static function init( $atts, $last_id_data = '', $num_needed_input = 0, $ids_to_remove = array(), $persistent_index = 1 )
	{
		$feed = new CtfFeedPro( $atts, $last_id_data, $num_needed_input );

		$feed->setFeedOptions();

		$bool_false = array(
			'includereplies',
			'masonry',
			'autoscroll',
			'disablelightbox',
			'carousel',
			'carouselautoplay',
			'carouselpag',
			'sslonly',
			'shorturls'
		);
		$feed->setStandardBoolOptions( $bool_false, false );
		$feed->setStandardBoolOptions( array( 'persistentcache', 'includeretweets', 'autores' ), true );
		$feed->setStandardTextOptions( 'carouselarrows', 'onhover' );
		$feed->setStandardTextOptions( 'font_method', 'svg' );
		$feed->setStandardTextOptions( 'carouselheight', 'tallest' );
		$feed->setStandardTextOptions( 'carouselcols', 1 );
		$feed->setStandardTextOptions( 'carouselmobilecols', 1 );
		$feed->setStandardTextOptions( 'carouseltime', 5000 );
		$feed->setStandardTextOptions( 'carouselloop', 'none' );
		$feed->setStandardTextOptions( 'masonrycols', 3 );
		$feed->setStandardTextOptions( 'masonrymobilecols', 1 );
		$feed->setStandardTextOptions( 'autoscrolldistance', '200' );
		$feed->setStandardTextOptions( 'textlength', 280 );
		$feed->setStandardTextOptions( 'maxmedia', 4 );
		$feed->setStandardTextOptions( 'imagecols', 'auto' );
		$feed->setStandardTextOptions( 'inreplytotext', 'In reply to' );
		$feed->setStandardTextOptions( array( 'includewords', 'excludewords' ) );
		$feed->setStandardTextOptions( array( 'includeanyall', 'excludeanyall' ), 'any' );
		$feed->setStandardTextOptions( 'filterandor', 'and' );

		$feed->setDatabaseOnlyOptions( 'remove_by_id' );

		$feed->setLayout();

		if ( $feed->feed_options['layout'] === 'carousel' ) {
			$feed->setCarouselClasses();
		} elseif ( $feed->feed_options['layout'] === 'masonry' ) {
			$feed->setMasonryClasses();
		}

		if ( ctf_show( 'twittercards', $feed->feed_options ) ) {
			$feed->setExistingTwitterCardData();
		}

		$feed->setCacheTypeOption();
		if ( $feed->feed_options['persistentcache'] ) {
			$feed->persistent_index = $persistent_index;
		}

		$feed->setTweetSet();

		return $feed;
	}

	public function setLayout() {

		$db = $this->db_options;
		$atts = $this->atts;

		if ( ! empty( $atts['layout'] ) ) {
			switch ( $atts['layout'] ) {
				case 'carousel' :
					$this->feed_options['layout'] = 'carousel';
					break;
				case 'masonry' :
					$this->feed_options['layout'] = 'masonry';
					break;
				default :
					$this->feed_options['layout'] = 'list';
					break;
			}
		} elseif ( ! empty( $atts['carousel'] ) && $atts['carousel'] === 'true' ) {
			$this->feed_options['layout'] = 'carousel';
		} elseif ( ! empty( $atts['masonry'] ) && $atts['masonry'] === 'true' ) {
			$this->feed_options['layout'] = 'masonry';
		} elseif ( isset( $db['layout'] ) ) {
			switch ( $db['layout'] ) {
				case 'carousel' :
					$this->feed_options['layout'] = 'carousel';
					break;
				case 'masonry' :
					$this->feed_options['layout'] = 'masonry';
					break;
				default :
					$this->feed_options['layout'] = 'list';
					break;
			}
		} elseif ( isset( $db['carousel'] ) && $db['carousel'] ) {
			$this->feed_options['layout'] = 'carousel';
		} elseif ( isset( $db['masonry'] ) && $db['masonry'] ) {
			$this->feed_options['layout'] = 'masonry';
		} else {
			$this->feed_options['layout'] = 'list';
		}
	}



	/**
	 * sets the feed types and associated parameters
	 */
	public function setFeedTypeAndTermOptions()
	{
		$this->feed_options['feed_types_and_terms'] = array();
		$this->feed_options['type'] = '';
		$this->feed_options['screenname'] = isset( $this->atts['screenname'] ) ? $this->atts['screenname'] : ( isset( $this->db_options['usertimeline_text'] ) ? $this->db_options['usertimeline_text'] : '' );

		if ( isset( $this->atts['home'] ) && $this->atts['home'] == 'true' ) {
			$this->feed_options['feed_types_and_terms'][] = array( 'hometimeline', '' );
		}

		if ( isset( $this->atts['mentions'] ) && $this->atts['mentions'] == 'true' ) {
			$this->feed_options['feed_types_and_terms'][] = array( 'mentionstimeline', '' );
		}

		if ( isset( $this->atts['screenname'] ) ) {
			$screennames = explode( ',', $this->atts['screenname'] );

			foreach ( $screennames as $screenname ) {
				$this->feed_options['feed_types_and_terms'][] = array( 'usertimeline', $screenname );
			}
			$this->feed_options['screenname'] = $screennames[0];
		}

		if ( isset( $this->atts['search'] ) ) {
			$searches = isset( $this->atts['search'] ) ? $this->atts['search'] : '';

			if ( ! empty( $searches ) ) {
				$searches = explode( ',', $this->atts['search'] );

				foreach ( $searches as $search ) {
					$this->feed_options['feed_types_and_terms'][] = array( 'search', str_replace( "'", '"', $search ) );
				}

			}

			$this->check_for_duplicates = true;
		}

		if ( isset( $this->atts['hashtag'] ) ) {

			$hashtags = isset( $this->atts['hashtag'] ) ? $this->validateHashtags( $this->atts['hashtag'] ) : '';

			$hashtags = str_replace( ',', ' OR ', $hashtags );
			if ( ! empty( $hashtags ) ) {
				$this->feed_options['feed_types_and_terms'][] = array( 'hashtag', $hashtags );
			}

			$this->check_for_duplicates = true;
		}

		if ( isset( $this->atts['lists'] ) ) {
			$lists = explode( ',', $this->atts['lists'] );

			foreach ( $lists as $list ) {
				$this->feed_options['feed_types_and_terms'][] = array( 'lists', $list );
			}
		}

		if ( isset( $this->atts['list'] ) ) {
			$lists = explode( ',', $this->atts['list'] );

			foreach ( $lists as $list ) {
				$this->feed_options['feed_types_and_terms'][] = array( 'lists', $list );
			}
		}

		// if there is only one feed type and term, just use the single feed creation method
		if ( sizeof( $this->feed_options['feed_types_and_terms'] ) == 1 ) {
			$this->feed_options['type'] = $this->feed_options['feed_types_and_terms'][0][0];
			$this->feed_options['feed_term'] = $this->feed_options['feed_types_and_terms'][0][1];
			$this->feed_options['feed_types_and_terms'] = '';
		}

		if ( empty( $this->feed_options['type'] ) && empty( $this->feed_options['feed_types_and_terms'] ) ) {
			$this->feed_options['type'] = isset( $this->db_options['type'] ) ? $this->db_options['type'] : '';
			switch ( $this->feed_options['type'] ) {
				case 'hometimeline':
					$this->feed_options['type'] = 'hometimeline';
					break;
				case 'mentionstimeline':
					$this->feed_options['type'] = 'mentionstimeline';
					break;
				case 'search':
					$search_str = isset( $this->db_options['search_text'] ) ? $this->db_options['search_text'] : '';

					$this->feed_options['feed_types_and_terms'][] = array( 'search', $search_str );
					$this->check_for_duplicates = true;

					break;
				case 'hashtag':
					$hashtag_str = isset( $this->db_options['hashtag_text'] ) ? $this->db_options['hashtag_text'] : '';
					$hashtag_str = str_replace( ',', ' OR ', $hashtag_str );

					$this->feed_options['feed_types_and_terms'][] = array( 'hashtag', $hashtag_str );
					$this->check_for_duplicates = true;

					break;
				case 'lists':
				$lists_str = isset( $this->db_options['lists_id'] ) ? $this->db_options['lists_id'] : '';
					$lists = explode( ',', $lists_str );

					foreach ( $lists as $list ) {
						$this->feed_options['feed_types_and_terms'][] = array( 'lists', $list );
					}
					break;
				default:
					$screennames_str = isset( $this->db_options['usertimeline_text'] ) ? $this->db_options['usertimeline_text'] : '';
					$screennames = explode( ',', $screennames_str );

					foreach ( $screennames as $screenname ) {
						$this->feed_options['feed_types_and_terms'][] = array( 'usertimeline', $screenname );
					}
					$this->feed_options['screenname'] = $screennames[0];
					break;

			}
			// if there is only one feed type and term, just use the single feed creation method
			if ( sizeof( $this->feed_options['feed_types_and_terms'] ) == 1 ) {
				$this->feed_options['type'] = $this->feed_options['feed_types_and_terms'][0][0];
				$this->feed_options['feed_term'] = $this->feed_options['feed_types_and_terms'][0][1];
				$this->feed_options['feed_types_and_terms'] = '';
			}
		}

	}

	private function validateHashtags( $val ) {
		$hashtags = preg_replace( "/#{2,}/", '', trim( $val ) );
		$hashtags = str_replace( "OR", ',', $hashtags );
		$hashtags = str_replace( ' ', '', $hashtags );

		$hashtags = explode( ',', $hashtags );

		$new_val = array();

		if ( ! empty( $hashtags ) ) {
			foreach ( $hashtags as $hashtag ) {
				if ( substr( $hashtag, 0, 1 ) != '#' && $hashtag != '' ) {
					$new_val[] .= '#' . $hashtag;
				} else {
					$new_val[] .= $hashtag;
				}
			}
		}

		$new_val = implode( ' OR ', $new_val );

		return $new_val;
	}

	public function setCacheTypeOption() {
		if ( $this->feed_options['persistentcache'] && ( $this->feed_options['type'] == 'search' || $this->feed_options['type'] == 'hashtag' ) ) {
			$this->feed_options['persistentcache'] = true;
		} else {
			$this->feed_options['persistentcache'] = false;
		}
	}

	/**
	 * sets the visible parts of each tweet for the feed
	 */
	public function setIncludeExcludeOptions()
	{
		$this->feed_options['tweet_includes'] = array();
		$this->feed_options['tweet_excludes'] = array();
		$this->feed_options['tweet_includes'] = isset( $this->atts['include'] ) ? explode( ',', str_replace( ', ', ',', $this->atts['include'] ) ) : array();
		if ( empty( $this->feed_options['tweet_includes'][0] ) ) {
			$this->feed_options['tweet_excludes'] = isset( $this->atts['exclude'] ) ? explode( ',', str_replace( ', ', ',', $this->atts['exclude'] ) ) : array();
		}
		if ( empty( $this->feed_options['tweet_excludes'][0] ) && empty( $this->feed_options['tweet_includes'][0] ) ) {
			$this->feed_options['tweet_includes'][] = isset( $this->db_options['include_retweeter'] ) && $this->db_options['include_retweeter'] == false ? null : 'retweeter';
			$this->feed_options['tweet_includes'][] = isset( $this->db_options['include_avatar'] ) && $this->db_options['include_avatar'] == false ? null : 'avatar';
			$this->feed_options['tweet_includes'][] = isset( $this->db_options['include_author'] ) && $this->db_options['include_author'] == false ? null : 'author';
			$this->feed_options['tweet_includes'][] = isset( $this->db_options['include_logo'] ) && $this->db_options['include_logo'] == false ? null : 'logo';
			$this->feed_options['tweet_includes'][] = isset( $this->db_options['include_text'] ) && $this->db_options['include_text'] == false ? null : 'text';
			$this->feed_options['tweet_includes'][] = isset( $this->db_options['include_date'] ) && $this->db_options['include_date'] == false ? null : 'date';
			$this->feed_options['tweet_includes'][] = isset( $this->db_options['include_actions'] ) && $this->db_options['include_actions'] == false ? null : 'actions';
			$this->feed_options['tweet_includes'][] = isset( $this->db_options['include_twitterlink'] ) && $this->db_options['include_twitterlink'] == false ? null : 'twitterlink';
			$this->feed_options['tweet_includes'][] = isset( $this->db_options['include_linkbox'] ) && $this->db_options['include_linkbox'] == false ? null : 'linkbox';
			$this->feed_options['tweet_includes'][] = isset( $this->db_options['include_repliedto'] ) && $this->db_options['include_repliedto'] == false ? null : 'repliedto';
			$this->feed_options['tweet_includes'][] = isset( $this->db_options['include_media'] ) && $this->db_options['include_media'] == false ? null : 'media';
			$this->feed_options['tweet_includes'][] = isset( $this->db_options['include_twittercards'] ) && $this->db_options['include_twittercards'] == false ? null : 'twittercards';
		}
	}

	/**
	 *  Adds classes to the main #ctf element to be used to create the masonry
	 *  layout for feeds
	 */
	private function setMasonryClasses()
	{
		$options = $this->feed_options;
		$classes_to_add = '';

		if ( $this->feed_options['layout'] === 'masonry' ) {
			$classes_to_add .= ' ctf-masonry';

			if ( $options['masonrycols'] > 3 && $options['masonrycols'] < 7 ) {
				$classes_to_add .= ' masonry-' . $options['masonrycols'] . '-desktop';
			}

			if ( $options['masonrycols'] == 2 ) {
				$classes_to_add .= ' masonry-2-desktop';
			}

			if ( $options['masonrymobilecols'] == 2 ) {
				$classes_to_add .= ' masonry-2-mobile';
			}

		}

		$this->feed_options['class'] .= $classes_to_add;
	}

	/**
	 *  Adds classes to the main #ctf element to be used to create the carousel
	 *  layout for feeds
	 */
	private function setCarouselClasses()
	{
		$this->feed_options['class'] .= ' ctf-carousel';
	}

	/**
	 * used to get twitter card data from the database and store it in member data
	 */
	private function setExistingTwitterCardData()
	{
		$this->db_twitter_cards = get_option( 'ctf_twitter_cards' );
	}

	/**
	 * sets the transient name for the caching system
	 */
	public function setTransientName()
	{
		$this->transient_name = 'ctf____'.$this->last_id_data;

		$last_id_data = substr( $this->last_id_data, -5, 5 );
		$num = isset( $this->feed_options['num'] ) ? $this->feed_options['num'] : '';
		$reply = $this->feed_options['includereplies'] === true ? 'r' : '';
		$includewords = ! empty( $this->feed_options['includewords'] ) ? substr( str_replace( array( ',', ' ' ), '', $this->feed_options['includewords'] ), 0, 10 ) : '';
		$excludewords = ! empty( $this->feed_options['excludewords'] ) ? substr( str_replace( array( ',', ' ' ), '', $this->feed_options['excludewords'] ), 0, 5 ) : '';
		$noretweets = ! $this->feed_options['includeretweets'] ? 'n' : '' ;
		$remove_by_id_array = explode( ',', str_replace( ' ', '', $this->feed_options['remove_by_id'] ) );
		$remove_by_id_str = '';

		if ( ! empty( $remove_by_id_array ) ) {
			foreach ( $remove_by_id_array as $id ) {
				$remove_by_id_str .= substr( $id, -3, 3 );
			}
		}

		switch ( $this->feed_options['type'] ) {
			case 'hometimeline':
				$this->transient_name = 'ctf_' . $last_id_data . 'home'. $num . $reply . $includewords . $remove_by_id_str . $excludewords . $noretweets;
				break;
			case 'usertimeline':
				$screenname = isset( $this->feed_options['screenname'] ) ? $this->feed_options['screenname'] : '';
				$this->transient_name = substr( 'ctf__' . $last_id_data . $screenname . $num . $reply . $includewords . $remove_by_id_str . $excludewords. $noretweets, 0, 45 );
				break;
			case 'search':
				$hashtag = isset( $this->feed_options['feed_term'] ) ? $this->feed_options['feed_term'] : '';
				$this->transient_name = substr( 'ctf_' . $last_id_data . substr( $hashtag, 0, 20 ) . $includewords . $num . $reply . $remove_by_id_str . $excludewords . $noretweets, 0, 45 );
				break;
			case 'hashtag':
				$hashtag = isset( $this->feed_options['feed_term'] ) ? $this->feed_options['feed_term'] : '';
				$this->transient_name = substr( 'ctf_' . $last_id_data . substr( $hashtag, 0, 20 ) . $includewords . $num . $reply . $remove_by_id_str . $excludewords . $noretweets, 0, 45 );
				break;
			case 'mentionstimeline':
				$this->transient_name = 'ctf_' . $last_id_data . 'mentions'. $num . $includewords . $remove_by_id_str . $excludewords . $noretweets;
				break;
			case 'lists':
				$list = isset( $this->feed_options['feed_term'] ) ? $this->feed_options['feed_term'] : '';
				$this->transient_name = substr( 'ctf_' . $last_id_data . $list . $num . $reply . $includewords . $remove_by_id_str . $excludewords, 0, 45 );
				break;
			default:
				if ( ! empty ( $this->feed_options['feed_types_and_terms'] ) ) {
					$names = $this->feed_options['feed_types_and_terms'];
					$working_name = '';
					foreach ( $names as $name ) {
						$working_name .= substr( $name[1], 0, 3 );
					}
					$this->transient_name = substr( 'ctf__' . $last_id_data . $working_name . $num . $reply . $includewords . $remove_by_id_str . $excludewords . $noretweets, 0, 45 );
					break;
				}

		}

	}

	/**
	 * will use the cached data in the feed if data seems to be valid and user
	 * wants to use caching
	 *
	 * @return bool|mixed   false if none is set, tweet set otherwise
	 */
	public function maybeSetTweetsFromCache()
	{
		if ( $this->feed_options['persistentcache'] && ( $this->feed_options['type'] == 'search' || $this->feed_options['type'] == 'hashtag' ) ) {
			$persistent_cache_tweets = $this->persistentCacheTweets();
			if ( is_array( $persistent_cache_tweets ) ) {
				$this->transient_data = array_slice( $persistent_cache_tweets, ( $this->persistent_index - $this->feed_options['num'] - 1 ) , $this->persistent_index );
			} else {
				$this->transient_data = $persistent_cache_tweets;
			}
		} else {
			$this->transient_data = get_transient( $this->transient_name );
			if ( ! is_array( $this->transient_data ) ) {
				$this->transient_data = json_decode( $this->transient_data, $assoc = true );
			}

			if ( $this->feed_options['cache_time'] <= 0 ) {
				return $this->tweet_set = false;
			}
		}
		// validate the transient data
		if ( $this->transient_data ) {
			$this->errors['cache_status'] = $this->validateCache();
			if ( $this->errors['cache_status'] === false ) {
				return $this->tweet_set = $this->transient_data;
			} else {
				return $this->tweet_set = false;
			}
		} else {
			$this->errors['cache_status'] = 'none found';
			return $this->tweet_set = false;
		}
	}

	private function reduceTweetSetData( $tweet_set, $limit = true ) {
		if ( $this->hasTweetTextFilter() || ! $this->feed_options['includereplies'] ) {
			$tweet_set = $this->filterTweetSet( $tweet_set, $limit );
		}

		if ( $this->check_for_duplicates ) {
			$tweet_set = $this->removeDuplicates( $tweet_set, $limit );
		}

		$this->tweet_set = $tweet_set;
		if ( isset( $tweet_set[0]['created_at'] ) ) {
			$this->trimTweetData( false );
			return $this->tweet_set;
		} else {
			return false;
		}
	}

	private function appendPersistentCacheTweets( $existing_cache, $tweet_set )
	{
		if ( is_array( $tweet_set ) ) {
			$working_tweet_set = array_merge( $tweet_set, $existing_cache );
		} else {
			$working_tweet_set = $existing_cache;
		}

		$working_tweet_set = array_slice( $working_tweet_set, 0, 150 );

		return $working_tweet_set;
	}

	private function persistentCacheTweets()
	{
		// if cache exists get cached data
		$includewords = ! empty( $this->feed_options['includewords'] ) ? substr( str_replace( array( ',', ' ' ), '', $this->feed_options['includewords'] ), 0, 10 ) : '';
		$excludewords = ! empty( $this->feed_options['excludewords'] ) ? substr( str_replace( array( ',', ' ' ), '', $this->feed_options['excludewords'] ), 0, 5 ) : '';
		$cache_name = substr( 'ctf_!_' . $this->feed_options['feed_term'] . $includewords . $excludewords, 0, 45 );
		$cache_time_limit_reached = get_transient( $cache_name ) ? false : true;

		$existing_cache = get_option( $cache_name, false );

		if ( $existing_cache && ! is_array( $existing_cache ) ) {
			$existing_cache = json_decode( $existing_cache, $assoc = true );
		}

		$this->persistent_index = (int)$this->persistent_index + (int)$this->feed_options['num'];

		$this->feed_options['count'] = 200;

		if ( ! empty( $this->last_id_data ) || ( ! $cache_time_limit_reached && $existing_cache ) ) {
			return $existing_cache;
		} elseif ( $existing_cache ) {
			// use "since-id" to look for more in an api request
			$since_id = $existing_cache[0]['id_str'];
			$api_obj = $this->getTweetsSinceID( $since_id, 'search', $this->feed_options['feed_term'], $this->feed_options['count'] );
			// add any new tweets to the cache
			$tweet_set = json_decode( $api_obj->json , $assoc = true );

			$tweet_set = isset( $tweet_set['statuses'] ) ? $tweet_set['statuses'] : array();

			// add a transient to delay another api retrieval
			set_transient( $cache_name, true, $this->feed_options['cache_time'] );

			if ( empty( $tweet_set ) ) {
				$existing_cache = $this->filterTweetSet( $existing_cache, false );
				$cache_set = json_encode( $existing_cache );

				update_option( $cache_name, $cache_set );
				return $existing_cache;
			} else {
				$tweet_set = $this->reduceTweetSetData( $tweet_set, false );
			}

			$tweet_set = $this->appendPersistentCacheTweets( $existing_cache, $tweet_set );
			$tweet_set = $this->filterTweetSet( $tweet_set, false );

			$cache_set = json_encode( $tweet_set );

			update_option( $cache_name, $cache_set );

			return $tweet_set;
			// else if cached data doesn't exist
		} else {
			// make a request for last 200 tweets
			$api_obj = $this->apiConnectionResponse( 'search', $this->feed_options['feed_term'] );
			// cache them in a regular option
			$this->tweet_set = json_decode( $api_obj->json , $assoc = true );
			// check for errors/tweets present
			if ( isset( $this->tweet_set['errors'][0] ) ) {
				if ( empty( $this->api_obj ) ) {
					$this->api_obj = new stdClass();
				}
				$this->api_obj->api_error_no = $this->tweet_set['errors'][0]['code'];
				$this->api_obj->api_error_message = $this->tweet_set['errors'][0]['message'];

				$this->tweet_set = false;
			}

			$tweets = isset( $this->tweet_set['statuses'] ) ? $this->tweet_set['statuses'] : $this->tweet_set;

			if ( empty( $tweets ) ) {
				$this->errors['error_message'] = 'No Tweets returned';
				$this->tweet_set = false;
			} else {
				$this->tweet_set = $this->reduceTweetSetData( $tweets, false );
			}

			if ( $this->tweet_set ) {
				$tweet_set = json_encode( $this->tweet_set );
				// create a new persistent cache
				update_option( $cache_name, $tweet_set );

				// update list of persistent cache
				$cache_list = get_option( 'ctf_cache_list', array() );

				$cache_list[] = $cache_name;

				update_option( 'ctf_cache_list', $cache_list );
			}

			return $this->tweet_set;
		}

		// add the search parameter to another option that contains a list of all persistent caches available
	}

	/**
	 * a check to see if any of the filtering options for the feed are set
	 *
	 * @return bool whether or not a filter is used for this feed
	 */
	private function hasTweetTextFilter()
	{
		if ( ! empty( $this->feed_options['includewords'] ) || ! empty( $this->feed_options['excludewords'] ) ) {
			return true;
		} elseif ( ! empty( $this->feed_options['remove_by_id'] ) || ! $this->feed_options['includeretweets'] ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * if a filter is applied to this feed, check and see if this tweet needs to
	 * or contains the includewords text
	 *
	 * @param $good_text array      words the tweet needs to be included in the feed
	 * @param $any_or_all enum      whether any or all of the goot text words need to be included
	 * @param $tweet_text string    content text of the tweet
	 * @param $default bool         the default return type if nothing is set
	 * @return bool                 whether the tweet meets the requirements for having good text
	 */
	private function hasGoodText( $good_text, $any_or_all, $tweet_text, $default )
	{
		if ( empty( $good_text ) ) { // don't factor in the includewords if there aren't any
			return $default;
		} else {
			$encoded_text = ' ' . str_replace( array( '+', '%0A' ), ' ',  urlencode( str_replace( array( '#', '@' ), array( ' HASHTAG', ' MENTION' ), strtolower( $tweet_text ) ) ) ) . ' ';

			if ( $any_or_all == 'any' ) {
				// as soon as we find any of the includewords, stop searching and return true
				foreach ( $good_text as $good ) {
					$converted_includeword = trim( str_replace('+', ' ', urlencode( str_replace( array( '#', '@' ), array( ' HASHTAG', ' MENTION' ), strtolower( $good ) ) ) ) );

					if ( preg_match('/\b'.$converted_includeword.'\b/i', $encoded_text, $matches ) ) {
						return true;
					}
				}
				// if foreach finishes without finding any matches
				return false;
			} else {
				// to make sure all of the includewords are present, keep a count of
				// how many of the words are detected and compare it to the number that's needed
				$good_text_matches = 0;
				$number_of_good_text_to_look_for = count( $good_text );
				foreach ( $good_text as $good ) {
					$converted_includeword = trim( str_replace('+', ' ', urlencode( str_replace( array( '#', '@' ), array( ' HASHTAG', ' MENTION' ), strtolower( $good ) ) ) ) );

					if ( preg_match('/\b'.$converted_includeword.'\b/i', $encoded_text, $matches ) ) {
						$good_text_matches++;
					}

				}
				if ( $good_text_matches >= $number_of_good_text_to_look_for ) {
					return true;
				} else {
					return false;
				}
			}
		}

	}

	/**
	 * if a filter is applied to this feed, check and see if this tweet needs to
	 * or contains the excludewords text
	 *
	 * @param $bad_text array       words the tweet cannot have to be included in the feed
	 * @param $any_or_all enum      whether any or all of the bad text words need to be included
	 * @param $tweet_text string    content text of the tweet
	 * @param $default bool         the default return type if nothing is set
	 * @return bool                 whether the tweet meets the requirements for having no bad text
	 */
	private function hasNoBadText( $bad_text, $any_or_all, $tweet_text, $default )
	{
		if ( empty( $bad_text ) ) { // don't factor in the excludewords if there aren't any
			return $default;
		} else {
			$encoded_text = ' ' . str_replace( array( '+', '%0A' ), ' ',  urlencode( str_replace( array( '#', '@' ), array( ' HASHTAG', ' MENTION' ), strtolower( $tweet_text ) ) ) ) . ' ';

			if ( $any_or_all == 'any' ) {
				// as soon as we find any of the excludewords, stop searching and return false
				foreach ( $bad_text as $bad ) {
					if ( empty( $bad ) ) {
						return true;
					}
					$converted_excludeword = trim( str_replace('+', ' ', urlencode( str_replace( array( '#', '@' ), array( ' HASHTAG', ' MENTION' ), strtolower( $bad ) ) ) ) );
					if ( preg_match('/\b'.$converted_excludeword.'\b/i', $encoded_text, $matches ) ) {
						return false;
					}
				}
				// if foreach finishes without finding any matches
				return true;
			} else {
				// under this circumstance, all excludewords need to be present to remove
				// the tweet so a count is kept and compared to the number of words
				$bad_text_matches = 0;
				$number_of_bad_text_to_look_for = count( $bad_text );
				foreach ( $bad_text as $bad ) {
					$converted_excludeword = trim( str_replace('+', ' ', urlencode( str_replace( '#', 'HASHTAG', strtolower( $bad ) ) ) ) );

					if ( preg_match('/\b'.$converted_excludeword.'\b/i', $encoded_text, $matches ) ) {
						$bad_text_matches++;
					}

				}
				if ( $bad_text_matches >= $number_of_bad_text_to_look_for ) {
					return false;
				} else {
					return true;
				}
			}
		}

	}

	/**
	 * this handles the removal of the tweet based on the feed options and filtering settings
	 *
	 * @param $tweet array  data from the Twitter API associated with this tweet
	 * @return bool         whether or not the tweet should be removed from the availalble tweets for the feed
	 */
	private function tweetShouldBeRemoved( $tweet )
	{
		$return = false;
		$good_text = ! empty( $this->feed_options['includewords'] ) ? explode( ',', str_replace( ' ', '', $this->feed_options['includewords'] ) ) : '';
		$bad_text = ! empty( $this->feed_options['excludewords'] ) ? explode( ',', str_replace( ' ', '', $this->feed_options['excludewords'] ) ) : '';
		$includewords_any_all = $this->feed_options['includeanyall'];
		$excludewords_any_all = $this->feed_options['excludeanyall'];
		$filter_and_or = $this->feed_options['filterandor'];
		if ( isset( $tweet['retweeted_status']['full_text'] ) ) {
			$tweet_text = $tweet['retweeted_status']['full_text'];
		} elseif ( isset( $tweet['retweeted_status']['text'] ) ) {
			$tweet_text = $tweet['retweeted_status']['text'];
		} elseif ( isset( $tweet['full_text'] ) ) {
			$tweet_text = $tweet['full_text'];
		} elseif ( isset( $tweet['text'] ) ) {
			$tweet_text = $tweet['text'];
		} else {
			$tweet_text = '';
		}
		$tweet_text = ' ' . preg_replace( '/[,.!?:;"]+/', '', $tweet_text )  . ' '; // spaces added so that we can use strpos instead of regex to find words
		$tweet_text = strtolower( preg_replace( '/[\n]+/', ' ', $tweet_text ) );
		// don't bother with filtering process if both filters are empty
		if ( ! empty( $good_text ) || ! empty( $bad_text ) ) {
			if ( $filter_and_or == 'and' && ! empty( $good_text ) && ! empty( $bad_text ) ) {
				if ( $this->hasGoodText( $good_text, $includewords_any_all, $tweet_text, true ) && $this->hasNoBadText( $bad_text, 'any', $tweet_text, true ) ) {
					$return = false;
				} else {
					$return = true;
				}
			} else {
				if ( $this->hasGoodText( $good_text, $includewords_any_all, $tweet_text, false ) || $this->hasNoBadText( $bad_text, $excludewords_any_all, $tweet_text, false ) ) {
					$return = false;
				} else {
					$return = true;
				}
			}
		}

		$return = apply_filters( 'ctf_filter_out_tweet', $return, $tweet_text, $tweet  );

		return $return;
	}

	/**
	 * this takes the current set of tweets and processes them until there are
	 * enough filtered tweets to create the feed from
	 */
	private function filterTweetSet( $tweet_set, $limit = true )
	{
		$working_tweet_set = isset( $tweet_set['statuses'] ) ? $tweet_set['statuses'] : $tweet_set;
		$usable_tweets = 0;
		if ( $limit ) {
			$tweets_needed = $this->feed_options['count'] + 1; // magic number here should be ADT
		} else {
			$tweets_needed = 200;
		}
		$tweets_to_remove_strip_ctf = str_replace( 'ctf_', '', $this->feed_options['remove_by_id'] );
		$ids_of_tweets_to_remove = ! empty( $tweets_to_remove_strip_ctf ) ? explode( ',', str_replace( ' ', '', $tweets_to_remove_strip_ctf ) ) : '';
		$i = 0; // index of working_tweet_set
		$still_setting_filtered_tweets = true;

		while ( $still_setting_filtered_tweets ) { // stays true until the number to display is reached or out of tweets
			if ( isset ( $working_tweet_set[$i] ) ) { // if there is another tweet available
				$retweet_id = isset( $working_tweet_set[$i]['retweeted_status']['id_str'] ) ? $working_tweet_set[$i]['retweeted_status']['id_str'] : '';
				if ( ! empty( $retweet_id ) && ! $this->feed_options['includeretweets'] ) {
					unset( $working_tweet_set[ $i ] );
				} elseif ( !$this->feed_options['includereplies'] && !$this->feed_options['selfreplies'] && isset( $working_tweet_set[$i]['in_reply_to_screen_name'] ) ) {
					unset( $working_tweet_set[$i] );
				} elseif ( !$this->feed_options['includereplies']
				           && $this->feed_options['selfreplies']
				           && isset( $working_tweet_set[$i]['in_reply_to_screen_name'] )
				           && $working_tweet_set[$i]['in_reply_to_screen_name'] !== $working_tweet_set[$i]['user']['screen_name']) {
					unset( $working_tweet_set[$i] );
				} elseif ( ! empty( $ids_of_tweets_to_remove ) && in_array( $working_tweet_set[$i]['id_str'], $ids_of_tweets_to_remove ) ) {
					unset( $working_tweet_set[$i] );
				} elseif ( $this->tweetShouldBeRemoved( $working_tweet_set[$i] ) ) {
					unset( $working_tweet_set[$i] );
				} else {
					$usable_tweets++;
				}
			} else {
				$still_setting_filtered_tweets = false;
			}

			// if there are no more tweets needed
			if ( $usable_tweets >= $tweets_needed ) {
				$still_setting_filtered_tweets = false;
			} else {
				$i++;
			}

		}

		if ( is_array( $working_tweet_set ) ) {
			return array_values( $working_tweet_set );
		} else {
			return false;
		}
	}

	/**
	 * used to compare two tweets created date for sorting combined feeds
	 *
	 * @param $a array  a tweet data set
	 * @param $b array  another tweet data set
	 * @return int      represents which has a greater value
	 */
	private static function compareDateCreatedAt( $a, $b ) {
		return strtotime( $b['created_at'] ) - strtotime( $a['created_at'] );
	}

	/**
	 * used to sort merged tweets that are likely not in chronological order
	 *
	 * @param $tweet_set array  all tweets
	 * @return mixed array      sorted tweets
	 */
	private function sortTweetSetByDate( $tweet_set ) {
		usort( $tweet_set, array( $this, 'compareDateCreatedAt' ) );
		return $tweet_set;
	}

	private function removeDuplicates( $tweet_set, $limit = true )
	{
		$tweet_set = isset( $tweet_set['statuses'] ) ? $tweet_set['statuses'] : $tweet_set;
		$usable_tweets = 0;
		if ( $limit ) {
			$tweets_needed = $this->feed_options['count'] + 1; // magic number here should be ADT
		} else {
			$tweets_needed = 200;
		}
		$ids_of_tweets_to_remove = array();

		$i = 0; // index of tweet_set
		$still_setting_filtered_tweets = true;
		while ( $still_setting_filtered_tweets ) { // stays true until the number to display is reached or out of tweets
			if ( isset( $tweet_set[$i]['retweeted_status']['id_str'] ) ) {
				unset( $tweet_set[$i] );
			} elseif ( isset( $tweet_set[$i] ) ) {
				$id = isset( $tweet_set[$i]['retweeted_status']['id_str'] ) ? $tweet_set[$i]['retweeted_status']['id_str'] : $tweet_set[$i]['id_str'];
				if ( in_array( $id, $ids_of_tweets_to_remove ) ) {
					unset( $tweet_set[$i] );
				} else {
					$usable_tweets++;
					$ids_of_tweets_to_remove[] = $id;
				}
			} else {
				$still_setting_filtered_tweets = false;
			}

			// if there are no more tweets needed
			if ( $usable_tweets >= $tweets_needed ) {
				$still_setting_filtered_tweets = false;
			} else {
				$i++;
			}

		}

		return array_values( $tweet_set );
	}

	private function getTweetsSinceID( $since_id, $end_point = 'search', $feed_term, $count )
	{
		// Only can be set in the options page
		$request_settings = array(
			'consumer_key' => $this->feed_options['consumer_key'],
			'consumer_secret' => $this->feed_options['consumer_secret'],
			'access_token' => $this->feed_options['access_token'],
			'access_token_secret' => $this->feed_options['access_token_secret'],
		);

		$get_fields = $this->setGetFieldsArray( $end_point, $feed_term );

		$get_fields['since_id'] = $since_id;

		$get_fields['count'] = $count;

		include_once( CTF_URL . '/inc/CtfOauthConnect.php' );
		include_once( CTF_URL . '/inc/CtfOauthConnectPro.php' );

		// actual connection
		$twitter_connect = new CtfOauthConnectPro( $request_settings, $end_point );
		$twitter_connect->setUrlBase();
		$twitter_connect->setGetFields( $get_fields );
		$twitter_connect->setRequestMethod( $this->feed_options['request_method'] );

		return $twitter_connect->performRequest();
	}

	/**
	 *  will attempt to connect to the api to retrieve current tweets
	 */
	public function maybeSetTweetsFromTwitter()
	{
		$this->setTweetsToRetrieve();

		if ( empty ( $this->feed_options['feed_types_and_terms'] ) || ! isset( $this->feed_options['feed_types_and_terms'] ) ) {
			$feed_term = isset( $this->feed_options['feed_term'] ) ? $this->feed_options['feed_term'] : '';
			$api_obj = $this->apiConnectionResponse( $this->feed_options['type'], $feed_term );

			$this->tweet_set = json_decode( $api_obj->json , $assoc = true );
		} else {
			$working_tweet_set = array();
			foreach ( $this->feed_options['feed_types_and_terms'] as $feed_type_and_term ) {
				$api_obj = $this->apiConnectionResponse( $feed_type_and_term[0], $feed_type_and_term[1] );
				$tweet_set_to_merge = json_decode( $api_obj->json , $assoc = true );

				if ( isset( $tweet_set_to_merge['statuses'] ) ) {
					$working_tweet_set = array_merge( $working_tweet_set, $tweet_set_to_merge['statuses'] );
				} elseif ( isset( $tweet_set_to_merge[0]['created_at'] ) ) {
					$working_tweet_set = array_merge( $working_tweet_set, $tweet_set_to_merge );
				}
			}

			$this->tweet_set = $this->sortTweetSetByDate( $working_tweet_set );
		}

		// check for errors/tweets present
		if ( isset( $this->tweet_set['errors'][0] ) ) {
			if ( empty( $this->api_obj ) ) {
				$this->api_obj = new stdClass();
			}
			$this->api_obj->api_error_no = $this->tweet_set['errors'][0]['code'];
			$this->api_obj->api_error_message = $this->tweet_set['errors'][0]['message'];

			$this->tweet_set = false;
		}

		$tweets = isset( $this->tweet_set['statuses'] ) ? $this->tweet_set['statuses'] : $this->tweet_set;

		if ( empty( $tweets ) ) {
			$this->errors['error_message'] = 'No Tweets returned';
			$this->tweet_set = false;
		} else {
			$this->tweet_set = $this->reduceTweetSetData( $tweets );
		}
	}

	/**
	 * sets the relevant get fields for this specific feed type and term
	 *
	 * @param $end_point string     api endpoint to use for this term
	 * @param $feed_term string     term to use for api parameter
	 * @return array                get fields for this request
	 */
	protected function setGetFieldsArray( $end_point, $feed_term )
	{
		$feed_type = $end_point;

		$get_fields = array();

		$get_fields['tweet_mode'] = 'extended';

		if ( $feed_type === 'usertimeline' ) {
			if ( ! empty ( $feed_term  ) ) {
				$get_fields['screen_name'] = $feed_term;
			}
			if ( $this->feed_options['includereplies'] || $this->feed_options['selfreplies'] ) {
				$get_fields['exclude_replies'] = 'false';
			} else {
				$get_fields['exclude_replies'] = 'true';
			}
		}

		if ( $feed_type === 'hometimeline' ) {
			if ( $this->feed_options['includereplies'] || $this->feed_options['selfreplies'] ) {
				$get_fields['exclude_replies'] = 'false';
			} else {
				$get_fields['exclude_replies'] = 'true';
			}
		}

		if ( $feed_type === 'search' || $feed_type === 'hashtag' ) {
			$get_fields['q'] = $feed_term;
		}

		if ( $feed_type === 'lists' ) {
			if ( ! empty ( $feed_term  ) ) {
				$get_fields['list_id'] = $feed_term;
			}
		}

		return $get_fields;
	}

	/**
	 * attempts to connect and retrieve tweets from the Twitter api
	 *
	 * @return mixed|string object containing the response
	 */
	public function apiConnectionResponse( $end_point, $feed_term )
	{
		// Only can be set in the options page
		$request_settings = array(
			'consumer_key' => $this->feed_options['consumer_key'],
			'consumer_secret' => $this->feed_options['consumer_secret'],
			'access_token' => $this->feed_options['access_token'],
			'access_token_secret' => $this->feed_options['access_token_secret'],
		);

		// For pagination, an extra post needs to be retrieved since the last post is
		// included in the next set
		$count = $this->feed_options['count'];

		$get_fields = $this->setGetFieldsArray( $end_point, $feed_term );

		if ( ! empty( $this->last_id_data ) ) {
			$count++;
			$max_id = $this->last_id_data;
		}
		$get_fields['count'] = $count;

		// max_id parameter should only be included for the second set of posts
		if ( isset( $max_id ) ) {
			$get_fields['max_id'] = $max_id;
		}

		include_once( CTF_URL . '/inc/CtfOauthConnect.php' );
		include_once( CTF_URL . '/inc/CtfOauthConnectPro.php' );

		// actual connection
		$twitter_connect = new CtfOauthConnectPro( $request_settings, $end_point );
		$twitter_connect->setUrlBase();
		$twitter_connect->setGetFields( $get_fields );
		$twitter_connect->setRequestMethod( $this->feed_options['request_method'] );

		return $twitter_connect->performRequest();
	}

	protected function removeStringFromText( $string, $text, $expanded_url = '' ) {
		$exceptions = array( '://fb.me/' );

		if ( $expanded_url !== '' ) {

			foreach ( $exceptions as $exception ) {

				if ( strpos( $expanded_url, $exception ) !== false ) {
					return str_replace( $string, $expanded_url, $text );
				}

			}

		}

		return str_replace( $string, '', $text );
	}

	/**
	 * captures additional data for "Pro" features
	 *
	 * @param $trimmed array    current set of trimmed tweets
	 * @param $tweet array      raw tweet data from api
	 * @return array
	 */
	protected function filterTrimmedTweets( $trimmed, $tweet )
	{
		if ( isset( $tweet['in_reply_to_screen_name'] ) ) {
			$trimmed['in_reply_to_screen_name'] = $tweet['in_reply_to_screen_name'];
			$trimmed['entities']['user_mentions'][0]['name'] = isset( $tweet['entities']['user_mentions'][0]['name'] ) ? $tweet['entities']['user_mentions'][0]['name'] : '';
			$trimmed['in_reply_to_status_id_str'] = $tweet['in_reply_to_status_id_str'];
		}

		if ( isset( $tweet['extended_entities']['media'] ) ) {
			// if there is media, we need to remove the media url from the tweet text
			$text = isset( $tweet['full_text'] ) ? $tweet['full_text'] : $tweet['text'];
			if ( isset( $tweet['extended_entities']['media'][0]['url'] ) ) {
				$trimmed['text'] = $this->removeStringFromText( $tweet['extended_entities']['media'][0]['url'], $text );
			}
			$num_media = count( $tweet['extended_entities']['media'] );
			for ( $i = 0; $i < $num_media; $i++ ) {
				$trimmed['extended_entities']['media'][$i]['media_url_https'] = $tweet['extended_entities']['media'][$i]['media_url_https'];
				$trimmed['extended_entities']['media'][$i]['type'] = $tweet['extended_entities']['media'][$i]['type'];
				if ( isset( $tweet['extended_entities']['media'][$i]['sizes'] ) ) {
					$trimmed['extended_entities']['media'][$i]['sizes'] = $tweet['extended_entities']['media'][$i]['sizes'];
				}
				if ( $tweet['extended_entities']['media'][$i]['type'] == 'video' || $tweet['extended_entities']['media'][$i]['type'] == 'animated_gif' ) {
					foreach ( $tweet['extended_entities']['media'][$i]['video_info']['variants'] as $variant ) {
						if ( isset( $variant['content_type'] ) && $variant['content_type'] == 'video/mp4' ) {
							$trimmed['extended_entities']['media'][$i]['video_info']['variants'][$i]['url'] = $variant['url'];
						}
					}
					if ( ! isset( $trimmed['extended_entities']['media'][$i]['video_info']['variants'][$i]['url'] ) ) {
						$trimmed['extended_entities']['media'][$i]['video_info']['variants'][$i]['url'] = $tweet['extended_entities']['media'][$i]['video_info']['variants'][0]['url'];
					}
				}
			}

		} elseif ( isset( $tweet['entities']['media'] ) ) {
			// if there is media, we need to remove the media url from the tweet text
			$text = isset( $tweet['full_text'] ) ? $tweet['full_text'] : $tweet['text'];
			if ( isset( $tweet['entities']['media'][0]['url'] ) ) {
				$trimmed['text'] = $this->removeStringFromText( $tweet['entities']['media'][0]['url'], $text );
			}

			$num_media = count( $tweet['entities']['media'] );
			for ( $i = 0; $i < $num_media; $i++ ) {
				$trimmed['entities']['media'][$i]['media_url_https'] = $tweet['entities']['media'][$i]['media_url_https'];
				$trimmed['entities']['media'][$i]['type'] = $tweet['entities']['media'][$i]['type'];
				if ( isset( $tweet['entities']['media'][$i]['sizes'] ) ) {
					$trimmed['entities']['media'][$i]['sizes'] = $tweet['entities']['media'][$i]['sizes'];
				}
				if ( $tweet['entities']['media'][$i]['type'] == 'video' || $tweet['entities']['media'][$i]['type'] == 'animated_gif' ) {
					foreach ( $tweet['entities']['media'][$i]['video_info']['variants'] as $variant ) {
						if ( isset( $variant['content_type'] ) && $variant['content_type'] == 'video/mp4' ) {
							$trimmed['entities']['media'][$i]['video_info']['variants'][$i]['url'] = $variant['url'];
						}
					}
					if ( ! isset( $trimmed['entities']['media'][$i]['video_info']['variants'][$i]['url'] ) ) {
						$trimmed['entities']['media'][$i]['video_info']['variants'][$i]['url'] = $tweet['entities']['media'][$i]['video_info']['variants'][0]['url'];
					}
				}
			}

		}

		if ( isset( $tweet['retweeted_status']['extended_entities']['media'] ) ) {
			// if there is media, we need to remove the media url from the tweet text
			$retweeted_text = isset( $tweet['retweeted_status']['full_text'] ) ? $tweet['retweeted_status']['full_text'] : $tweet['retweeted_status']['text'];
			if ( isset( $tweet['retweeted_status']['extended_entities']['media'][0]['url'] ) ) {
				$trimmed['retweeted_status']['text'] = $this->removeStringFromText( $tweet['retweeted_status']['extended_entities']['media'][0]['url'], $retweeted_text );
			}

			$num_media = count( $tweet['retweeted_status']['extended_entities']['media'] );
			for ( $i = 0; $i < $num_media; $i++ ) {
				$trimmed['retweeted_status']['extended_entities']['media'][$i]['media_url_https'] = $tweet['retweeted_status']['extended_entities']['media'][$i]['media_url_https'];
				$trimmed['retweeted_status']['extended_entities']['media'][$i]['type'] = $tweet['retweeted_status']['extended_entities']['media'][$i]['type'];
				if ( isset( $tweet['retweeted_status']['extended_entities']['media'][$i]['sizes'] ) ) {
					$trimmed['retweeted_status']['extended_entities']['media'][$i]['sizes'] = $tweet['retweeted_status']['extended_entities']['media'][$i]['sizes'];
				}
				if ( $tweet['retweeted_status']['extended_entities']['media'][$i]['type'] == 'video' || $tweet['retweeted_status']['extended_entities']['media'][$i]['type'] == 'animated_gif' ) {
					foreach ( $tweet['retweeted_status']['extended_entities']['media'][$i]['video_info']['variants'] as $variant ) {
						if ( isset( $variant['content_type'] ) && $variant['content_type'] == 'video/mp4' ) {
							$trimmed['retweeted_status']['extended_entities']['media'][$i]['video_info']['variants'][$i]['url'] = $variant['url'];
						}
					}
					if ( ! isset( $trimmed['retweeted_status']['extended_entities']['media'][$i]['video_info']['variants'][$i]['url'] ) ) {
						$trimmed['retweeted_status']['extended_entities']['media'][$i]['video_info']['variants'][$i]['url'] = $tweet['retweeted_status']['extended_entities']['media'][$i]['video_info']['variants'][0]['url'];
					}
				}
			}

		} elseif ( isset( $tweet['retweeted_status']['entities']['media'] ) ) {
			// if there is media, we need to remove the media url from the tweet text
			$retweeted_text = isset( $tweet['retweeted_status']['full_text'] ) ? $tweet['retweeted_status']['full_text'] : $tweet['retweeted_status']['text'];
			if ( isset( $tweet['retweeted_status']['entities']['media'][0]['url'] ) ) {
				$trimmed['retweeted_status']['text'] = $this->removeStringFromText( $tweet['retweeted_status']['entities']['media'][0]['url'], $retweeted_text );
			}

			$num_media = count( $tweet['retweeted_status']['entities']['media'] );
			for( $i = 0; $i < $num_media; $i++ ) {
				$trimmed['retweeted_status']['entities']['media'][$i]['media_url_https'] = $tweet['retweeted_status']['entities']['media'][$i]['media_url_https'];
				$trimmed['retweeted_status']['entities']['media'][$i]['type'] = $tweet['retweeted_status']['entities']['media'][$i]['type'];
				if ( isset( $tweet['retweeted_status']['entities']['media'][$i]['sizes'] ) ) {
					$trimmed['retweeted_status']['entities']['media'][$i]['sizes'] = $tweet['retweeted_status']['entities']['media'][$i]['sizes'];
				}
				if ( $tweet['retweeted_status']['entities']['media'][$i]['type'] == 'video' || $tweet['retweeted_status']['entities']['media'][$i]['type'] == 'animated_gif' ) {
					foreach ( $tweet['retweeted_status']['entities']['media'][$i]['video_info']['variants'] as $variant ) {
						if ( isset( $variant['content_type'] ) && $variant['content_type'] == 'video/mp4' ) {
							$trimmed['retweeted_status']['entities']['media'][$i]['video_info']['variants'][$i]['url'] = $variant['url'];
						}
					}
					if ( ! isset( $trimmed['retweeted_status']['entities']['media'][$i]['video_info']['variants'][$i]['url'] ) ) {
						$trimmed['retweeted_status']['entities']['media'][$i]['video_info']['variants'][$i]['url'] = $tweet['retweeted_status']['entities']['media'][$i]['video_info']['variants'][0]['url'];
					}
				}
			}

		} elseif ( isset( $tweet['quoted_status']['extended_entities']['media'] ) ) {
			// if there is media, we need to remove the media url from the tweet text
			$quoted_text = isset( $tweet['quoted_status']['full_text'] ) ? $tweet['quoted_status']['full_text'] : $tweet['quoted_status']['text'];
			if ( isset( $tweet['quoted_status']['extended_entities']['media'][0]['url'] ) ) {
				$trimmed['quoted_status']['text'] = $this->removeStringFromText( $tweet['quoted_status']['extended_entities']['media'][0]['url'], $quoted_text );
			}

			$num_media = count( $tweet['quoted_status']['extended_entities']['media'] );
			for( $i = 0; $i < $num_media; $i++ ) {
				$trimmed['quoted_status']['extended_entities']['media'][$i]['media_url_https'] = $tweet['quoted_status']['extended_entities']['media'][$i]['media_url_https'];
				$trimmed['quoted_status']['extended_entities']['media'][$i]['type'] = $tweet['quoted_status']['extended_entities']['media'][$i]['type'];
				if ( $tweet['quoted_status']['extended_entities']['media'][$i]['type'] == 'video' || $tweet['quoted_status']['extended_entities']['media'][$i]['type'] == 'animated_gif' ) {
					foreach ( $tweet['quoted_status']['extended_entities']['media'][$i]['video_info']['variants'] as $variant ) {
						if ( isset( $variant['content_type'] ) && $variant['content_type'] == 'video/mp4' ) {
							$trimmed['quoted_status']['extended_entities']['media'][$i]['video_info']['variants'][$i]['url'] = $variant['url'];
						}
					}
					if ( ! isset( $trimmed['quoted_status']['extended_entities']['media'][$i]['video_info']['variants'][$i]['url'] ) ) {
						$trimmed['quoted_status']['extended_entities']['media'][$i]['video_info']['variants'][$i]['url'] = $tweet['quoted_status']['extended_entities']['media'][$i]['video_info']['variants'][0]['url'];
					}
				}
			}

		} elseif ( isset( $tweet['quoted_status']['entities']['media'] ) ) {
			// if there is media, we need to remove the media url from the tweet text
			$quoted_text = isset( $tweet['quoted_status']['full_text'] ) ? $tweet['quoted_status']['full_text'] : $tweet['quoted_status']['text'];
			if ( isset( $tweet['quoted_status']['entities']['media'][0]['url'] ) ) {
				$trimmed['quoted_status']['text'] = $this->removeStringFromText( $tweet['quoted_status']['entities']['media'][0]['url'], $quoted_text );
			}

			$num_media = count( $tweet['quoted_status']['entities']['media'] );
			for( $i = 0; $i < $num_media; $i++ ) {
				$trimmed['quoted_status']['entities']['media'][$i]['media_url_https'] = $tweet['quoted_status']['entities']['media'][$i]['media_url_https'];
				$trimmed['quoted_status']['entities']['media'][$i]['type'] = $tweet['quoted_status']['entities']['media'][$i]['type'];
				if ( $tweet['quoted_status']['entities']['media'][$i]['type'] == 'video' || $tweet['quoted_status']['entities']['media'][$i]['type'] == 'animated_gif' ) {
					foreach ( $tweet['quoted_status']['entities']['media'][$i]['video_info']['variants'] as $variant ) {
						if ( isset( $variant['content_type'] ) && $variant['content_type'] == 'video/mp4' ) {
							$trimmed['quoted_status']['entities']['media'][$i]['video_info']['variants'][$i]['url'] = $variant['url'];
						}
					}
					if ( ! isset( $trimmed['quoted_status']['entities']['media'][$i]['video_info']['variants'][$i]['url'] ) ) {
						$trimmed['quoted_status']['entities']['media'][$i]['video_info']['variants'][$i]['url'] = $tweet['quoted_status']['entities']['media'][$i]['video_info']['variants'][0]['url'];
					}
				}
			}

		}

		if ( isset( $tweet['retweeted_status']['quoted_status']['extended_entities']['media'] ) ) {
			// if there is media, we need to remove the media url from the tweet text
			$retweeted_text = isset( $tweet['retweeted_status']['quoted_status']['full_text'] ) ? $tweet['retweeted_status']['quoted_status']['full_text'] : $tweet['retweeted_status']['quoted_status']['text'];
			if ( isset( $tweet['retweeted_status']['quoted_status']['extended_entities']['media'][0]['url'] ) ) {
				$trimmed['retweeted_status']['quoted_status']['text'] = $this->removeStringFromText( $tweet['retweeted_status']['quoted_status']['extended_entities']['media'][0]['url'], $retweeted_text );
			}

			$num_media = count( $tweet['retweeted_status']['quoted_status']['extended_entities']['media'] );
			for ( $i = 0; $i < $num_media; $i++ ) {
				$trimmed['retweeted_status']['quoted_status']['extended_entities']['media'][$i]['media_url_https'] = $tweet['retweeted_status']['quoted_status']['extended_entities']['media'][$i]['media_url_https'];
				$trimmed['retweeted_status']['quoted_status']['extended_entities']['media'][$i]['type'] = $tweet['retweeted_status']['quoted_status']['extended_entities']['media'][$i]['type'];
				if ( isset( $tweet['retweeted_status']['quoted_status']['extended_entities']['media'][$i]['sizes'] ) ) {
					$trimmed['retweeted_status']['quoted_status']['extended_entities']['media'][$i]['sizes'] = $tweet['retweeted_status']['quoted_status']['extended_entities']['media'][$i]['sizes'];
				}
				if ( $tweet['retweeted_status']['quoted_status']['extended_entities']['media'][$i]['type'] == 'video' || $tweet['retweeted_status']['quoted_status']['extended_entities']['media'][$i]['type'] == 'animated_gif' ) {
					foreach ( $tweet['retweeted_status']['quoted_status']['extended_entities']['media'][$i]['video_info']['variants'] as $variant ) {
						if ( isset( $variant['content_type'] ) && $variant['content_type'] == 'video/mp4' ) {
							$trimmed['retweeted_status']['quoted_status']['extended_entities']['media'][$i]['video_info']['variants'][$i]['url'] = $variant['url'];
						}
					}
					if ( ! isset( $trimmed['retweeted_status']['quoted_status']['extended_entities']['media'][$i]['video_info']['variants'][$i]['url'] ) ) {
						$trimmed['retweeted_status']['quoted_status']['extended_entities']['media'][$i]['video_info']['variants'][$i]['url'] = $tweet['retweeted_status']['quoted_status']['extended_entities']['media'][$i]['video_info']['variants'][0]['url'];
					}
				}
			}

		} elseif ( isset( $tweet['retweeted_status']['quoted_status']['entities']['media'] ) ) {
			// if there is media, we need to remove the media url from the tweet text
			$retweeted_text = isset( $tweet['retweeted_status']['quoted_status']['full_text'] ) ? $tweet['retweeted_status']['quoted_status']['full_text'] : $tweet['retweeted_status']['quoted_status']['text'];
			if ( isset( $tweet['retweeted_status']['quoted_status']['entities']['media'][0]['url'] ) ) {
				$trimmed['retweeted_status']['quoted_status']['text'] = $this->removeStringFromText( $tweet['retweeted_status']['quoted_status']['entities']['media'][0]['url'], $retweeted_text );
			}

			$num_media = count( $tweet['retweeted_status']['quoted_status']['entities']['media'] );
			for( $i = 0; $i < $num_media; $i++ ) {
				$trimmed['retweeted_status']['quoted_status']['entities']['media'][$i]['media_url_https'] = $tweet['retweeted_status']['quoted_status']['entities']['media'][$i]['media_url_https'];
				$trimmed['retweeted_status']['quoted_status']['entities']['media'][$i]['type'] = $tweet['retweeted_status']['quoted_status']['entities']['media'][$i]['type'];
				if ( isset( $tweet['retweeted_status']['quoted_status']['entities']['media'][$i]['sizes'] ) ) {
					$trimmed['retweeted_status']['quoted_status']['entities']['media'][$i]['sizes'] = $tweet['retweeted_status']['quoted_status']['entities']['media'][$i]['sizes'];
				}
				if ( $tweet['retweeted_status']['quoted_status']['entities']['media'][$i]['type'] == 'video' || $tweet['retweeted_status']['quoted_status']['entities']['media'][$i]['type'] == 'animated_gif' ) {
					foreach ( $tweet['retweeted_status']['quoted_status']['entities']['media'][$i]['video_info']['variants'] as $variant ) {
						if ( isset( $variant['content_type'] ) && $variant['content_type'] == 'video/mp4' ) {
							$trimmed['retweeted_status']['quoted_status']['entities']['media'][$i]['video_info']['variants'][$i]['url'] = $variant['url'];
						}
					}
					if ( ! isset( $trimmed['retweeted_status']['quoted_status']['entities']['media'][$i]['video_info']['variants'][$i]['url'] ) ) {
						$trimmed['retweeted_status']['quoted_status']['entities']['media'][$i]['video_info']['variants'][$i]['url'] = $tweet['retweeted_status']['quoted_status']['entities']['media'][$i]['video_info']['variants'][0]['url'];
					}
				}
			}

		}

		//remove the url from the text if it links to a quoted tweet that is already linked to
		if ( isset( $tweet['quoted_status'] ) ) {
			$maybe_remove_index = count( $tweet['entities']['urls'] ) - 1;
			if ( isset( $tweet['entities']['urls'][$maybe_remove_index]['url'] ) ) {
				$text = isset( $trimmed['full_text'] ) ? $trimmed['full_text'] : $trimmed['text'];
				$trimmed['text'] = $this->removeStringFromText( $tweet['entities']['urls'][$maybe_remove_index]['url'], $text );
			}
		}


		// used to generate twitter cards
		if ( isset( $tweet['entities']['urls'][0]['expanded_url'] ) ) {

			$trimmed['entities']['urls'][0]['expanded_url'] = $tweet['entities']['urls'][0]['expanded_url'];

			if ( ctf_show( 'twittercards', $this->feed_options ) ) {
				$twitter_card = $this->maybeGetTwitterCardData( $tweet['entities']['urls'][0]['expanded_url'] );

				if ( ! empty( $twitter_card ) ) {
					$trimmed['twitter_card'] = $twitter_card;
					$text = isset( $tweet['full_text'] ) ? $tweet['full_text'] : $tweet['text'];
					if ( isset( $tweet['entities']['urls'][0]['url'] ) ) {
						$trimmed['text'] = $this->removeStringFromText( $tweet['entities']['urls'][0]['url'], $text );
					}
				}
			}

		}

		if ( isset( $tweet['retweeted_status']['entities']['urls'][0]['expanded_url'] ) ) {

			$trimmed['retweeted_status']['entities']['urls'][0]['expanded_url'] = $tweet['retweeted_status']['entities']['urls'][0]['expanded_url'];

			if ( ctf_show( 'twittercards', $this->feed_options ) ) {
				$twitter_card = $this->maybeGetTwitterCardData( $tweet['retweeted_status']['entities']['urls'][0]['expanded_url'] );

				if ( ! empty( $twitter_card ) ) {
					$trimmed['retweeted_status']['twitter_card'] = $twitter_card;
					$retweeted_text = isset( $tweet['retweeted_status']['full_text'] ) ? $tweet['retweeted_status']['full_text'] : $tweet['retweeted_status']['text'];
					if ( isset( $tweet['retweeted_status']['entities']['urls'][0]['url'] ) ) {
						$trimmed['retweeted_status']['text'] = $this->removeStringFromText( $tweet['retweeted_status']['entities']['urls'][0]['url'], $retweeted_text );
					}
				}
			}
		}

		return $trimmed;
	}

	/**
	 * if data exists for this url, return it so it can be used to create a twitter card
	 *
	 * @param $url string   url to search for twitter card data
	 * @return bool|mixed   return the data if it exists, otherwise false
	 */
	private function maybeGetTwitterCardData( $url ) {
		$url_key = str_replace('&','038',$url);
		$url_key = preg_replace( '~[^a-zA-Z0-9]+~', '', $url_key );

		if ( isset( $this->db_twitter_cards[ $url_key ] ) ) {
			return $this->db_twitter_cards[ $url_key ];
		}

		return false;
	}

	/**
	 * Adds data attributes to the #cff element
	 *
	 * User defined carousel options are used in the javascript file
	 * with the use of data attributes and jQuery to read them
	 *
	 * @param array $cff_content | the html that generates the feed
	 * @param array $atts | all user defined options for the feed
	 * @return string | modified html that generates the feed
	 */
	private function carouselDataAtts( $options )
	{
		if ( $this->feed_options['layout'] === 'carousel' ) {
			$data = '';

			$custom_breakpoints = apply_filters( 'ctf_carousel_breakpoints', array() );

			if ( $options['carouselautoplay'] ) {
				$data .= ' data-ctf-interval=' . $options['carouseltime'];
			}

			$data .= ' data-ctf-loop="'.$options['carouselloop'] .'"';
			$data .= isset( $options['carouselcols'] ) ?  ' data-ctf-cols="' . $options['carouselcols'] . '"' : '';
			$data .= isset( $options['carouselmobilecols'] ) ? ' data-ctf-mobilecols="' . $options['carouselmobilecols'] . '"' : '';
			$data .= isset( $options['carouselarrows'] ) ?  ' data-ctf-arrows="' . $options['carouselarrows']  . '"' : '';
			$data .= isset( $options['carouselheight'] ) ?  ' data-ctf-height="' . $options['carouselheight']  . '"' : '';
			$data .= ! empty( $custom_breakpoints ) ?  ' data-ctf-breakpoints="' . esc_attr( wp_json_encode( $custom_breakpoints ) ) . '"' : '';

			if ( $options['carouselpag'] ) {
				$data .= ' data-ctf-pag="true"';
			} else {
				$data .= ' data-ctf-pag="false"';
			}

			return $data;
		}
		return '';
	}

	/**
	 * creates opening html for the feed
	 *
	 * @return string opening html that creates the feed
	 */
	public function getFeedOpeningHtml()
	{
		$feed_options = $this->feed_options;

		$ctf_data_disablelinks = ($feed_options['disablelinks'] == 'true') ? ' data-ctfdisablelinks="true"' : '';
		$ctf_data_linktextcolor = $feed_options['linktextcolor'] != '' ? ' data-ctflinktextcolor="'.$feed_options['linktextcolor'].'"' : '';
		$ctf_data_autoscrolldistance = $feed_options['autoscroll'] ? ' data-ctfscrolloffset="'.$feed_options['autoscrolldistance'].'"' : '';
		$ctf_data_maxmedia = isset( $feed_options['maxmedia'] ) ? ' data-ctfmaxmedia="'.$feed_options['maxmedia'].'"' : '4';
		$ctf_data_imagecols = isset( $feed_options['imagecols'] ) != 0 ? ' data-ctfimagecols="'.$feed_options['imagecols'].'"' : 'auto';
		$ctf_data_other = $this->carouselDataAtts( $feed_options );
		$ctf_enable_intents = ($feed_options['disableintents'] === false || $feed_options['disableintents'] === 0) && ctf_show( 'actions', $feed_options ) ? ' data-ctfintents="1"' : '';

		$ctf_data_needed = $this->num_tweets_needed;

		$ctf_feed_type = ! empty ( $feed_options['type'] ) ? $feed_options['type'] : 'multiple';
		$ctf_feed_classes = 'ctf ctf-type-' . $ctf_feed_type;
		$ctf_feed_classes .= ' ' . $feed_options['class'] . ' ctf-styles';
		if ( ! empty( $feed_options['height'] ) ) $ctf_feed_classes .= ' ctf-fixed-height';
		$ctf_feed_classes .= $feed_options['width_mobile_no_fixed'] ? ' ctf-width-resp' : '';
		if ( $feed_options['autoscroll'] ) { $ctf_feed_classes .= ' ctf-autoscroll'; }
		if ( $this->check_for_duplicates ) { $ctf_feed_classes .= ' ctf-no-duplicates'; }
		if ( $feed_options['persistentcache'] ) { $ctf_feed_classes .= ' ctf-persistent'; }
		if ( $feed_options['font_method'] === 'fontfile' ) { $ctf_feed_classes .= ' ctf-fontfile'; }
		$ctf_feed_classes = apply_filters( 'ctf_feed_classes', $ctf_feed_classes ); //add_filter( 'ctf_feed_classes', function( $ctf_feed_classes ) { return $ctf_feed_classes . ' new-class'; }, 10, 1 );

		$ctf_feed_html = '';

		$ctf_feed_html .= '<!-- Custom Twitter Feeds by Smash Balloon -->';
		$ctf_feed_html .= '<div id="ctf" class="' . $ctf_feed_classes . '" style="' . $feed_options['width'] . $feed_options['height'] . $feed_options['bgcolor'] . '" data-ctfshortcode="' . $this->getShortCodeJSON() . '"' .$ctf_data_disablelinks . $ctf_data_linktextcolor . $ctf_data_autoscrolldistance . $ctf_enable_intents . ' data-ctfneeded="'. $ctf_data_needed . '"' . $ctf_data_other . $ctf_data_maxmedia . $ctf_data_imagecols . '>';
		$tweet_set = $this->tweet_set;

		// dynamically include header
		if ( $feed_options['showheader'] ) {
			$ctf_feed_html .= $this->getFeedHeaderHtml( $tweet_set, $this->feed_options );
		}

		$ctf_feed_html .= '<div class="ctf-tweets">';

		return $ctf_feed_html;
	}

	/**
	 * generates the header for the feed
	 *
	 * @param string $tweet_set     current set of tweets
	 * @param array $feed_options options for this feed
	 * @return string               complete html for the header
	 */
	protected function getFeedHeaderHtml( $tweet_set, $feed_options )
	{
		$ctf_header_html = '';

		$header_info = $tweet_set[0]['user'];

		if ( $feed_options['type'] === 'mentionstimeline' || $feed_options['type'] === 'hometimeline'  ) {

			$header_json = get_transient( 'ctf_hometimeline_header' );

			if ( ! $header_json ) {
				// Only can be set in the options page
				$request_settings = array(
					'consumer_key' => $this->feed_options['consumer_key'],
					'consumer_secret' => $this->feed_options['consumer_secret'],
					'access_token' => $this->feed_options['access_token'],
					'access_token_secret' => $this->feed_options['access_token_secret'],
				);

				$get_fields = $this->setGetFieldsArray( 'accountlookup', '' );

				include_once( CTF_URL . '/inc/CtfOauthConnect.php' );
				include_once( CTF_URL . '/inc/CtfOauthConnectPro.php' );

				// actual connection
				$twitter_connect = new CtfOauthConnectPro( $request_settings, 'accountlookup' );
				$twitter_connect->setUrlBase();
				$twitter_connect->setGetFields( $get_fields );
				$twitter_connect->setRequestMethod( $this->feed_options['request_method'] );

				$request_results = $twitter_connect->performRequest();

				$header_json = isset( $request_results->json ) ? $request_results->json : false;

				set_transient( 'ctf_hometimeline_header', $header_json, 60*60 );

			}

			$header_info = isset( $header_json ) ? json_decode( $header_json, true ) : array();

		}

		if ( $feed_options['type'] === 'usertimeline' || $feed_options['type'] === 'hometimeline' || $feed_options['type'] === 'mentionstimeline' ) {
			$ctf_header_html .= '<div class="ctf-header';
			if( !$feed_options['showbio'] || empty( $header_info['description'] ) ) $ctf_header_html .= ' ctf-no-bio';
			$ctf_header_html .= '" style="' . $feed_options['headerbgcolor'] . $feed_options['headertextcolor'] . '">';
			$ctf_header_html .= '<a href="https://twitter.com/' . $header_info['screen_name'] . '" target="_blank" title="@' . $header_info['screen_name'] . '" class="ctf-header-link">';
			$ctf_header_html .= '<div class="ctf-header-text">';
			$ctf_header_html .= '<p class="ctf-header-user">';
			$ctf_header_html .= '<span class="ctf-header-name">';

			if ( $feed_options['headertext'] != '' ) {
				$ctf_header_html .= $feed_options['headertext'];
			} else {
				$ctf_header_html .= $header_info['name'];
			}

			$ctf_header_html .= '</span>';

			if ( $header_info['verified'] == 1 ) {
				$ctf_header_html .= '<span class="ctf-verified">' . ctf_get_fa_el( 'fa-check-circle' ) . '</span>';
			}

			$ctf_header_html .= '<span class="ctf-header-follow">' . ctf_get_fa_el( 'fa-twitter' ) . 'Follow</span>';

			// Tweet and follower counts
			$ctf_header_html .= '<span class="ctf-header-counts">';
			$ctf_header_html .= '<span class="ctf-header-tweets-count" title="'.number_format( intval( $header_info['statuses_count'] ) ).' Tweets">' . ctf_get_fa_el( 'fa-twitter' );
			$ctf_header_html .= number_format( intval( $header_info['statuses_count'] ) );
			$ctf_header_html .= '</span>';
			$ctf_header_html .= '<span class="ctf-header-followers"  title="'.number_format( intval( $header_info['followers_count'] ) ).' Followers">' . ctf_get_fa_el( 'fa-user' );
			$ctf_header_html .= number_format( intval( $header_info['followers_count'] ) );
			$ctf_header_html .= '</span>';
			$ctf_header_html .= '</span>';

			$ctf_header_html .= '</p>';

			if ( $feed_options['showbio'] && !empty( $header_info['description'] ) ) {
				$ctf_header_html .= '<p class="ctf-header-bio">' . esc_html( $header_info['description'] ) . '</p>';
			}

			$ctf_header_html .= '</div>';
			$ctf_header_html .= '<div class="ctf-header-img">';
			$ctf_header_html .= '<div class="ctf-header-img-hover">' . ctf_get_fa_el( 'fa-twitter' ) . '</div>';
			$ctf_header_html .= '<img src="' . esc_url( $header_info['profile_image_url_https'] ) . '" alt="' . esc_attr( $header_info['name'] ). '" width="48" height="48">';
			$ctf_header_html .= '</div>';
			$ctf_header_html .= '</a>';
			$ctf_header_html .= '</div>';
		} else {

			if ( $feed_options['type'] === 'search' || $feed_options['type'] === 'hashtag' ) {
				$using_custom = $feed_options['headertext'] != '';
				$raw_header_text = $using_custom ? $feed_options['headertext'] : $feed_options['feed_term'];

				//List multiple terms
				$hashtags = explode(" OR ", $feed_options['feed_term']);
				if ( ! $using_custom ) {
					$default_header_text = '';
					$h_index = 0;
					foreach ( $hashtags as $hashtag ) {
						if( $h_index > 0 ) $default_header_text .= ', ';
						$default_header_text .= $hashtag;
						$h_index++;
					}
				} else {
					$default_header_text = $feed_options['headertext'];
				}


				if ( $feed_options['type'] === 'hashtag' ) {
					$url_part = 'hashtag/' . str_replace("#", "", $hashtags[0]);
				} else {
					$url_part = 'search?q=' . rawurlencode( str_replace( array( ', ', "'" ), array( ' OR ', '"' ), $feed_options['feed_term'] ) );
				}

			} else {
				$default_header_text = 'Twitter';
				$url_part = $feed_options['screenname']; //Need to get screenname here
			}

			//Header for combined feed types
			if ( ! empty( $feed_options['feed_types_and_terms'] ) ) {
				if ( $feed_options['headertext'] != '' ) {
					$default_header_text = $feed_options['headertext'];

					if ( $feed_options['feed_types_and_terms'][0][0] === 'search' || $feed_options['feed_types_and_terms'][0][0] === 'hashtag' ) {
						$raw_header_text = $feed_options['feed_types_and_terms'][0][1];
						//List multiple terms
						$hashtags = explode( " OR ", $feed_options['feed_types_and_terms'][0][1] );

						if ( $feed_options['feed_types_and_terms'][0][0] === 'hashtag' ) {
							$url_part = 'hashtag/' . str_replace( "#", "", $hashtags[0] );
						} else {
							$url_part = 'search?q=' . rawurlencode( str_replace( array( ', ', "'" ), array(
									' OR ',
									'"'
								), $feed_options['feed_types_and_terms'][0][1] ) );
						}
					}

				} else {
					$default_header_text = '';
					$i_term = 0;
					foreach ( $feed_options['feed_types_and_terms'] as $feed_set ) {
						if ( $feed_set[0] == 'lists' ) {
							$default_header_text .= '';
						} else {
							if ( $i_term > 0 ) {
								$default_header_text .= ', ';
							}
							if ( $feed_set[0] == 'usertimeline' ) {
								$default_header_text .= '@';
							}
							$default_header_text .= $feed_set[1];
						}
						$i_term++;
					}
				}

				if ( empty( $default_header_text ) ) {
					$default_header_text = 'Twitter';
				}

			}

			$ctf_header_html .= '<div class="ctf-header ctf-header-type-generic" style="' . $feed_options['headerbgcolor'] . $feed_options['headertextcolor'] . '">';
			$ctf_header_html .= '<a href="https://twitter.com/' . $url_part . '" target="_blank" class="ctf-header-link">';
			$ctf_header_html .= '<div class="ctf-header-text">';
			$ctf_header_html .= '<p class="ctf-header-no-bio">' . esc_html( $default_header_text ) . '</p>';
			$ctf_header_html .= '</div>';
			$ctf_header_html .= '<div class="ctf-header-img">';
			$ctf_header_html .= '<div class="ctf-header-generic-icon">';
			$ctf_header_html .= ctf_get_fa_el( 'fa-twitter' );
			$ctf_header_html .= '</div>';
			$ctf_header_html .= '</div>';
			$ctf_header_html .= '</a>';
			$ctf_header_html .= '</div>';
		}

		return $ctf_header_html;
	}

	/**
	 * outputs the html for a set of tweets to be used in the feed
	 *
	 * @param int $is_pagination    1 or 0, used to differentiate between the first set and subsequent tweet sets
	 *
	 * @return string $tweet_html
	 */
	public function getTweetSetHtml( $is_pagination = 0 )
	{
		$tweet_set = isset( $this->tweet_set['statuses'] ) ? $this->tweet_set['statuses'] : $this->tweet_set;

		if ( isset( $this->tweet_set['statuses'] ) && is_array( $this->tweet_set['statuses'] ) ) {
			$tweet_count = count( $this->tweet_set['statuses'] );
		} elseif ( is_array( $this->tweet_set ) ) {
			$tweet_count = count( $this->tweet_set );
		} else {
			$tweet_count = 0;
		}
		$len = min( $this->feed_options['num'] + $is_pagination, $tweet_count );
		$i = $is_pagination; // starts at index "1" to offset duplicate tweet
		$feed_options = $this->feed_options;
		$tweet_html = $this->feed_html;

		if ( $is_pagination && ( ! isset ( $tweet_set[1]['id_str'] ) ) ) {
			$tweet_html .= $this->getOutOfTweetsHtml( $this->feed_options );
		} else {
			while ( $i < $len ) {

				// run a check to accommodate the "search" endpoint as well
				$post = $tweet_set[$i];

				// save the original tweet data in case it's a retweet
				$post_id = $post['id_str'];
				$author = strtolower( $post['user']['screen_name'] );

				// creates a string of classes applied to each tweet
				$tweet_classes = 'ctf-item ctf-author-' . $author .' ctf-new';
				if ( !ctf_show( 'avatar', $feed_options ) ) $tweet_classes .= ' ctf-hide-avatar';
				$tweet_classes = apply_filters( 'ctf_tweet_classes', $tweet_classes ); // add_filter( 'ctf_tweet_classes', function( $tweet_classes ) { return $ctf_feed_classes . ' new-class'; }, 10, 1 );

				$retweet_data_att = '';
				// check for retweet
				if ( isset( $post['retweeted_status'] ) ) {
					$retweeter = array(
						'name' => $post['user']['name'],
						'screen_name' => $post['user']['screen_name']
					);
					$retweet_data_att = ( $this->check_for_duplicates ) ? ' data-ctfretweetid="'.$post['retweeted_status']['id_str'].'"' : '';
					$post = $post['retweeted_status'];
					$tweet_classes .= ' ctf-retweet';
				} else {
					unset( $retweeter );
				}

				// check for reply
				if ( isset( $post['in_reply_to_screen_name'] ) && $post['in_reply_to_screen_name'] !== $post['user']['screen_name'] ) {
					$tweet_classes .= ' ctf-reply';
					$replied_to = array(
						'screen_name' => $post['in_reply_to_screen_name'],
						'name' => $post['entities']['user_mentions'][0]['name'],
						'id_str' => $post['in_reply_to_status_id_str']
					);
				} else {
					unset( $replied_to );
				}

				$disable_tc_for_quoted = false;
				// check for quoted
				if ( isset( $post['quoted_status'] ) ) {
					$tweet_classes .= ' ctf-quoted';
					$quoted = $post['quoted_status'];
				} else {
					unset( $quoted );
				}

				//Media
				$media = false ;
				$num_media = false;
				if ( isset( $post['extended_entities']['media'] ) ) {

					$num_media = count( $post['extended_entities']['media'] );
					for( $ii = 0; $ii < $num_media; $ii++ ) {
						if ( $post['extended_entities']['media'][$ii]['type'] == 'video' || $post['extended_entities']['media'][$ii]['type'] == 'animated_gif' ) {
							$media[$ii]['url'] = $post['extended_entities']['media'][$ii]['video_info']['variants'][$ii]['url'];
						} else {
							$media[$ii]['url'] = isset( $post['extended_entities']['media'][$ii]['media_url_https'] ) ? $post['extended_entities']['media'][$ii]['media_url_https'] : '';
						}
						$media[$ii]['type'] = $post['extended_entities']['media'][$ii]['type'];
						if ( $media[$ii]['type'] == 'video' ) {
							$media[$ii]['video_atts'] = 'controls';
						} elseif ( $media[$ii]['type'] == 'animated_gif' ) {
							$media[$ii]['video_atts'] = 'controls loop autoplay muted';
						}
						$media[$ii]['poster'] = isset( $post['extended_entities']['media'][$ii]['media_url_https'] ) ? $post['extended_entities']['media'][$ii]['media_url_https'] : '';
						if ( $media[$ii]['type'] == 'photo' ) {
							$media[$ii]['sizes'] = isset( $post['extended_entities']['media'][$ii]['sizes'] ) ? $post['extended_entities']['media'][$ii]['sizes'] : '';
						}
					}

				} elseif ( isset( $post['entities']['media'] ) ) {

					$num_media = count( $post['entities']['media'] );
					for( $ii = 0; $ii < $num_media; $ii++ ) {
						if ( $post['entities']['media'][$ii]['type'] == 'video' || $post['entities']['media'][$ii]['type'] == 'animated_gif' ) {
							$media[$ii]['url'] = $post['entities']['media'][$ii]['video_info']['variants'][$ii]['url'];
						} else {
							$media[$ii]['url'] = $post['entities']['media'][$ii]['media_url_https'];
						}
						$media[$ii]['type'] = $post['entities']['media'][$ii]['type'];
						if ( $media[$ii]['type'] == 'video' ) {
							$media[$ii]['video_atts'] = 'controls';
						} elseif ( $media[$ii]['type'] == 'animated_gif' ) {
							$media[$ii]['video_atts'] = 'controls loop autoplay muted';
						}
						$media[$ii]['poster'] = $post['entities']['media'][$ii]['media_url_https'];
						if ( $media[$ii]['type'] == 'photo' ) {
							$media[$ii]['sizes'] = $post['entities']['media'][$ii]['sizes'];
						}
					}

				}

				//Quoted Tweets Media
				$quoted_media = false ;

				if ( isset( $quoted['extended_entities']['media'] ) ) {

					$num_media = count( $quoted['extended_entities']['media'] );
					for( $ii = 0; $ii < $num_media; $ii++ ) {
						if ( $quoted['extended_entities']['media'][$ii]['type'] == 'video' || $quoted['extended_entities']['media'][$ii]['type'] == 'animated_gif' ) {
							$quoted_media[$ii]['url'] = $quoted['extended_entities']['media'][$ii]['video_info']['variants'][$ii]['url'];
						} else {
							$quoted_media[$ii]['url'] = $quoted['extended_entities']['media'][$ii]['media_url_https'];
						}
						$quoted_media[$ii]['type'] = $quoted['extended_entities']['media'][$ii]['type'];
						if ( $quoted_media[$ii]['type'] == 'video' ) {
							$quoted_media[$ii]['video_atts'] = 'controls';
						} elseif ( $quoted_media[$ii]['type'] == 'animated_gif' ) {
							$quoted_media[$ii]['video_atts'] = 'controls loop autoplay muted';
						}
						$quoted_media[$ii]['poster'] = $quoted['extended_entities']['media'][$ii]['media_url_https'];
					}

					// don't get the twitter card if it's a quoted video
					if ( $quoted_media ) {
						$disable_tc_for_quoted = true;
						unset( $post['twitter_card'] );
					}

				} elseif ( isset( $quoted['entities']['media'] ) ) {

					$num_media = count( $quoted['entities']['media'] );
					for( $ii = 0; $ii < $num_media; $ii++ ) {
						if ( $quoted['entities']['media'][$ii]['type'] == 'video' || $quoted['entities']['media'][$ii]['type'] == 'animated_gif' ) {
							$quoted_media[$ii]['url'] = $quoted['entities']['media'][$ii]['video_info']['variants'][$ii]['url'];
						} else {
							$quoted_media[$ii]['url'] = $quoted['entities']['media'][$ii]['media_url_https'];
						}
						$quoted_media[$ii]['type'] = $quoted['entities']['media'][$ii]['type'];
						if ( $quoted_media[$ii]['type'] == 'video' ) {
							$quoted_media[$ii]['video_atts'] = 'controls';
						} elseif ( $quoted_media[$ii]['type'] == 'animated_gif' ) {
							$quoted_media[$ii]['video_atts'] = 'controls loop autoplay muted';
						}
						$quoted_media[$ii]['poster'] = $quoted['entities']['media'][$ii]['media_url_https'];
					}

					// don't get the twitter card if it's a quoted video
					if ( $quoted_media ) {
						$disable_tc_for_quoted = true;
						unset( $post['twitter_card'] );
				    }

				}

				$twitter_card_data_att = '';
				$twitter_card_url = '';
				if ( isset( $post['entities']['urls'][0]['expanded_url'] ) ) {
					$twitter_card_url = $post['entities']['urls'][0]['expanded_url'];
				}

				if ( ! isset( $post['twitter_card'] )
				     && ctf_show( 'twittercards', $feed_options )
				     && isset( $post['entities']['urls'][0]['expanded_url'] )
				     && ! $media
					 && ! $disable_tc_for_quoted) {
					$twitter_card_data_att = ' data-ctflinkurl="' . esc_url_raw( $twitter_card_url ) . '"';
					$tweet_classes .= ' ctf-check-link';
				}
				//Check whether it's a 3rd party video (youtube, vimeo, vine)
				$iframe_src = '';
				if( $twitter_card_url ){

					//Check whether it's a youtube video
					$youtube = stripos($twitter_card_url, 'youtube.com/watch');
					$youtu = stripos($twitter_card_url, 'youtu.be');
					$youtubeembed = stripos($twitter_card_url, 'youtube.com/embed');

					//Check whether it's a Vimeo video
					$vimeo = stripos($twitter_card_url, 'vimeo');

					//Check whether it's a Vine video
					$vine = stripos($twitter_card_url, 'vine.co');

					//Check whether it's a SoundCloud embed
					$soundcloud = stripos($twitter_card_url, 'soundcloud.com');

					//Check whether it's a Spotify embed
					$spotify = stripos($twitter_card_url, 'open.spotify.com');

					//YouTube
					if( $youtube || $youtubeembed || $youtu ){
						if ($youtube || $youtubeembed) {
							//Get the unique video id from the url by matching the pattern
							if (preg_match("/v=([^&]+)/i", $twitter_card_url, $matches)) {
								$id = $matches[1];
							}   elseif(preg_match("/\/v\/([^&]+)/i", $twitter_card_url, $matches)) {
								$id = $matches[1];
							}   elseif(preg_match("/\/embed\/([^&]+)/i", $twitter_card_url, $matches)) {
								$id = $matches[1];
							}
						} else if ($youtu) {
							$id = explode('/', $twitter_card_url);
							$id = end($id);
						}
						if ( isset ( $id ) && ! is_array( $id ) ) {
							$id = explode("?", $id);
						} else {
							$id = array();
						}
						if ( isset( $id[0] ) ) {
							$last = $id[0];
						} else {
							$last = '';
						}
						$iframe_src = 'https://www.youtube.com/embed/' . $last;
					}

					//Vimeo
					if ($vimeo) {
						$clip_id = '';

						//http://vimeo.com/moogaloop.swf?clip_id=101557016&autoplay=1
						$query = parse_url($twitter_card_url, PHP_URL_QUERY);
						parse_str($query, $params);
						if(isset($params['clip_id'])) $clip_id = $params['clip_id'];

						//https://player.vimeo.com/video/116446625?autoplay=1
						if( !isset($clip_id) || $clip_id == '' ){
							$vimeo_url = strtok($twitter_card_url,'?');
							$vimeo_pass = explode('/', $vimeo_url);
							$clip_id = end(($vimeo_pass));
						}
						if (!preg_match('([a-zA-Z])', $clip_id)) {
							$iframe_src = 'https://player.vimeo.com/video/'.$clip_id;
						} else {
							$iframe_src = '';
							$vimeo = false;
						}

					}

					//Vine
					if ($vine) {
						//https://vine.co/v/hu77ljU6OOF/embed/simple
						$twitter_card_url = explode("?", $twitter_card_url);
						$iframe_src = $twitter_card_url[0] . '/embed/simple';
					}

					//SoundCloud
					if ($soundcloud){
						$twitter_card_url = explode("?", $twitter_card_url);
						$iframe_src = 'https://w.soundcloud.com/player/?url=' . $twitter_card_url[0] . '&amp;auto_play=false&amp;hide_related=true&amp;show_comments=false&amp;show_user=true&amp;show_reposts=false&amp;visual=false';
						$media[0]['iframe_type'] = 'audio';
					}

					//Spotify
					/*
					 * https://open.spotify.com/track/5H0kIgpWNZm9HF6VkU4dow?si=KvuO3MAuQmCxq8AI2QdhCw
					 * <iframe src="https://open.spotify.com/embed/album/1DFixLWuPkv3KT3TnV35m3" width="300" height="380" frameborder="0" allowtransparency="true" allow="encrypted-media"></iframe>
<iframe src="https://open.spotify.com/embed/track/5H0kIgpWNZm9HF6VkU4dow?si=KvuO3MAuQmCxq8AI2QdhCw" width="300" height="380" frameborder="0" allowtransparency="true" allow="encrypted-media"></iframe>
					 */
					if ($spotify){
						$type = strpos( $twitter_card_url, 'track/' ) !== false ? 'track' : 'album';
						$url_pieces = $type === 'track' ? explode('track', $twitter_card_url ) : explode('album', $twitter_card_url );
						if ( isset( $url_pieces[1] ) ) {
							$iframe_src = 'https://open.spotify.com/embed/' . $type . $url_pieces[1];
							$media[0]['iframe_type'] = 'audio';
							$media[0]['iframe_class'] = ' ctf-spotify';
						} else {
							$spotify = false;
						}
					}

					//If the link is for an embedded video then add to media object
					if($youtube || $youtu || $youtubeembed || $vimeo || $vine || $soundcloud || $spotify){
						$media[0]['type'] = 'iframe';
						$media[0]['url'] = esc_url( $iframe_src );
					}

				} // if( $twitter_card_url )
				if ( ctf_show( 'twittercards', $feed_options ) && isset( $post['twitter_card'] ) ) {
					$tweet_classes .= ' ctf-tc-checked';
				}

					// include tweet view
				$tweet_html .= '<div class="'. $tweet_classes . '" id="ctf_' . esc_attr( $post_id ) . '" style="' . $feed_options['tweetbgcolor'] .'"' . $twitter_card_data_att . $retweet_data_att . '>';

				if ( isset( $retweeter ) && ctf_show( 'retweeter', $feed_options ) ) {
					$tweet_html .= '<div class="ctf-context">';
					$tweet_html .= '<a href="https://twitter.com/intent/user?screen_name=' . $retweeter['screen_name'] . '" target="_blank" class="ctf-retweet-icon">' . ctf_get_fa_el( 'fa-retweet' ) . '<span class="ctf-screenreader">Retweet on Twitter</span></a>';
					$tweet_html .= '<a href="https://twitter.com/' . $retweeter['screen_name'] . '" target="_blank" class="ctf-retweet-text" style="' . $feed_options['authortextsize'] . $feed_options['authortextweight'] . $feed_options['textcolor'] . '">' . $retweeter['name'] . ' ' . $feed_options['retweetedtext'] . '</a>';
					$tweet_html .= '</div>';
				} else if ( isset( $replied_to ) && ctf_show( 'repliedto', $feed_options ) ) {
					$tweet_html .= '<div class="ctf-context">';
					$tweet_html .= '<a href="https://twitter.com/' .$post['user']['screen_name'] . '/status/' . $post['id_str'] . '" target="_blank" class="ctf-reply-icon">' . ctf_get_fa_el( 'fa-reply' ) . '<span class="ctf-screenreader">View tweet on Twitter</span></a>';
					$tweet_html .= '<a href="https://twitter.com/' . $replied_to['screen_name'] . '" target="_blank" class="ctf-replied-to-text" style="' . $feed_options['authortextsize'] . $feed_options['authortextweight'] .  $feed_options['textcolor'] . '">' . $feed_options['inreplytotext'] . ' ' . $replied_to['name'] . '</a>';
					$tweet_html .= '</div>';
				} // show repliedto

				if ( ctf_show( 'avatar', $feed_options ) || ctf_show( 'author', $feed_options ) || ctf_show( 'logo', $feed_options ) || ctf_show( 'date', $feed_options ) ) {
					$tweet_html .= '<div class="ctf-author-box">';
					$tweet_html .= '<div class="ctf-author-box-link" style="' . $feed_options['authortextsize'] . $feed_options['authortextweight'] . $feed_options['textcolor'] . '">';
					if ( ctf_show( 'avatar', $feed_options ) ) {
						$tweet_html .= '<a href="https://twitter.com/' . $post['user']['screen_name'] . '" class="ctf-author-avatar" target="_blank" style="' . $feed_options['authortextsize'] . $feed_options['authortextweight'] . $feed_options['textcolor'] . '">';
						$tweet_html .= '<img src="' . esc_url( $post['user']['profile_image_url_https'] ) . '" alt="' . $post['user']['screen_name'] . ' avatar" width="48" height="48">';
						$tweet_html .= '</a>';
					}

					if ( ctf_show( 'author', $feed_options ) ) {
						$tweet_html .= '<a href="https://twitter.com/' . $post['user']['screen_name'] . '" target="_blank" class="ctf-author-name" style="' . $feed_options['authortextsize'] . $feed_options['authortextweight'] . $feed_options['textcolor'] . '">' . $post['user']['name'] . '</a>';
						if ( $post['user']['verified'] == 1 ) {
							$tweet_html .= '<span class="ctf-verified" >' . ctf_get_fa_el( 'fa-check-circle' ) . '</span>';
						}
						$tweet_html .= '<a href="https://twitter.com/' . $post['user']['screen_name'] . '" class="ctf-author-screenname" target="_blank" style="' . $feed_options['authortextsize'] . $feed_options['authortextweight'] . $feed_options['textcolor'] . '">@' . $post['user']['screen_name'] . '</a>';
						$tweet_html .= '<span class="ctf-screename-sep">&middot;</span>';
					}

					if ( ctf_show( 'date', $feed_options ) ) {
						$tweet_html .= '<div class="ctf-tweet-meta">';
						$tweet_html .= '<a href="https://twitter.com/' . $post['user']['screen_name'] . '/status/' . $post['id_str'] . '" class="ctf-tweet-date" target="_blank" style="' . $feed_options['datetextsize'] . $feed_options['datetextweight'] . $feed_options['textcolor'] . '">' . esc_html( ctf_get_formatted_date( $post['created_at'], $feed_options, $post['user']['utc_offset'] ) ) . '<span class="ctf-screenreader"> ' . $post['id_str'] . '</span></a>';
						$tweet_html .= '</div>';
					} // show date
					$tweet_html .= '</div>';
					if ( ctf_show( 'logo', $feed_options ) ) {
						$tweet_html .= '<div class="ctf-corner-logo" style="' . $feed_options['logosize'] . $feed_options['logocolor'] . '">';
						$tweet_html .= ctf_get_fa_el( 'fa-twitter' );
						$tweet_html .= '</div>';
					}
					$tweet_html .= '</div>';
				}
				$disablelightbox = $feed_options['disablelightbox'];

				if ( ctf_show( 'text', $feed_options ) || ctf_show( 'media', $feed_options ) || ctf_show( 'twittercards', $feed_options ) || ctf_show( 'linkbox', $feed_options ) ) {
					$tweet_html .= '<div class="ctf-tweet-content';
					if ( $feed_options['disablelightbox'] ) $tweet_html .= ' ctf-disable-lightbox';
					$tweet_html .= '">';

					if ( ctf_show( 'text', $feed_options ) ) {
						$post_text = apply_filters( 'ctf_tweet_text', $post['text'], $feed_options, $post );
						if ( $feed_options['linktexttotwitter'] ) {
							$tweet_html .= '<a class="ctf-tweet-text-link" href="https://twitter.com/' .$post['user']['screen_name'] . '/status/' . $post['id_str'] . '" target="_blank">';
							$tweet_html .= '<p class="ctf-tweet-text" style="' . $feed_options['tweettextsize'] . $feed_options['tweettextweight'] . $feed_options['textcolor'] . '">' . nl2br( $post_text ) . '</p>';
							$tweet_html .= '</a>';
						} else {
							$tweet_html .= '<p class="ctf-tweet-text" style="' . $feed_options['tweettextsize'] . $feed_options['tweettextweight'] . $feed_options['textcolor'] . '">' . nl2br( $post_text ) . '</p>';
						} // link text to twitter option is selected
					}

					$media = apply_filters( 'ctf_item_media', $media );

					if ( ctf_show( 'media', $feed_options ) && $media ) {
						$media_classes = 'ctf-tweet-media';
						$media_classes .= $num_media > 1 ? ' ctf-tweet-media-masonry' : '';
						$tweet_html .= '<div class="' . $media_classes . '">';

						// if($ctf_is_video_embed) $tweet_html .= $ctf_iframe;
						$maxmedia = isset( $feed_options['maxmedia'] ) ? $feed_options['maxmedia'] : 4;

						$num_added = 0;
						foreach ( $media as $medium ) {

							if ( $num_added < $maxmedia ) {
								$num_added++;
								//Define image for lightbox
								isset($medium['poster']) ? $ctf_lightbox_image = $medium['poster'] : $ctf_lightbox_image = $medium['url'];
								if ( isset( $post['text'] ) && ! empty( $post['text'] ) ) {
									$alt_text_for_image = explode(" ", $post['text'], 6 );
									$alt_text_for_image = __( 'Image for the Tweet beginning', 'custom-twitter-feeds' ) . ': ' . implode(" ", array_splice( $alt_text_for_image, 0, 5 ) );

								} else {
									$alt_text_for_image = __( 'View on Twitter', 'custom-twitter-feeds' );
								}
								$ctf_alt_text = htmlspecialchars(preg_replace('@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?)@', '', $alt_text_for_image));

								if ( $medium['type'] == 'iframe' ){
									$additional_class = isset( $medium['iframe_type'] ) ? ' ctf-if-type-' . $medium['iframe_type'] : '';
									$additional_class .= isset( $medium['iframe_class'] ) ? $medium['iframe_class'] : '';
									$ctf_lightbox_image = plugins_url( '/img/video-lightbox.png' , dirname(__FILE__) );
									$tweet_html .= '<div class="ctf-iframe-wrap'.$additional_class.'">';
								}

								//Create the media link for the lightbox/post
								$media_link = '<a ';
								if($disablelightbox){
									$media_link .= 'href="https://twitter.com/' .$post['user']['screen_name'] . '/status/' . $post['id_str'] . '" target="_blank" class="ctf-media-link ';
								} else {
									$media_link .= 'href="'.esc_url( $ctf_lightbox_image ).'" data-ctf-lightbox="1" data-title="'.esc_attr( $post['text'] ).'" data-user="'.$post['user']['screen_name'].'" data-name="'.esc_attr($post['user']['name']).'" data-id="'.$post_id.'" data-url="https://twitter.com/' .$post['user']['screen_name'] . '/status/' . $post['id_str'] . '" data-avatar="'.esc_url( $post['user']['profile_image_url_https'] ).'" data-date="'.ctf_get_formatted_date( $post['created_at'] , $feed_options, $post['user']['utc_offset'] ).'" class="ctf-lightbox-link ';
								}
								if ( $medium['type'] == 'video' || $medium['type'] == 'animated_gif') {
									$media_link .= 'ctf-video ctf-video-type-'.esc_attr( $medium['type'] ).'" data-video="'.esc_url( $medium['url'] ).'" data-iframe=""><span class="ctf-screenreader">Twitter feed video.</span>';
								} else if ( $medium['type'] == 'iframe' ) {
									$media_link .= 'ctf-iframe" data-video="" data-iframe="'.esc_url( $medium['url'] ).'"><span class="ctf-screenreader">Twitter feed video.</span>';
								} else {
									$media_link .= 'ctf-image" data-video="" data-iframe=""><span class="ctf-screenreader">Twitter feed video.</span>';
								}

								//Don't include the media link if the lightbox is disabled and it's a video
								( ($medium['type'] == 'video' || $medium['type'] == 'animated_gif') && $disablelightbox ) ? $tweet_html .= '' : $tweet_html .= $media_link;

								//Add hover state to photos/videos
								$tweet_html .= ctf_get_fa_el( 'ctf_playbtn' );
								if(!$disablelightbox) $tweet_html .= '<div class="ctf-photo-hover">' . ctf_get_fa_el( 'fa-arrows-alt' ) . '</div>';

								if ( $medium['type'] == 'video' || $medium['type'] == 'animated_gif' ) {

									if( $disablelightbox || $medium['type'] == 'animated_gif' ) $tweet_html .= '<video ' . $medium['video_atts'] .' src="' . esc_url( $medium['url'] ) . '" type="video/mp4" poster="'.esc_url( $ctf_lightbox_image ).'">';
									$tweet_html .= '<img src="'.$ctf_lightbox_image.'" alt="'.esc_attr( $ctf_alt_text ).'" />';
									if( $disablelightbox ) $tweet_html .= '</video>';

								} else if ( $medium['type'] == 'iframe' ) {
									$tweet_html .= '<iframe src="'.$medium['url'].'" type="text/html" allowfullscreen frameborder="0" title="Twitter Feed Media" webkitAllowFullScreen mozallowfullscreen></iframe>';
								} else {
									if ( $feed_options['autores'] === true ) {
										$data_sizes = isset( $medium['sizes'] ) && is_array( $medium['sizes'] ) ? ' data-ctfsizes="' . $medium['sizes']['thumb']['w'].','.$medium['sizes']['small']['w'] . ','.$medium['sizes']['medium']['w'] .','. $medium['sizes']['large']['w'] . '"' : '';
										$tweet_html .= '<img src="' . esc_url( $medium['url'] ). ':thumb" alt="'.esc_attr( $ctf_alt_text ).'"' . $data_sizes . '>';
									} else {
										$data_sizes = ' data-ctfsizes="full"';
										$tweet_html .= '<img src="' . esc_url( $medium['url'] ) . '" alt="'.esc_attr( $ctf_alt_text ).'"' . $data_sizes . '>';
									}

								}

								//End the media link
								( ($medium['type'] == 'video' || $medium['type'] == 'animated_gif') && $disablelightbox ) ? $tweet_html .= '' : $tweet_html .= '</a>';

								if ( $medium['type'] == 'iframe' ) $tweet_html .= '</div>';
							}

						}// end foreach
						
						$tweet_html .= '</div>';
					} elseif ( ctf_show( 'twittercards', $feed_options ) && isset( $post['twitter_card'] ) && ! empty( $post['twitter_card']['twitter:card'] ) && ! $disable_tc_for_quoted ) {

						if ( $post['twitter_card']['twitter:card'] === 'summary' || $post['twitter_card']['twitter:card'] === 'summary_large_image' || $post['twitter_card']['twitter:card'] === 'player' ) {
							$tweet_html .= '<a href="' . $twitter_card_url . '" class="ctf-twitter-card ctf-tc-type-' . $post['twitter_card']['twitter:card'] . '" target="_blank">';
							if ( !empty( $post['twitter_card']['twitter:image'] ) ) {
								$tweet_html .= '<div class="ctf-tc-image" data-bg="' . esc_url( $post['twitter_card']['twitter:image'] ) . '"><img src="' . esc_url( $post['twitter_card']['twitter:image'] ) . '" alt="'.esc_attr( $post['twitter_card']['twitter:image:alt'] ).'"></div>';
							}
							$tweet_html .= '<div class="ctf-tc-summary-info">';
							$tweet_html .= '<p class="ctf-tc-heading">'.esc_html( $post['twitter_card']['twitter:title'] ).'</p>';
							$tweet_html .= '<p class="ctf-tc-desc">'.esc_html( substr($post['twitter_card']['twitter:description'], 0, 150 ));
							if( strlen($post['twitter_card']['twitter:description']) > 150 ) $tweet_html .= '...';
							$tweet_html .= '</p>';
							$working_url = explode("/", preg_replace("(^https?://)", "", $twitter_card_url));
							$tweet_html .= '<p class="ctf-tc-url">'.esc_html( $working_url[0] ).'</p>';
							$tweet_html .= '</div>';

							$tweet_html .= '</a>';
						} elseif ( $post['twitter_card']['twitter:card'] === 'amplify' ) {

							//HTML5 video
							if( isset( $post['twitter_card']['twitter:image:src'] ) ){
								$tweet_html .= '<div class="ctf-tweet-media">';
								if( $disablelightbox ){
									$tweet_html .= '<a href="https://twitter.com/' .$post['user']['screen_name'] . '/status/' . $post_id . '" target="_blank">';
								} else {
									$tweet_html .= '<a href="'.$post['twitter_card']['twitter:image:src'].'" data-ctf-lightbox="1" data-title="'.esc_attr( $post['text'] ).'" data-user="'.esc_attr( $post['user']['screen_name'] ).'" data-name="'.esc_attr( $post['user']['name'] ).'" data-id="'.esc_attr( $post_id ).'" data-url="https://twitter.com/' .$post['user']['screen_name'] . '/status/' . $post['id_str'] . '" data-avatar="'.esc_url( $post['user']['profile_image_url_https'] ).'" data-date="'.ctf_get_formatted_date( $post['created_at'] , $feed_options, $post['user']['utc_offset'] ).'" data-video="" data-iframe="'.esc_url( $twitter_card_url ).'" data-amplify="true" class="ctf-video">';
									$tweet_html .= ctf_get_fa_el( 'ctf_playbtn' );
									$tweet_html .= '<div class="ctf-photo-hover"></div>';
								}
								$tweet_html .= '<img src="'.esc_url( $post['twitter_card']['twitter:image:src'] ).'" alt="'.esc_attr( $post['twitter_card']['twitter:title'] ).'">' .
								               '</a>' .
								               '</div>';
							}

						} else {
							$tweet_html .= '<a href="' . esc_url( $twitter_card_url ) . '" target="_blank">' . esc_html( $twitter_card_url ) . '</a>';
						}
					}


					if ( ctf_show( 'linkbox', $feed_options ) && isset( $quoted ) ) {
						if ( $quoted_media
						     && ($quoted_media[0]['type'] == 'video' || $quoted_media[0]['type'] == 'animated_gif') ) {
							$medium = $quoted_media[0];
							isset($medium['poster']) ? $ctf_lightbox_image = $medium['poster'] : $ctf_lightbox_image = $medium['url'];
							isset($post['text']) ? $ctf_alt_text = htmlspecialchars($post['text']) : $ctf_alt_text = 'View on Twitter';
							$tweet_html .= '<div class="ctf-quoted-video">';
							$media_link = '<a ';

							if($disablelightbox){
								$media_link .= 'href="https://twitter.com/' .$post['user']['screen_name'] . '/status/' . $post['id_str'] . '" target="_blank" class="ctf-media-link ';
							} else {
								$media_link .= 'href="'.esc_url( $ctf_lightbox_image ).'" data-ctf-lightbox="1" data-title="'.esc_attr( $post['text'] ).'" data-user="'.$post['user']['screen_name'].'" data-name="'.esc_attr($post['user']['name']).'" data-id="'.$post_id.'" data-url="https://twitter.com/' .$post['user']['screen_name'] . '/status/' . $post['id_str'] . '" data-avatar="'.esc_url( $post['user']['profile_image_url_https'] ).'" data-date="'.ctf_get_formatted_date( $post['created_at'] , $feed_options, $post['user']['utc_offset'] ).'" class="ctf-lightbox-link ';
							}
							if ( $medium['type'] == 'video' || $medium['type'] == 'animated_gif') {
								$media_link .= 'ctf-video ctf-video-type-'.esc_attr( $medium['type'] ).'" data-video="'.esc_url( $medium['url'] ).'" data-iframe=""><span class="ctf-screenreader">Twitter feed video.</span>';
							}

							//Don't include the media link if the lightbox is disabled and it's a video
							( ($medium['type'] == 'video' || $medium['type'] == 'animated_gif') && $disablelightbox ) ? $tweet_html .= '' : $tweet_html .= $media_link;
							$tweet_html .= ctf_get_fa_el( 'ctf_playbtn' );

							if( $disablelightbox || $medium['type'] == 'animated_gif' ) $tweet_html .= '<video ' . $medium['video_atts'] .' src="' . esc_url( $medium['url'] ) . '" type="video/mp4" poster="'.esc_url( $ctf_lightbox_image ).'">';
							$tweet_html .= '<img src="'.$ctf_lightbox_image.'" alt="'.esc_attr( $ctf_alt_text ).'" />';
							if( $disablelightbox || $medium['type'] == 'animated_gif' ) $tweet_html .= '</video>';

							( ($medium['type'] == 'video' || $medium['type'] == 'animated_gif') && $disablelightbox ) ? $tweet_html .= '' : $tweet_html .= '</a>';
							$tweet_html .= '<div class="ctf-tc-summary-info">';
							$tweet_html .= '<span class="ctf-quoted-author-name">' . esc_html( $quoted['user']['name'] ) . '</span>';

							if ($quoted['user']['verified'] == 1) {
								$tweet_html .= '<span class="ctf-quoted-verified">' . ctf_get_fa_el( 'fa-check-circle' ) . '</span>';
							} // user is verified
							$quoted_text = apply_filters( 'ctf_quoted_tweet_text', $quoted['text'], $feed_options, $quoted );
							$tweet_html .= '<span class="ctf-quoted-author-screenname">@' . esc_html( $quoted['user']['screen_name'] ) . '</span>';
							$tweet_html .= '<p class="ctf-quoted-tweet-text" style="' . $feed_options['tweettextsize'] . $feed_options['tweettextweight'] . $feed_options['textcolor'] . '">' . nl2br( $quoted_text ) . '</p>';
							$tweet_html .= '</div>';

							$tweet_html .= '</div>';

						} else {
							$tweet_html .= '<a href="https://twitter.com/' .$quoted['user']['screen_name'] . '/status/' . $quoted['id_str'] . '" class="ctf-quoted-tweet" style="' . $feed_options['quotedauthorsize'] . $feed_options['quotedauthorweight'] . $feed_options['textcolor'] . '" target="_blank">';

							if ( $quoted_media ) {
								foreach ( $quoted_media as $medium ) {
									//Define image for lightbox
									isset($medium['poster']) ? $ctf_lightbox_image = $medium['poster'] : $ctf_lightbox_image = $medium['url'];
									isset($post['text']) ? $ctf_alt_text = htmlspecialchars($post['text']) : $ctf_alt_text = 'View on Twitter';

									$tweet_html .= '<div class="ctf-tc-image" data-bg="' . esc_url( $ctf_lightbox_image ) . '"><img src="' . esc_url( $ctf_lightbox_image ) . '" alt="'.esc_attr( $ctf_alt_text ).'"></div>';


									// $tweet_html .= '<div class="ctf-tc-summary-info">';
									//     $tweet_html .= '<p class="ctf-tc-heading">'.$post['twitter_card']['twitter:title'].'</p>';
									//     $tweet_html .= '<p class="ctf-tc-desc">'.substr($post['twitter_card']['twitter:description'], 0, 150);
									//     if( strlen($post['twitter_card']['twitter:description']) > 150 ) $tweet_html .= '...';
									//     $tweet_html .= '</p>';
									//     $working_url = explode("/", preg_replace("(^https?://)", "", $twitter_card_url));
									//     $tweet_html .= '<p class="ctf-tc-url">'.$working_url[0].'</p>';
									// $tweet_html .= '</div>';





								}// end foreach
							}

							$tweet_html .= '<div class="ctf-tc-summary-info">';
							$tweet_html .= '<span class="ctf-quoted-author-name">' . esc_html( $quoted['user']['name'] ) . '</span>';

							if ($quoted['user']['verified'] == 1) {
								$tweet_html .= '<span class="ctf-quoted-verified">' . ctf_get_fa_el( 'fa-check-circle' ) . '</span>';
							} // user is verified
							$quoted_text = apply_filters( 'ctf_quoted_tweet_text', $quoted['text'], $feed_options, $quoted );
							$tweet_html .= '<span class="ctf-quoted-author-screenname">@' . esc_html( $quoted['user']['screen_name'] ) . '</span>';
							$tweet_html .= '<p class="ctf-quoted-tweet-text" style="' . $feed_options['tweettextsize'] . $feed_options['tweettextweight'] . $feed_options['textcolor'] . '">' . nl2br( $quoted_text ) . '</p>';
							$tweet_html .= '</div>';
							$tweet_html .= '</a>';
						}

					}// show link box



					$tweet_html .= '</div>';
				}

				$tweet_html .= '<div class="ctf-tweet-actions">';
				if ( ctf_show( 'actions', $feed_options ) ) {
					$tweet_html .= '<a href="https://twitter.com/intent/tweet?in_reply_to=' . $post['id_str'] . '&related=' . $post['user']['screen_name'] . '" class="ctf-reply" target="_blank" style="' . $feed_options['iconsize'] . $feed_options['iconcolor'] . '">' . ctf_get_fa_el( 'fa-reply' ) . '<span class="ctf-screenreader">Reply on Twitter ' . $post['id_str'] . '</span></a>';
					$tweet_html .= '<a href="https://twitter.com/intent/retweet?tweet_id=' . $post['id_str'] . '&related=' . $post['user']['screen_name'] . '" class="ctf-retweet" target="_blank" style="' . $feed_options['iconsize'] . $feed_options['iconcolor'] . '">' . ctf_get_fa_el( 'fa-retweet' ) . '<span class="ctf-screenreader">Retweet on Twitter ' . $post['id_str'] . '</span><span class="ctf-action-count ctf-retweet-count">';
					if ( $post['retweet_count'] > 0 ) {
						$tweet_html .= $post['retweet_count'];
					}
					$tweet_html .= '</span></a>';
					$tweet_html .= '<a href="https://twitter.com/intent/like?tweet_id=' . $post['id_str'] . '&related=' . $post['user']['screen_name'] . '" class="ctf-like" target="_blank" style="' . $feed_options['iconsize'] . $feed_options['iconcolor'] . '">' . ctf_get_fa_el( 'fa-heart' ) . '<span class="ctf-screenreader">Like on Twitter ' . $post['id_str'] . '</span><span class="ctf-action-count ctf-favorite-count">';
					if ( $post['favorite_count'] > 0 ) {
						$tweet_html .= $post['favorite_count'];
					}
					$tweet_html .= '</span></a>';
				}
				if ( ctf_show( 'twitterlink', $feed_options ) ) {
					$tweet_html .= '<a href="https://twitter.com/' . $post['user']['screen_name'] . '/status/' . $post['id_str'] . '" class="ctf-twitterlink" style="' . $feed_options['textcolor'] . '" target="_blank">' . esc_html( $feed_options['twitterlinktext'] ) . ' <span class="ctf-screenreader">' . $post['id_str'] . '</span></a>';
				} // show twitter link or actions
				$tweet_html .= '</div>';
				$tweet_html .= '</div>';

				$i++;
			}
		}
		return $tweet_html;
	}
}