<?php

/*
 * sync*gw SpamBot Bundle
 *
 * @copyright  http://syncgw.com, 2013 - 2020
 * @author     Florian Daeumling, http://syncgw.com
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */

use Contao\Backend;
use Contao\DataContainer;
use Contao\DC_Table;

$GLOBALS['TL_DCA']['tl_module']['config']['onload_callback'][] = [ 'tl_module_SpamBot', 'loadDCA' ];

// IP check palettes
$GLOBALS['TL_DCA']['tl_module']['palettes']['SpamBot-IP'] = 'name,type;'.
    '{spambot},spambot_engines,spambot_mod;'.
    '{spambot_output},spambot_page,spambot_msg;';

// Mail check palettes
$GLOBALS['TL_DCA']['tl_module']['palettes']['SpamBot-Mail'] = 'name,type;'.
    '{spambot},spambot_engines,spambot_mod;';

// engine names
$GLOBALS['TL_DCA']['tl_module']['fields']['spambot_engines'] = [
    'label'         => &$GLOBALS['TL_LANG']['tl_module']['spambot_engines'],
    'inputType'     => 'checkboxWizard',
    'eval'          => [ 'multiple' => true, 'submitOnChange' => true ],
    'options'       => [],
    'sql'           => 'blob NULL',
];

// modes
$GLOBALS['TL_DCA']['tl_module']['fields']['spambot_mod'] = [
    'label'         => &$GLOBALS['TL_LANG']['tl_module']['spambot_mod'],
    'inputType'     => 'select',
    'options'       => [ 1 => 'spambot_mod_first', 2 => 'spambot_mod_spam', 3 => 'spambot_mod_ham' ],
    'reference'     => &$GLOBALS['TL_LANG']['tl_module'],
    'sql'           => 'tinyint(1) NOT NULL default \'1\'',
];

// redirection
$GLOBALS['TL_DCA']['tl_module']['fields']['spambot_page'] = [
    'label'         => &$GLOBALS['TL_LANG']['tl_module']['spambot_page'],
    'exclude'       => true,
    'inputType'     => 'pageTree',
    'explanation'   => 'jumpTo',
    'eval'          => [ 'fieldType' => 'radio', 'tl_class' => 'clr' ],
    'sql'           => 'int(10) unsigned NOT NULL default \'0\'',
];

// message only
$GLOBALS['TL_DCA']['tl_module']['fields']['spambot_msg'] = [
    'label'         => &$GLOBALS['TL_LANG']['tl_module']['spambot_msg'],
    'exclude'       => true,
    'inputType'     => 'checkbox',
    'sql'           => 'tinyint(1) NOT NULL default \'0\'',
];

// Intern
$GLOBALS['TL_DCA']['tl_module']['SpamBot']['spambot_intern'] =
    '{spambot_internal},spambot_internal_exp,spambot_internal_logspam,spambot_internal_logham;';
$GLOBALS['TL_DCA']['tl_module']['fields']['spambot_internal_exp'] = [
    'label'         => &$GLOBALS['TL_LANG']['tl_module']['spambot_internal_exp'],
    'exclude'       => true,
    'inputType'     => 'text',
    'default'       => 2,
    'eval'          => [ 'maxlength' => 3, 'rgxp' => 'digit' ],
    'save_callback' => [[ 'tl_module_SpamBot', 'chkExpiration' ]],
    'sql'           => 'int(3) unsigned NOT NULL default \'0\'',
];
$GLOBALS['TL_DCA']['tl_module']['fields']['spambot_internal_logham'] = [
    'label'         => &$GLOBALS['TL_LANG']['tl_module']['spambot_internal_logham'],
    'default'       => 1,
    'inputType'     => 'checkbox',
    'sql'           => 'tinyint(1) NOT NULL default \'0\'',
];
$GLOBALS['TL_DCA']['tl_module']['fields']['spambot_internal_logspam'] = [
    'label'         => &$GLOBALS['TL_LANG']['tl_module']['spambot_internal_logspam'],
    'default'       => 1,
    'inputType'     => 'checkbox',
    'sql'           => 'tinyint(1) NOT NULL default \'0\'',
];

// Spamhaus
$GLOBALS['TL_DCA']['tl_module']['SpamBot']['spambot_spamhaus'] =
    '{spambot_spamhaus},spambot_spamhaus_mods;';
