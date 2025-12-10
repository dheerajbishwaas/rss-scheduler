<?php

namespace App\Controllers;

use App\Models\PostModel;
use SimplePie;

class RssController extends BaseController
{
    public function import()
    {
        return view('rss/import_form');
    }

    public function fetch()
    {
        $url = $this->request->getPost('rss_url');
        $sortMode = $this->request->getPost('sort_mode'); // asc or desc

        if (! filter_var($url, FILTER_VALIDATE_URL)) {
            return redirect()->back()->with('error', 'Invalid RSS URL');
        }

        // Initialize SimplePie
        $feed = new SimplePie();
        $feed->set_feed_url($url);
        $feed->enable_cache(false);
        $feed->init();

        if ($feed->error()) {
            return redirect()->back()->with('error', $feed->error());
        }

        $items = $feed->get_items();
        if (!$items) {
            return redirect()->back()->with('error', 'No items found in feed.');
        }

        // Convert SimplePie items to array
        $posts = [];
        foreach ($items as $item) {
            $title = $item->get_title() ?? '';
            $content = $item->get_content() ?? '';
            $pubDate = $item->get_date('Y-m-d H:i:s');
            $image = $this->extractImageFromItem($item);
            
            // Correct emoji/special char count
            $count = grapheme_strlen(strip_tags($title));

            $posts[] = [
                'title'      => $title,
                'content'    => $content,
                'char_count' => $count,
                'pub_date'   => $pubDate,
                'image_url'  => $image,
            ];
        }

        // Sorting for priority
        if ($sortMode === 'asc') {
            usort($posts, fn($a, $b) => strtotime($a['pub_date']) <=> strtotime($b['pub_date']));
        } else {
            usort($posts, fn($a, $b) => strtotime($b['pub_date']) <=> strtotime($a['pub_date']));
        }

        // Assign priorities
        foreach ($posts as $i => &$p) {
            $p['priority'] = $i + 1;
        }

        // Save in DB
        $postModel = new PostModel();
        foreach ($posts as $p) {
            $postModel->insert($p);
        }

        return redirect()->back()->with('success', 'Feed imported successfully!');
    }

    public function extractImageFromItem($item)
    {

        // 1. enclosure (TOI & most news sites)
        $enclosure = $item->get_enclosure();
        if ($enclosure && $enclosure->get_link()) {
            return $enclosure->get_link();
        }

        // 2. media:content (some RSS feeds)
        $media = $item->get_item_tags(SIMPLEPIE_NAMESPACE_MEDIARSS, 'content');
        if (!empty($media[0]['attribs']['']['url'])) {
            return $media[0]['attribs']['']['url'];
        }

        // 3. description me embed image (fallback)
        $description = $item->get_description();
        if ($description && preg_match('/<img[^>]+src=["\']([^"\']+)["\']/i', $description, $match)) {
            return $match[1];
        }

        // 4. content me bhi check kar lo
        $content = $item->get_content();
        if ($content && preg_match('/<img[^>]+src=["\']([^"\']+)["\']/i', $content, $match)) {
            return $match[1];
        }

        return null;
    }
}
