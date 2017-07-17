<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

define('DIR_NOT_INTAKE', -10);
define('NO_DIRECTORY', -11);

class PathLibrary {

    /**
     * Function to check the validity of a path string and extract it into
     * segments
     *
     * @param rodsaccount       Reference to the rodsaccount object
     * @param pathStart         The first part of any valid intake path,
     *                          i.e. the serverzone/home/intake-prefix
     * @param path              The path to split into its segments
     * @param dir[out]          ProdsDir object of the path, if the collection
     *                          exists
     * @return array            Array containing path segments starting after
     *                          the pathstart, if the path is a valid collection.
     *                          Error code otherwise
     */
    public function getPathSegments($rodsaccount, $pathStart, $path, &$dir) {
        $studyIDBegin = strpos(
            $path,
            $pathStart
        );

        if($studyIDBegin !== 0) {
            // error
            // echo "Not a valid intake folder";
            return DIR_NOT_INTAKE;
        } else {
            try {
                $dir = new ProdsDir($rodsaccount, $path, true);

                return explode("/", substr($path, strlen($pathStart)));


            } catch(RODSException $e) {
                return NO_DIRECTORY;
            }
        }
    }

    /**
     * Method that returns the pathstart based on the rods server zone
     * and the intake prefix both defined in config/config.php
     *
     * @param config        Reference to config array, as it is not
     *                      available in library context
     * @return string       String containing path start up to and
     *                      including the intake prefix
     */
    public function getPathStart($config) {
        /*
        return sprintf(
                "/%s/home/%s",
                $config->item('rodsServerZone'),
                $config->item('intake-prefix')
            );
        */

        return sprintf(
            "/%s/home",
            $config->item('rodsServerZone')
        );
    }

    /**
     * Method to find the level definitions as defined in the level hierarchy
     * in config/config.php
     *
     * @param config        Reference to config array, as it is not
     *                      available in library context
     * @param segments      The path segments as gotton from getPathSegments()
     * @param[out] level    The level definitions for the level the segments form
     * @param[out] depth    Integer indicating the level depth below home for the path
     */
    public function getCurrentLevelAndDepth($config, $segments, &$level, &$depth) {
        $depth = sizeof($segments) - 1;

        if(sizeof($config->item('level-hierarchy')) >= sizeof($segments)) {
            $level = $config->item('level-hierarchy')[$depth];
        } else {
            $level = $config->item('default-level');
        }
    }
}