<?php
declare(strict_types=1);

/*
 * sync*gw SpamBot Bundle
 *
 * @copyright  https://syncgw.com, 2013 - 2023
 * @author     Florian Daeumling, https://syncgw.com
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */

use Contao\Backend;
use Contao\DataContainer;
use Contao\DC_Table;
use Contao\Image;
use syncgw\SpamBotBundle\Module\SpamBot;

$GLOBALS['TL_DCA']['tl_spambot'] = [
    'config' => [
        'dataContainer'             => 'Table',
        'onload_callback'           => [[ 'tl_spambot', 'loadDCA' ]],
        'sql' => [
            'keys' => [
                'id'                => 'primary',
                'ip'                => 'index',
                'mail'              => 'index',
            ]
        ],
    ],

    'palettes' => [
        'default'                   => 'module,mail,ip,typ,created,tstamp,browser,status',
    ],

    'list' => [
        'sorting' => [
            'mode'                  => 2,
            'flag'                  => 1,
            'fields'                => ['tstamp DESC'],
            'panelLayout'           => 'filter;sort,search,limit',
        ],
        'label' => [
            'fields'                => ['ip', 'mail', 'tstamp', 'created', 'typ', 'status'],
            'showColumns'           => TRUE,
            'label_callback'        => [ 'tl_spambot', 'mkLabel' ],
        ],
        'global_operations' => [
            'all' => [
                'label'             => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href'              => 'act=select',
                'class'             => 'header_edit_all',
                'attributes'        => 'onclick="Backend.getScrollOffset()" accesskey="e"',
            ],
            'cleartab' => [
                'label'             => &$GLOBALS['TL_LANG']['tl_spambot']['clear'],
                'href'              => 'key=clearTab',
                'class'             => 'spambot_clear',
                'attributes'        => 'onclick="if(!confirm(\''.$GLOBALS['TL_LANG']['tl_spambot']['confirm'].'\'))return FALSE;Backend.getScrollOffset()" accesskey="c"',
            ],
        ],
        'operations' => [
            'edit' => [
                'label'             => &$GLOBALS['TL_LANG']['tl_spambot']['edit'],
                'href'              => 'act=edit',
                'icon'              => 'edit.svg',
            ],
            'copy' => [
                'label'             => &$GLOBALS['TL_LANG']['tl_spambot']['copy'],
                'href'              => 'act=copy',
                'icon'              => 'copy.svgf',
            ],
            'delete' => [
                'label'             => &$GLOBALS['TL_LANG']['tl_spambot']['delete'],
                'href'              => 'act=delete',
                'icon'              => 'delete.svg',
                'attributes'        => 'onclick="if(!confirm(\''.$GLOBALS['TL_LANG']['MSC']['deleteConfirm'].'\'))return FALSE;Backend.getScrollOffset()"',
            ],
            'show' => [
                'attributes'        => 'style= "display:none;"',
                'icon'              => 'show.svg',
            ],
            'check' => [
                'label'             => &$GLOBALS['TL_LANG']['tl_spambot']['check'],
                'icon'              => 'bundles/spambot/images/check.png',
                'button_callback'   => [ 'tl_spambot', 'mkChkButton' ],
            ],
        ],
    ],

    'fields' => [
       'id' => [
            'sql'               => "int(10) unsigned NOT NULL auto_increment",
        ],
        'module' => [
            'label'             => &$GLOBALS['TL_LANG']['tl_spambot']['module'],
            'filter'            => TRUE,
            'inputType'         => 'select',
            'options'           => [],
            'eval'              => [ 'submitOnChange' => TRUE ],
            'sql'               => "int(10) unsigned NOT NULL default '0'",
        ],
        'hidden' => [
            'sql'               => "varchar(255) NULL",
        ],
        'ip' => [
            'label'             => &$GLOBALS['TL_LANG']['tl_spambot']['ip'],
            'search'            => TRUE,
            'sorting'           => TRUE,
            'inputType'         => 'text',
            'default'           => ' ',
            'eval'              => [ 'maxlength' => 40, 'nospace' => TRUE, 'decodeEntities' => TRUE, 'tl_class' => 'w50' ],
            'sql'               => "varchar(40) NULL",
        ],
        'mail' => [
            'label'             => &$GLOBALS['TL_LANG']['tl_spambot']['mail'],
            'search'            => TRUE,
            'sorting'           => TRUE,
            'inputType'         => 'text',
            'default'           => ' ',
            'eval'              => [ 'maxlength' => 254, 'nospace' => TRUE, 'decodeEntities' => TRUE, 'tl_class' => 'w50' ],
            'sql'               => "varchar(255) NULL",
        ],
        'typ' => [
            'label'             => &$GLOBALS['TL_LANG']['tl_spambot']['typ'],
            'filter'            => TRUE,
            'sorting'           => TRUE,
            'inputType'         => 'select',
            'options'           => SpamBot::$Status,
            'default'           => SpamBot::NOTFOUND,
            'eval'              => [ 'mandatory' => TRUE, 'tl_class' => 'clr' ],
            'options_callback'  => [ 'tl_spambot', 'getTypes' ],
            'sql'               => "tinyint(1) NOT NULL default '0'",
        ],
        'created' => [
            'label'             => &$GLOBALS['TL_LANG']['tl_spambot']['created'],
            'filter'            => TRUE,
            'sorting'           => TRUE,
            'inputType'         => 'text',
            'flag'              => 6,
            'eval'              => [ 'mandatory' => TRUE, 'datepicker' => TRUE, 'rgxp' => 'datim', 'tl_class' => 'w50' ],
            'load_callback'     => [[ 'tl_spambot', 'getCreate' ]],
            'sql'               => "int(10) unsigned NOT NULL default '0'",
        ],
        'tstamp' => [
            'label'             => &$GLOBALS['TL_LANG']['tl_spambot']['tstamp'],
            'filter'            => TRUE,
            'sorting'           => TRUE,
            'inputType'         => 'text',
            'flag'              => 6,
            'eval'              => [ 'mandatory' => TRUE, 'datepicker' => TRUE, 'rgxp' => 'datim', 'tl_class' => 'w50' ],
            'sql'               => "int(10) unsigned NOT NULL default '0'",
        ],
        'browser' => [
            'label'             => &$GLOBALS['TL_LANG']['tl_spambot']['browser'],
            'default'           => Environment::get('httpUserAgent'),
            'inputType'         => 'text',
            'eval'              => [ 'maxlength' => 255, 'class' => 'spambot_input', 'tl_class' => 'clr' ],
            'sql'               => "varchar(255) NULL",
        ],
        'status' => [
            'label'             => &$GLOBALS['TL_LANG']['tl_spambot']['status'],
            'inputType'         => 'text',
            'eval'              => [ 'maxlength' => 255, 'class' => 'spambot_input' ],
            'sql'               => "varchar(255) NULL",
        ],
    ],
];

