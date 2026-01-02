<?php
class JamiiforumsBridge extends BridgeAbstract {

    const NAME = 'Jamiiforums – Threads Mpya';
    const URI = 'https://www.jamiiforums.com/';
    const DESCRIPTION = 'RSS feed ya threads mpya kutoka Jamiiforums';

    public function collectData() {
        $html = getSimpleHTMLDOM(self::URI)
            or returnServerError('Imeshindwa kufungua Jamiiforums');

        /*
         * Jamiiforums (XenForo):
         * Threads ziko kwenye: a.PreviewTooltip
         */
        foreach ($html->find('a.PreviewTooltip') as $a) {

            if (!isset($a->href)) {
                continue;
            }

            // Hakikisha ni thread link
            if (strpos($a->href, '/threads/') === false) {
                continue;
            }

            $item = [];

            // Title
            $item['title'] = trim($a->plaintext);

            // Link kamili
            $item['uri'] = urljoin(self::URI, $a->href);

            // Content (muhtasari)
            $item['content'] = $item['title'];

            $this->items[] = $item;
        }

        // Ondoa duplicates
        $this->items = array_unique($this->items, SORT_REGULAR);
    }
}
