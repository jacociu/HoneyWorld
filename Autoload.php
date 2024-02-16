<?php

function my_autoloader($className) {

        $firstLetter = $className[0];
        switch ($firstLetter) {
            case 'E':
                include_once( 'Entity/'. $className . '.php' );
                break;

            case 'F':
                include_once( 'Foundation/'. $className . '.php' );
                break;

            case 'V':
                include_once( 'View/'. $className . '.php' );
                break;

            case 'C':
                include_once( 'Control/'. $className . '.php' );
                break;

            case 'I':
                include_once( $className . '.php' );
                break;

            case 'U':
                include_once ('Utility/'. $className. '.php');
                break;

    }
}

spl_autoload_register('my_autoloader');