class tl_spambot extends Backend {

    /*
     * @var array()
     */
    protected static $ModTyp = [];

    /**
     * Load data container
     */
    public function loadDCA(DC_Table $dc): void {

        // edit / new record?
        if (!$this->Input->get('act'))
            $GLOBALS['TL_DCA']['tl_spambot']['fields']['module']['options'][0] = 'Loaded';

        // load module list
        $rc = $this->Database->prepare('SELECT id,name,type,spambot_engines FROM tl_module WHERE type LIKE ?')->execute('SpamBot-%');
        while ($rc->next()) {
            // set module names
            $GLOBALS['TL_DCA']['tl_spambot']['fields']['module']['options'][$rc->id] = $rc->name;
            // save module type
            self::$ModTyp[$rc->id] = 'SpamBot-IP' === $rc->type ? 'IP' : 'Mail';
        }
    }


    /**
     * Convert label
     */
    public function mkLabel(array $row, string $label, DataContainer $dc, array $args): array {

        // convert "typ"
        $args[4] = '<img class="list_icon_new" src="'.TL_SCRIPT_URL.'bundles/spambot/images/'.strtolower($args[4]).
                   '.png" title="'.$args[4].'" alt="'.$args[4].'">';

        return $args;
    }

    /**
     * Truncate entire table
     */
    public function clearTab(): void {
        $this->Database->execute('TRUNCATE tl_spambot');
        $this->redirect(\Environment::get('script').'?do=SpamBot');
    }

    /**
     * Make "Check" icon
     */
    public function mkChkButton(array $row, $href, string $label, string $title, string $icon, string $attributes): string {

            // check available data
        if ('IP' === self::$ModTyp[$row['module']])
            $ok = $row['ip'] ? TRUE : FALSE;
        else
            $ok = $row['mail'] ? TRUE : FALSE;

        $href = 'id='.$row['id'].'&amp;key=check'.self::$ModTyp[$row['module']];
        if (!($row['typ'] & (SpamBot::BLACKL | SpamBot::WHITEL | SpamBot::LOADED)) && $ok)
            return '<a href="'.$this->addToUrl($href).'" title="'.specialchars($title).'"'.$attributes.'>'.
                   Image::getHtml($icon, $label).'</a> ';

        return Image::getHtml(str_replace('check', 'nocheck', $icon), $label);
    }

    /**
     * Get creation date
     */
    public function getCreate(string $varValue, DC_Table $dc): string {
        return !$varValue ? strval(time()) : $varValue;
    }

    /**
     * Get all available record types
     */
    public function getTypes(DC_Table $dc): array {
        return array_slice(SpamBot::$Status, 1, count(SpamBot::$Status) - 2, TRUE);
    }

    /**
     * Check IP address
     */
    public function checkIP(DC_Table $dc): string {
        return self::_doCheck(SpamBot::TYP_IP, $dc->id);
    }