$GLOBALS['TL_DCA']['tl_module']['fields']['spambot_spamhaus_mods'] = [
    'label'         => &$GLOBALS['TL_LANG']['tl_module']['spambot_spamhaus_mods'],
    'exclude'       => true,
    'inputType'     => 'checkbox',
    'eval'          => [ 'multiple' => true ],
    'load_callback' => [[ 'tl_module_SpamBot', 'loadSH' ]],
    'sql'           => 'varchar(255) NOT NULL default \'\'',
];

// Honeypot
$GLOBALS['TL_DCA']['tl_module']['SpamBot']['spambot_honeypot'] =
    '{spambot_honeypot},spambot_honeypot_key,spambot_honeypot_score;';
$GLOBALS['TL_DCA']['tl_module']['fields']['spambot_honeypot_key'] = [
    'label'         => &$GLOBALS['TL_LANG']['tl_module']['spambot_honeypot_key'],
    'exclude'       => true,
    'inputType'     => 'text',
    'eval'          => ['minlength' => 12, 'maxlength' => 12, 'rgxp' => 'alpha'],
    'save_callback' => [[ 'tl_module_SpamBot', 'chkHoneypotKey' ]],
    'sql'           => 'varchar(12) NULL',
];
$GLOBALS['TL_DCA']['tl_module']['fields']['spambot_honeypot_score'] = [
    'label'         => &$GLOBALS['TL_LANG']['tl_module']['spambot_honeypot_score'],
    'exclude'       => true,
    'inputType'     => 'text',
    'default'       => 25,
    'eval'          => [ 'maxlength' => 3, 'rgxp' => 'digit' ],
    'save_callback' => [[ 'tl_module_SpamBot', 'chkHoneypotScore' ]],
    'sql'           => 'int(3) unsigned NOT NULL default \'0\'',
];

// StopForumSpam
$GLOBALS['TL_DCA']['tl_module']['SpamBot']['spambot_stopforumspam'] =
    '{spambot_stopforumspam},spambot_stopforumspam_score;';
$GLOBALS['TL_DCA']['tl_module']['fields']['spambot_stopforumspam_score'] = [
    'label'         => &$GLOBALS['TL_LANG']['tl_module']['spambot_stopforumspam_score'],
    'exclude'       => true,
    'inputType'     => 'text',
    'default'       => 95.5,
    'eval'          => [ 'maxlength' => 5, 'rgxp' => 'digit' ],
    'save_callback' => [[ 'tl_module_SpamBot', 'chkStopForumSpamScore' ]],
    'sql'           => 'decimal(12,2) NOT NULL default \'0.00\'',
];

// SORBS
$GLOBALS['TL_DCA']['tl_module']['SpamBot']['spambot_sorbs'] =
    '{spambot_sorbs},spambot_sorbs_mods;';
$GLOBALS['TL_DCA']['tl_module']['fields']['spambot_sorbs_mods'] = [
    'label'         => &$GLOBALS['TL_LANG']['tl_module']['spambot_sorbs_mods'],
    'exclude'       => true,
    'inputType'     => 'checkbox',
    'eval'          => [ 'multiple' => true ],
    'load_callback' => [[ 'tl_module_SpamBot', 'loadSORBS' ]],
    'sql'           => 'varchar(255) NOT NULL default \'\'',
];

// Blocklist
$GLOBALS['TL_DCA']['tl_module']['SpamBot']['spambot_blocklist'] =
    '{spambot_blocklist},spambot_blocklist_mods;';
$GLOBALS['TL_DCA']['tl_module']['fields']['spambot_blocklist_mods'] = [
    'label'         => &$GLOBALS['TL_LANG']['tl_module']['spambot_blocklist_mods'],
    'exclude'       => true,
    'inputType'     => 'checkbox',
    'eval'          => [ 'multiple' => true ],
    'load_callback' => [[ 'tl_module_SpamBot', 'loadBlocklist' ]],
    'sql'           => 'varchar(255) NOT NULL default \'\'',
];

// AHBL
$GLOBALS['TL_DCA']['tl_module']['SpamBot']['spambot_ahbl'] =
    '{spambot_ahbl},spambot_ahbl_mods;';
