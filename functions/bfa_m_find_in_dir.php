<?php
function bfa_m_find_in_dir( $base, $pattern, $recursive = true, $case_sensitive = false ) {
    $result = array();
    if( $case_sensitive ) {
        if( false === bfa_m_find_in_dir_( $base, $pattern, $recursive, $result )) {
            return false;
        }
    } else {
        if( false === bfa_m_find_in_dir_i_( $base, $pattern, $recursive, $result )) {
            return false;
        }
    }
   
    return $result;
}

/**
 * @access private
 */
function bfa_m_find_in_dir_( $base, $pattern, $recursive, &$result ) {
    $dh = @opendir( $base );
    if( false === $dh ) {
        return false;
    }
    while( $file = readdir( $dh )) {
        if( "." == $file || ".." == $file ){
            continue;
        }
//		Note: ereg() is depreciated in php 5.3
//      if( false !== @ereg( $pattern, "{$base}/{$file}" )) { 
        if( false !== @preg_match( '/'.$pattern.'/', "{$base}/{$file}" )) {
            $result[] = "{$base}/{$file}";
        }
        if( false !== $recursive && is_dir( "{$base}/{$file}" )) {
            bfa_m_find_in_dir_( "{$base}/{$file}", $pattern, $recursive, $result );
        }
    }
    closedir( $dh );
    return true;
}

/**
 * @access private
 */
function bfa_m_find_in_dir_i_( $base, $pattern, $recursive, &$result ) {
    $dh = @opendir( $base );
    if( false === $dh ) {
        return false;
    }
    while( $file = readdir( $dh )) {
        if( "." == $file || ".." == $file ){
            continue;
        }
//		Note: ergi() is depreciated in php 5.3
//      if( false !== @eregi( $pattern, "{$base}/{$file}" )) { 
        if( false !== @preg_match( '/'.$pattern.'/i', "{$base}/{$file}" )) {
            $result[] = "{$base}/{$file}";
        }
        if( false !== $recursive && is_dir( "{$base}/{$file}" )) {
            bfa_m_find_in_dir_( "{$base}/{$file}", $pattern, $recursive, $result );
        }
    }
    closedir( $dh );
    return true;
}
?>