    /**
     * Check mail address
     */
    public function checkMail(DC_Table $dc): string {
        return self::_doCheck(SpamBot::TYP_MAIL, $dc->id);
    }

    /**
     * Perform checking
     */
    private function _doCheck(int $func, int $id): string {

        // get module ID from page record
        $rec = $this->Database->prepare('SELECT ip,mail,module FROM tl_spambot WHERE id=?')->execute($id);
        $obj = new SpamBot($rec->module);

        // clean URI
        $pi = $this->Input->get('xi');
        $pr = $this->Input->get('rc');
        $uri = str_replace(['&rc=1', '&xi=1'], [NULL, NULL], Environment::get('request'));

        // check data
        // error test IP
        // $rc = $obj->callMods($func, '127.0.0.2', NULL, $pi + 1);
        $rc = $obj->callMods($func, $rec->ip, $rec->mail, $pi + 1);
        // do we perform a re-check?
        if ($pr && ($time = $this->Input->cookie('SpamBotRun'))) {
            $time = deserialize($time);
            ++$time['RUNS'];
        } else
            $time = ['RUNS' => 1];

        // create output
        $style = [SpamBot::NOTFOUND => 'notfound',
                  SpamBot::WHITEL   => 'whitelist',
                  SpamBot::HAM      => 'ham',
                  SpamBot::SPAM     => 'spam',
                  SpamBot::BLACKL   => 'blacklist',
                  SpamBot::LOADED   => 'loaded',
        ];

        $out = '<div id="tl_buttons">
<a href="'.str_replace('&key='.(SpamBot::TYP_IP === $func ? 'checkIP' : 'checkMail'), NULL, $uri).'" class="header_back" title="'.
specialchars($GLOBALS['TL_LANG']['MSC']['backBT']).'" accesskey="b">'.$GLOBALS['TL_LANG']['MSC']['backBT'].'</a>'.
'</div><h2 class="sub_headline">';
        if (SpamBot::TYP_IP === $func) {
            $out .= sprintf($GLOBALS['TL_LANG']['tl_spambot']['typ_ip'], $rec->ip);
        } else {
            $out .= sprintf($GLOBALS['TL_LANG']['tl_spambot']['typ_mail'], $rec->mail);
        }
        $out .= '</h2><table class="spambot_table"><tbody>';
        foreach ($rc as $name => $v) {
            if (FALSE === strpos($v[1], '<field'))
                $v[1] .= '<br />';
            // save execution time
            $time[$name] += $v[2];
            $avg = round($time[$name] / $time['RUNS'], 3);
            $typ = $style[$v[0]];
            $out .= '<tr class="spambot_tr_'.$typ.'">'.
                    '<td class="spambot_td_'.$typ.'" style="width:30px">'.
                        '<img class="list_icon_new" src="bundles/spambot/images/'.$typ.'.png" '.
                        'title="'.SpamBot::$Status[$v[0]].'" alt="'.SpamBot::$Status[$v[0]].'">'.
                    '</td>'.
                    '<td class="spambot_td_'.$typ.'" style="width:240px">';
            if (SpamBot::TYP_IP === $func)
                $out .= sprintf($GLOBALS['SpamBot']['Engines'][$name]['CheckIP'], $rec->ip);
            else
                $out .= sprintf($GLOBALS['SpamBot']['Engines'][$name]['CheckMail'], $rec->mail);
            $out .= '</td>'.
                    '<td class="spambot_td_'.$typ.'">'.('Intern' === $name ? $v[1] : substr($v[1], strpos($v[1], ':') + 1)).
                    sprintf($GLOBALS['TL_LANG']['tl_spambot']['lab_time'], $v[2]).'<br />'.
                    ($pr ? sprintf($GLOBALS['TL_LANG']['tl_spambot']['lab_avt'], $avg) : NULL).
                    '</td></tr>';
        }
        $out .= '</tbody></table>';
        // save execution time
        $this->setCookie('SpamBotRun', serialize($time),
                         time() + $GLOBALS['TL_CONFIG']['sessionTimeout'], $GLOBALS['TL_CONFIG']['websitePath']);
        // set buttons
        return $out.'<div class="tl_submit_container">'.
                '<input class="tl_submit" type="button" value="'.$GLOBALS['TL_LANG']['tl_spambot']['recheck'][0].'" '.
                'title="'.$GLOBALS['TL_LANG']['tl_spambot']['recheck'][1].
                '" onclick="window.location=\''.$uri.'&rc=1\'"> '.
                '<input class="tl_submit" type="button" value="'.$GLOBALS['TL_LANG']['tl_spambot']['extinfo'][0].'" '.
                'title="'.$GLOBALS['TL_LANG']['tl_spambot']['extinfo'][1].
                '" onclick="window.location=\''.$uri.'&rc=1&xi=1\'"></div>';
    }
}