$GLOBALS['TL_DCA']['tl_module']['fields']['spambot_ahbl_mods'] = [
    'label'         => &$GLOBALS['TL_LANG']['tl_module']['spambot_ahbl_mods'],
    'exclude'       => true,
    'inputType'     => 'checkbox',
    'eval'          => [ 'multiple' => true ],
    'load_callback' => [[ 'tl_module_SpamBot', 'loadAHBL' ]],
    'sql'           => 'varchar(255) NOT NULL default \'\'',
];

// BotScout
$GLOBALS['TL_DCA']['tl_module']['SpamBot']['spambot_botscout'] =
    '{spambot_botscout},spambot_botscout_key,spambot_botscout_count;';
$GLOBALS['TL_DCA']['tl_module']['fields']['spambot_botscout_key'] = [
    'label'         => &$GLOBALS['TL_LANG']['tl_module']['spambot_botscout_key'],
    'exclude'       => true,
    'inputType'     => 'text',
    'eval'          => [ 'minlength' => 15, 'maxlength' => 15 ],
    'save_callback' => [[ 'tl_module_SpamBot', 'chkBotScoutKey' ]],
    'sql'           => 'varchar(20) NULL',
];
$GLOBALS['TL_DCA']['tl_module']['fields']['spambot_botscout_count'] = [
    'label'         => &$GLOBALS['TL_LANG']['tl_module']['spambot_botscout_count'],
    'exclude'       => true,
    'inputType'     => 'text',
    'default'       => 5,
    'eval'          => [ 'maxlength' => 3, 'rgxp' => 'digit' ],
    'save_callback' => [[ 'tl_module_SpamBot', 'chkBotScoutScore' ]],
    'sql'           => 'int(3) unsigned NOT NULL default \'0\'',
];

// FSpamList
$GLOBALS['TL_DCA']['tl_module']['SpamBot']['spambot_fspamlist'] =
    '{spambot_fspamlist},spambot_fspamlist_key,spambot_fspamlist_count;';
$GLOBALS['TL_DCA']['tl_module']['fields']['spambot_fspamlist_key'] = [
    'label'         => &$GLOBALS['TL_LANG']['tl_module']['spambot_fspamlist_key'],
    'exclude'       => true,
    'inputType'     => 'text',
    'eval'          => [ 'minlength' => 12, 'maxlength' => 15 ],
    'save_callback' => [[ 'tl_module_SpamBot', 'chkFSpamListKey' ]],
    'sql'           => 'varchar(20) NULL',
];
$GLOBALS['TL_DCA']['tl_module']['fields']['spambot_fspamlist_count'] = [
    'label'         => &$GLOBALS['TL_LANG']['tl_module']['spambot_fspamlist_count'],
    'exclude'       => true,
    'inputType'     => 'text',
    'default'       => 5,
    'eval'          => [ 'maxlength' => 3, 'rgxp' => 'digit' ],
    'save_callback' => [[ 'tl_module_SpamBot', 'chkFSpamListScore' ]],
    'sql'           => 'int(3) unsigned NOT NULL default \'0\'',
];

// IPStack
$GLOBALS['TL_DCA']['tl_module']['SpamBot']['spambot_ipstack'] =
    '{spambot_ipstack},spambot_ipstack_countries;';
$GLOBALS['TL_DCA']['tl_module']['fields']['spambot_ipstack_countries'] = [
    'label'         => &$GLOBALS['TL_LANG']['tl_module']['spambot_ipstack_countries'],
    'inputType'     => 'select',
    'options'       => $this->getCountries(),
    'eval'          => [ 'multiple' => true, 'size' => 8, 'tl_class' => 'w50 w50h', 'chosen' => true ],
    'sql'           => 'blob NULL',
];

class tl_module_SpamBot extends Backend
{
    protected $Engines;

