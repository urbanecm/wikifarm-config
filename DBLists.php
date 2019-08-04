<?php

class DBLists
{
    /**
     * Get an array of DB names from a .dblist file.
     *
     * @param $srcPath string
     * @return Array
     */
    public static function readDbListFile( $dblist ) {
        $fileName = __DIR__ . '/dblists/' . $dblist . '.dblist';
        $lines = @file( $fileName, FILE_IGNORE_NEW_LINES );
        if ( !$lines ) {
            throw new Exception( __METHOD__ . "(): unable to read $dblist.\n" );
        }
    
        $dbs = [];
        foreach ( $lines as $line ) {
            // Strip comments ('#' to end-of-line) and trim whitespace.
            $line = trim( substr( $line, 0, strcspn( $line, '#' ) ) );
            $dbs[] = $line;
        }
        return $dbs;
    }
}