<?php
class SimuNzuriBridge extends BridgeAbstract {

    const NAME = 'SimuNzuri.com – Makala Mpya';
    const URI = 'https://simunzuri.com/';
    const DESCRIPTION = 'RSS feed ya makala zote mpya kutoka SimuNzuri.com';

    public function collectData() {
        $html = getSimpleHTMLDOM(self::URI)
            or returnServerError('Imeshindwa kufungua SimuNzuri.com');

        /*
         * SimuNzuri ni WordPress:
         * posts ziko ndani ya <article>
         */
        foreach ($html->find('article') as $article) {

            $titleEl = $article->find('h2 a, h3 a', 0);
            if (!$titleEl) {
                continue;
            }

            $item = [];

            // Title
            $item['title'] = trim($titleEl->plaintext);

            // Link
            $item['uri'] = $titleEl->href;

            // Content
            $content = '';

            // Featured image
            $img = $article->find('img', 0);
            if ($img && isset($img->src)) {
                $content .= '<img src="' . $img->src . '"><br>';
            }

            // Excerpt
            $excerpt = $article->find('p', 0);
            if ($excerpt) {
                $content .= '<p>' . $excerpt->plaintext . '</p>';
            }

            $item['content'] = $content;

            $this->items[] = $item;
        }
    }
}