    /**
     * Load data container
     *
     * @param DC_Table $dc
     */
    public function loadDCA(DC_Table $dc)
    {
        $rc = $dc->Database->prepare('SELECT type,spambot_engines FROM tl_module WHERE id=?')->execute($dc->id);
        if (!$rc->spambot_engines) {
            $this->Engines = [];
        } else {
            $this->Engines = deserialize($rc->spambot_engines);
        }

        // update palette
        foreach ($this->Engines as $name) {
            $GLOBALS['TL_DCA']['tl_module']['palettes'][$rc->type] .=
                $GLOBALS['TL_DCA']['tl_module']['SpamBot']['spambot_'.strtolower($name)];
        }

        // Spamhaus options (default will be set later)
        $GLOBALS['TL_DCA']['tl_module']['fields']['spambot_spamhaus_mods']['options'] =
            $GLOBALS['SpamBot']['Engines']['Spamhaus']['Codes'];
        // SORBS options (default will be set later)
        $GLOBALS['TL_DCA']['tl_module']['fields']['spambot_blocklist_mods']['options'] =
            $GLOBALS['SpamBot']['Engines']['Blocklist']['Codes'];
        // Blocklist options (default will be set later)
        $GLOBALS['TL_DCA']['tl_module']['fields']['spambot_sorbs_mods']['options'] =
            $GLOBALS['SpamBot']['Engines']['SORBS']['Codes'];
        // AHBL options (default will be set later)
        $GLOBALS['TL_DCA']['tl_module']['fields']['spambot_ahbl_mods']['options'] =
            $GLOBALS['SpamBot']['Engines']['AHBL']['Codes'];

        // load engines
        $func = 'SpamBot-IP' === $rc->type ? 'CheckIP' : 'CheckMail';
        // engines
        foreach ($GLOBALS['SpamBot']['Engines'] as $k => $v) {
            if (!isset($v[$func])) {
                continue;
            }
            $GLOBALS['TL_DCA']['tl_module']['fields']['spambot_engines']['options'][$k] = $v['HomePage'];
        }

        // it is required to save default, because only if default is changed value will be saved later
        $this->Database->prepare('UPDATE tl_module SET spambot_engines=? WHERE id=?')
                        ->execute(serialize($this->Engines), $dc->id);
    }

    /**
     * Load Spamhaus default values
     *
     * @param mixed         $varValue
     * @param DataContainer $dc
     *
     * @return array
     */
    public function loadSH($varValue, DataContainer $dc)
    {
        if ($varValue) {
            return $varValue;
        }

        // it is required to save default, because only if default is changed value will be saved
        $this->Database->prepare('UPDATE tl_module SET spambot_spamhaus_mods=? WHERE id=?')
                        ->execute(serialize($GLOBALS['SpamBot']['Engines']['Spamhaus']['Spam']), $dc->id);

        return serialize($GLOBALS['SpamBot']['Engines']['Spamhaus']['Spam']);
    }

    /**
     * Load SORBS default values
     *
     * @param mixed         $varValue
     * @param DataContainer $dc
     *
     * @return array
     */
    public function loadSORBS($varValue, DataContainer $dc)
    {
        if ($varValue) {
            return $varValue;
        }

        // it is required to save default, because only if default is changed value will be saved
        $this->Database->prepare('UPDATE tl_module SET spambot_sorbs_mods=? WHERE id=?')
                        ->execute(serialize($GLOBALS['SpamBot']['Engines']['SORBS']['Spam']), $dc->id);

        return serialize($GLOBALS['SpamBot']['Engines']['SORBS']['Spam']);
    }

    /**
     * Load Blocklist default values
     *
     * @param mixed         $varValue
     * @param DataContainer $dc
     *
     * @return array
     */
    public function loadBlocklist($varValue, DataContainer $dc)
    {
        if ($varValue) {
            return $varValue;
        }

        // it is required to save default, because only if default is changed value will be saved
        $this->Database->prepare('UPDATE tl_module SET spambot_blocklist_mods=? WHERE id=?')
                        ->execute(serialize($GLOBALS['SpamBot']['Engines']['Blocklist']['Spam']), $dc->id);

        return serialize($GLOBALS['SpamBot']['Engines']['Blocklist']['Spam']);
    }

    /**
     * Load AHBL default values
     *
     * @param mixed         $varValue
     * @param DataContainer $dc
     *
     * @return array
     */
    public function loadAHBL($varValue, DataContainer $dc)
    {
        if ($varValue) {
            return $varValue;
        }

        // it is required to save default, because only if default is changed value will be saved
        $this->Database->prepare('UPDATE tl_module SET spambot_ahbl_mods=? WHERE id=?')
                        ->execute(serialize($GLOBALS['SpamBot']['Engines']['AHBL']['Spam']), $dc->id);

        return serialize($GLOBALS['SpamBot']['Engines']['AHBL']['Spam']);
    }

