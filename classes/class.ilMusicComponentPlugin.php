<?php

include_once("./Services/COPage/classes/class.ilPageComponentPlugin.php");

/**
 * @author Mohammed Helwani <mohammed.helwani@llz.uni-halle.de>
 * @version $Id$
 *
 */
class ilMusicComponentPlugin extends ilPageComponentPlugin
{

    const PLUGIN_ID = "music";
    const PLUGIN_NAME = "MusicComponent";
    /**
     * @var self|null
     */
    protected static $instance = NULL;

    /**
     * ilMusicComponentPlugin constructor
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return self
     */
    public static function getInstance()
    {
        if (self::$instance === NULL) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @return string
     */
    public function getPluginName()
    {
        return self::PLUGIN_NAME;
    }


    /**
     * @param string $a_type
     *
     * @return bool
     */
    public function isValidParentType($a_type)
    {
        // Allow in all parent types
        return true;
    }

    /**
     * Get Javascript files
     */
    function getJavascriptFiles($a_mode)
    {
        return array("js/jquery.klavier.min.js", "js/vexflow.min.js", "js/notelex.js");
    }

    /**
     * Get css files
     */
    function getCssFiles($a_mode)
    {
        return array("css/musiccomponent.css");
    }
}
