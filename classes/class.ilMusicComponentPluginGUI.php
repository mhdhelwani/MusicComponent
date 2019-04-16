<?php
include_once("./Services/COPage/classes/class.ilPageComponentPluginGUI.php");

/**
 * @author Mohammed Helwani <mohammed.helwani@llz.uni-halle.de>
 * @version $Id$
 *
 * @ilCtrl_isCalledBy ilMusicComponentPluginGUI: ilPCPluggedGUI
 */
class ilMusicComponentPluginGUI extends ilPageComponentPluginGUI
{
    /**
     * @var ilMusicComponentPlugin    The plugin object
     */
    var $plugin = null;

    /**
     * @var ilCtrl
     */
    protected $ctrl;

    /**
     * @var ilTemplate
     */
    protected $tpl;

    /**
     * ilMusicComponentPluginGUI constructor
     */
    public function __construct()
    {
        parent::__construct();

        global $ilCtrl, $tpl;

        $this->ctrl = $ilCtrl;
        $this->tpl = $tpl;
        $this->plugin = $this->getPlugin();
    }


    /**
     *
     */
    public function executeCommand()
    {
        $next_class = $this->ctrl->getNextClass();

        switch ($next_class) {
            default:
                // perform valid commands
                $cmd = $this->ctrl->getCmd();
                if (in_array($cmd, array("create", "save", "edit", "edit2", "update", "cancel"))) {
                    $this->$cmd();
                }
                break;
        }
    }

    public function insert()
    {
        $form = $this->initForm(true);
        $this->tpl->setContent($form->getHTML());
    }

    /**
     * @param bool|false $a_create
     * @return ilPropertyFormGUI
     */
    public function initForm($a_create = false)
    {
        include_once("Services/Form/classes/class.ilPropertyFormGUI.php");
        $form = new ilPropertyFormGUI();

        $title = new ilTextInputGUI($this->plugin->txt('title'), "title");
        $title->setMaxLength(40);
        $title->setSize(40);
        $title->setRequired(true);
        $form->addItem($title);

        $this->tpl->addJavaScript($this->plugin->getDirectory() . '/js/jquery.klavier.min.js');
        $this->tpl->addJavaScript($this->plugin->getDirectory() . '/js/vexflow.min.js');
        $this->tpl->addJavaScript($this->plugin->getDirectory() . '/js/notelex.js');
        $this->tpl->addCss($this->plugin->getDirectory() . '/css/musiccomponent.css');

        $item = new ilCustomInputGUI("");
        $template = $this->plugin->getTemplate('tpl.edit.html');
        $template->setVariable("selectedValues", "[]");

        if (!$a_create) {
            $prop = $this->getProperties();
            $title->setValue($prop["title"]);
            $template->setVariable("selectedValues", $prop["selectedValues"]);
        }

        $item->setHTML($template->get());
        $form->addItem($item);



        if ($a_create) {
            $this->addCreationButton($form);
            $form->addCommandButton("cancel", $this->lng->txt("cancel"));
            $form->setTitle($this->getPlugin()->txt("cmd_insert"));
        } else {
            $form->addCommandButton("update", $this->lng->txt("save"));
            $form->addCommandButton("cancel", $this->lng->txt("cancel"));
            $form->setTitle($this->getPlugin()->txt("edit_ex_el"));
        }

        $form->setFormAction($this->ctrl->getFormAction($this));

        return $form;
    }

    /**
     * Save new music component
     */
    public function create()
    {
        $form = $this->initForm(true);
        if ($form->checkInput()) {
            $properties = array(
                "title" => $form->getInput("title"),
                "ID" => uniqid(''),
                "selectedValues" => $form->getInput("selectedValues")
            );
            if ($this->createElement($properties)) {
                ilUtil::sendSuccess($this->lng->txt("msg_obj_modified"), true);
                $this->returnToParent();
            }
        }

        $form->setValuesByPost();
        $this->tpl->setContent($form->getHtml());
    }

    function edit()
    {
        $form = $this->initForm();
        $this->tpl->setContent($form->getHTML());
    }

    function update()
    {
        $form = $this->initForm(true);
        if ($form->checkInput()) {
            $properties = array(
                "title" => $form->getInput("title"),
                "ID" => uniqid(''),
                "selectedValues" => $form->getInput("selectedValues")
            );
            if ($this->updateElement($properties)) {
                ilUtil::sendSuccess($this->lng->txt("msg_obj_modified"), true);
                $this->returnToParent();
            }
        }

        $form->setValuesByPost();
        $this->tpl->setContent($form->getHtml());

    }

    /**
     * Cancel
     */
    function cancel()
    {
        $this->returnToParent();
    }

    /**
     * Get HTML for element
     * @param       $a_mode
     * @param array $a_properties
     * @param       $a_plugin_version
     * @return string $html
     */
    function getElementHTML($a_mode, array $a_properties, $a_plugin_version)
    {
        $tpl = $this->plugin->getTemplate("tpl.content.html");
        $tpl->setVariable("ID", $a_properties['ID']);
        $tpl->setVariable("selectedValues", $a_properties['selectedValues']);
        return $tpl->get();
    }
}
