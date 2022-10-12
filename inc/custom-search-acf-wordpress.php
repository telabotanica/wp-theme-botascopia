<?php
/*
  ##############################
  ########### Search ###########
  ##############################
  Included are steps to help make this script easier for other to follow
  All you have to do is add custom ACF post types into Step 1 and custom taxonomies into Step 10
  I also updated this work to include XSS and SQL injection projection
  [list_searcheable_acf list all the custom fields we want to include in our search query]
  @return [array] [list of custom fields]
*/

// Define list of ACF fields you want to search through - do NOT include taxonomies here
function list_searcheable_acf(){
    $list_searcheable_acf = array( "your",
                                   "acf",
                                   "non-repeater",
                                   "field-names",
                                   "here",
                                   "repeater" => array( "repeater-sub-field1",
                                                        "repeater-sub-field2" )
                                   );
    return $list_searcheable_acf;
}

/*
 * [advanced_custom_search search that encompasses ACF/advanced custom fields and taxonomies and split expression before request]
 * @param  [query-part/string]      $search    [the initial "where" part of the search query]
 * @param  [object]                 $wp_query []
 * @return [query-part/string]      $search    [the "where" part of the search query as we customized]
 * modified from gist: https://gist.github.com/fiskhandlarn/f6f86c99e59f62d72ac2ce10be12dc1a
 * see https://vzurczak.wordpress.com/2013/06/15/extend-the-default-wordpress-search/
 * credits to Vincent Zurczak for the base query structure/spliting tags section and Sjouw for comment cleanup
*/

function advanced_custom_search( $search, $wp_query ) {
    global $wpdb;

    if ( empty( $search )) {
        return $search;
    }

    // 1- get search expression
    $terms_raw = $wp_query->query_vars[ 's' ];

    // 2- check search term for XSS attacks
    $terms_xss_cleared = strip_tags($terms_raw);

    // 3- do another check for SQL injection, use WP esc_sql
    $terms = esc_sql($terms_xss_cleared);

    // 4- explode search expression to get search terms
    $exploded = explode( ' ', $terms );
    if( $exploded === FALSE || count( $exploded ) == 0 ) {
        $exploded = array( 0 => $terms );
    }

    // 5- setup search variable as a string
    $search = '';

    // 6- get searcheable_acf, a list of advanced custom fields you want to search content in
    $list_searcheable_acf = list_searcheable_acf();

    // 7- get custom table prefixes, thanks to Brian Douglas @bmdinteractive on github for this improvement
    $table_prefix = $wpdb->prefix;

    // 8- search through tags, inject each into SQL query
    foreach( $exploded as $tag ) {
        $search .= "
      AND (
        (".$table_prefix."posts.post_title LIKE '%$tag%')
        OR (".$table_prefix."posts.post_excerpt LIKE '%$tag%')
        OR (".$table_prefix."posts.post_content LIKE '%$tag%')
        ".
        // 9- Adds to $search DB data from custom post types
            "OR EXISTS (
          SELECT * FROM ".$table_prefix."postmeta
          WHERE post_id = ".$table_prefix."posts.ID
          AND (";
        // 9b - reads through $list_searcheable_acf array to see which custom post types you want to include in the search string
        $metaStatements = array();
        foreach ($list_searcheable_acf as $key => $searcheable_acf) {
            if ( is_array( $searcheable_acf ) ) {
                foreach ( $searcheable_acf as $repeater_acf ) {
                    array_push( $metaStatements, "(meta_key LIKE '" . $key . "_%_" . $repeater_acf . "' AND meta_value LIKE '%$tag%')" );
                }
            }
            else {
                array_push( $metaStatements, "(meta_key = '" . $searcheable_acf . "' AND meta_value LIKE '%$tag%')" );
            }
        }
        $search .= join( $metaStatements, "\n          OR " );
        $search .= ")
        )".
                
            ")"; // closes $search
    } // closes foreach
    return $search;
} // closes function advanced_custom_search

// 12- use add_filter to put advanced_custom_search into the posts_search results
add_filter( 'posts_search', 'advanced_custom_search', 500, 2 );
