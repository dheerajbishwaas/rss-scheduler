<?php if ($pager->getPageCount() > 1): ?>
	<ul class="pagination">
		<?php
		$links = $pager->links();
		$total = (int) $pager->getPageCount();

		// Map page number => link info and find current page
		$map = [];
		$current = 1;
		foreach ($links as $link) {
			$num = (int) $link['title'];
			$map[$num] = $link;
			if (! empty($link['active'])) {
				$current = $num;
			}
		}

		// Decide which page numbers to show (first 2, last 2, and around current)
		$show = [];
		$show[] = 1;
		if ($total >= 2) {
			$show[] = 2;
		}
		for ($i = $current - 1; $i <= $current + 1; $i++) {
			if ($i >= 1 && $i <= $total) {
				$show[] = $i;
			}
		}
		if ($total - 1 > 2) {
			$show[] = $total - 1;
		}
		if ($total > 1) {
			$show[] = $total;
		}

		$show = array_values(array_unique($show));
		sort($show);

		// Prev button
		$prev = $current - 1;
		if ($prev >= 1 && isset($map[$prev])) {
			echo '<li class="page-prev"><a href="' . $map[$prev]['uri'] . '" rel="prev">&laquo; Prev</a></li>';
		} else {
			echo '<li class="page-prev disabled"><span>&laquo; Prev</span></li>';
		}

		// Render pages with ellipses
		$last = 0;
		foreach ($show as $p) {
			if ($last && $p > $last + 1) {
				echo '<li class="ellipsis"><span>…</span></li>';
			}

			if (isset($map[$p])) {
				$link = $map[$p];
				$active = ! empty($link['active']) ? 'active' : '';
				echo '<li class="' . $active . '"><a href="' . $link['uri'] . '">' . $link['title'] . '</a></li>';
			} else {
				echo '<li><span>' . $p . '</span></li>';
			}

			$last = $p;
		}

		// Next button
		$next = $current + 1;
		if ($next <= $total && isset($map[$next])) {
			echo '<li class="page-next"><a href="' . $map[$next]['uri'] . '" rel="next">Next &raquo;</a></li>';
		} else {
			echo '<li class="page-next disabled"><span>Next &raquo;</span></li>';
		}
		?>
	</ul>
<?php endif ?>