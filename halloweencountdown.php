<?php
if (!defined('_TB_VERSION_')) {
    exit;
}

/**
 * Halloween Countdown
 */
class HalloweenCountdown extends Module
{

    public function __construct()
    {
        $this->name = 'halloweencountdown';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Michael Rouse';
        $this->need_instance = 0;

        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->l('Halloween Countdown');
        $this->description = $this->l('Displays a halloween countdown in your footer');
        $this->tb_versions_compliancy = '> 1.0.0';
        $this->tb_min_version = '1.0.0';
        $this->ps_versions_compliancy = ['min' => '1.6', 'max' => '1.6.99.99'];
    }


    /**
     * Renders the display in the footer
     */
    public function hookDisplayFooter()
    {
        $todaysDate = date_create(date("Y/m/d"));
        $nextHalloween = date_create(date("Y/10/31", (date("m") > 10) ? strtotime('+1 year') : strtotime('+0 minute')));

        $isHalloween = date("m/d") == "10/31";

        $dateDiff = date_diff($todaysDate, $nextHalloween);
        $daysUntilHalloween = $dateDiff->format('%a');

        $daysWord = ($daysUntilHalloween > 1) ? "days" : "day";
        $daysWord = $this->l($daysWord);

        $halloweenCountdown = "{$daysUntilHalloween} {$daysWord} " . $this->l("until Halloween!");

        if ($isHalloween)
            $halloweenCountdown = $this->l("Happy Halloween!");

        $isHalfway = $daysUntilHalloween == 183;

        if ($isHalloween)
            $halloweenCountdown = $this->l("Halfway to Halloween!");

        $spookyEmojiList = ['🎃','👻','🦇','🧛‍♂️','💀','🧟‍♂️','🕷','🕸','⚰️'];
        $spookyEmoji = $spookyEmojiList[array_rand($spookyEmojiList)];

        $this->smarty->assign([
            'days_until_halloween' => $daysUntilHalloween,
            'halloween_countdown' => $halloweenCountdown,
            'is_halloween' => $isHalloween,
            'is_halfway_to_halloween' => $isHalfway,
            'spooky_emoji' => $spookyEmoji
        ]);

        return $this->display(__FILE__, 'halloweenCountdown.tpl');
    }




    public function install()
    {
        if (!parent::install()) {
            return false;
        }

        $this->registerHook('displayFooter');

        return true;
    }

    public function uninstall()
    {
        return parent::uninstall();
    }
}