    /**
     * Check cache expiration
     *
     * @param string   $varValue
     * @param DC_Table $dc
     *
     * @throws Exception
     *
     * @return string
     */
    public function chkExpiration($varValue, DC_Table $dc)
    {
        if (in_array('Intern', $this->Engines, true)) {
            if (!strlen($varValue)) {
                throw new Exception($GLOBALS['TL_LANG']['tl_module']['spambot_internal_exp'][1]);
            }
            if ($varValue < 1) {
                throw new Exception($GLOBALS['TL_LANG']['tl_module']['spambot_val0'].'<br />'.
                                    $GLOBALS['TL_LANG']['tl_module']['spambot_internal_exp'][1]);
            }
            if ($varValue > 365) {
                throw new Exception($GLOBALS['TL_LANG']['tl_module']['spambot_val365'].'<br />'.
                                    $GLOBALS['TL_LANG']['tl_module']['spambot_internal_exp'][1]);
            }
        }

        return $varValue;
    }

    /**
     * Check Honeypot API key
     *
     * @param string   $varValue
     * @param DC_Table $dc
     *
     * @throws Exception
     *
     * @return string
     */
    public function chkHoneypotKey($varValue, DC_Table $dc)
    {
        if (in_array('Honeypot', $this->Engines, true)) {
            if (!strlen($varValue)) {
                throw new Exception($GLOBALS['TL_LANG']['tl_module']['spambot_honeypot_key'][1]);
            }
        }

        return $varValue;
    }

    /**
     * Check Honeypot score value
     *
     * @param string   $varValue
     * @param DC_Table $dc
     *
     * @throws Exception
     *
     * @return string
     */
    public function chkHoneypotScore($varValue, DC_Table $dc)
    {
        if (in_array('Honeypot', $this->Engines, true)) {
            if (!strlen($varValue)) {
                throw new Exception($GLOBALS['TL_LANG']['tl_module']['spambot_honeypot_score'][1]);
            }
        }

        return $varValue;
    }

    /**
     * Check StopForumSpam score value
     *
     * @param string   $varValue
     * @param DC_Table $dc
     *
     * @throws Exception
     *
     * @return string
     */
    public function chkStopForumSpamScore($varValue, DC_Table $dc)
    {
        if (in_array('StopForumSpam', $this->Engines, true)) {
            if (!strlen($varValue)) {
                throw new Exception($GLOBALS['TL_LANG']['tl_module']['spambot_stopforumspam_score'][1]);
            }
        }

        return $varValue;
    }

    /**
     * Check BotScout API key
     *
     * @param string   $varValue
     * @param DC_Table $dc
     *
     * @throws Exception
     *
     * @return string
     */
    public function chkBotScoutKey($varValue, DC_Table $dc)
    {
        if (in_array('BotScout', $this->Engines, true)) {
            if (!strlen($varValue)) {
                throw new Exception($GLOBALS['TL_LANG']['tl_module']['spambot_botscout_key'][1]);
            }
        }

        return $varValue;
    }

    /**
     * Check BotScout score value
     *
     * @param string   $varValue
     * @param DC_Table $dc
     *
     * @throws Exception
     *
     * @return string
     */
    public function chkBotScoutScore($varValue, DC_Table $dc)
    {
        if (in_array('BotScout', $this->Engines, true)) {
            if (!strlen($varValue)) {
                throw new Exception($GLOBALS['TL_LANG']['tl_module']['spambot_botscout_score'][1]);
            }
        }

        return $varValue;
    }

    /**
     * Check FSpamList API key
     *
     * @param string   $varValue
     * @param DC_Table $dc
     *
     * @throws Exception
     *
     * @return string
     */
    public function chkFSpamListKey($varValue, DC_Table $dc)
    {
        if (in_array('FSpamList', $this->Engines, true)) {
            if (!strlen($varValue)) {
                throw new Exception($GLOBALS['TL_LANG']['tl_module']['spambot_fspamlist_key'][1]);
            }
        }

        return $varValue;
    }

    /**
     * Check FSpamList score value
     *
     * @param string   $varValue
     * @param DC_Table $dc
     *
     * @throws Exception
     *
     * @return string
     */
    public function chkFSpamListScore($varValue, DC_Table $dc)
    {
        if (in_array('FSpamList', $this->Engines, true)) {
            if (!strlen($varValue)) {
                throw new Exception($GLOBALS['TL_LANG']['tl_module']['spambot_fspamlist_score'][1]);
            }
        }

        return $varValue;
    }
}
